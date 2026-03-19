<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;
use Mckenziearts\Notify\Facades\LaravelNotify;


class UserController extends Controller
{
    public function loginform()
    {
        return view('login');
    }
    public function login(Request $request)
    {
        // STEP 1 : If OTP is NOT submitted
        if (!$request->otp) {

            // Validate email and password
            $validated = $request->validate([
                'email' => ['required', 'string', 'email', 'max:255'],
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'max:20',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).+$/'
                ]
            ]);

            // Check credentials
            $user = User::where('email', $request->email)->first();

            if ($user && Hash::check($request->password, $user->password)) {
                if ($user->status === "active") {
                    session([
                        'login_user_id' => $user->id,
                        'enable_two_factors' => $user->enable_two_factors
                    ]);
                    //2FA
                    if ($user->enable_two_factors == "enable") {
                        // Generate OTP
                        $otp = rand(100000, 999999);
                        $user->otp = $otp;
                        $user->otp_expire_at = now()->addMinutes(5);
                        $user->save();
                        // Store OTP and user ID in session
                        session([
                            'login_otp' => $otp,
                            'login_user_id' => $user->id
                        ]);

                        // Logout until OTP verified
                        // Auth::logout();

                        // Send OTP mail
                        // Mail::to($user->email)->send(new OtpMail($otp));
                        Mail::to($user->email)->queue(new OtpMail($otp));

                        // return response()->json([
                        //     'status' => 'otp_required',
                        //     'otp' => $otp,
                        //     'message' => 'OTP sent to your email'
                        // ]);
                        // return redirect()->back();
                        return redirect()->back()->withInput();
                    } else {
                        // Auth::login($user);
                        // $request->session()->regenerate();
                        // session()->forget(['login_otp', 'login_user_id']);
                        // return response()->json([
                        //     'status' => 'success',
                        //     'message' => 'Login successful'
                        // ]);
                        Auth::login($user);
                        $request->session()->regenerate();
                        // return redirect('/home')->with('success', 'Login successful');
                        LaravelNotify::success('Login successful 🎉');
                        return redirect('/home');
                        }

                } else {
                    // Auth::logout();
                    // return response()->json([
                    //     'status' => 'error',
                    //     'message' => 'Your account is inactive'
                    // ]);
                    return back()->with('error', 'Your account is inactive');
                }
            }

            // return response()->json([
            //     'status' => 'error',
            //     'message' => 'Invalid email or password'
            // ]);
            // return back()->with('error', 'Invalid email or password');
            LaravelNotify::error('Invalid email or password ❌');
            return back();
        }
        // STEP 2 : OTP verification
        else {
            $request->validate(
                [
                    'otp' => ['required', 'numeric', 'digits:6']
                ],
                [
                    'otp.required' => 'OTP is required.',
                    'otp.numeric' => 'OTP must contain only numbers.',
                    'otp.digits' => 'OTP must be exactly 6 digits.'
                ]
            );
            // ✅ get user from session
            $userId = session('login_user_id');
            $user = User::find($userId);


            if (!$user) {
                // return response()->json([
                //     'status' => 'error',
                //     'message' => 'Session expired. Please login again.'
                // ]);
                return back()->with('error', 'Session expired. Please login again.');
            }
            // ❌ Wrong OTP
            if ($user->otp !== $request->otp) {
                // return response()->json([
                //     'status' => 'error',
                //     'message' => 'Invalid OTP'
                //     ]);
                return back()->with('error', 'Invalid OTP');
            }

            //current time > expiry time
            if (now()->gt($user->otp_expire_at)) {
                // return response()->json([
                //     'status' => 'error',
                //     'message' => 'OTP expired. Please request a new one.'
                // ]);
                return back()->with('error', 'OTP expired. Please request a new one.');
            }

            // ✅ OTP valid
            Auth::login($user);
            $request->session()->regenerate();

            // clear otp after success (important 🔥)
            $user->update([
                'otp' => null,
                'otp_expire_at' => null
            ]);
            session()->forget(['login_otp', 'login_user_id']);
            // return response()->json([
            //     'status' => 'success',
            //     'message' => 'Login successful'
            //     ]);
            // return redirect('/home')->with('success', 'Login successful');
            LaravelNotify::success('Login successful with 2FA 🔐');
            return redirect('/home');
        }
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
    // dd(Auth::user());

    public function home()
    {
        return view('home');
    }

    public function signup(Request $request)
    {
        $validated = $request->validate(
            [
                'name' => ['required', 'string', 'max:100', 'min:3', 'regex:/^[a-zA-Z\s]+$/'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
                'phone' => ['required', 'digits:10'],
                'password' => ['required', 'string', 'min:8', 'max:20', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).+$/'],
                // 'status' => ['required','in:active,inactive'],
                'profile' => ['required', 'file', 'mimes:jpg,jpeg,png,webp', 'max:20480']
            ],
            [
                'profile.required' => 'The profile picture must be a file of type: jpg, jpeg, png, webp.'
            ]
        );

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->password = bcrypt($request->password);
        // $user->status = $request->status;

        if ($request->hasFile('profile')) {
            $image = $request->file('profile');
            $uuid = Str::uuid()->toString();    //random string
            $imageName = $image->getClientOriginalName();   // name with extension
            $filename = pathinfo($imageName, PATHINFO_FILENAME);    //name without extension
            $extension = $image->getClientOriginalExtension();  //extension
            $profile_image = $filename . '_' . $uuid . '.' . $extension;
            $image->storeAs('profile', $profile_image, 'public');  //store in public / products
            $user->profile_picture = $profile_image;
        }

        if ($user->save()) {
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Signup successful'
                ]);
            }
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Signup failed'
        ]);
    }




    public function profile()
    {
        // $user = User::all();
        // return view('profile', ['user' => $user]);
        $user = Auth::user();
        return view('profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $validated = $request->validate(
            [
                'name' => ['required', 'string', 'min:3', 'max:100', 'regex:/^[a-zA-Z0-9\s]+$/'],
                'phone' => ['required', 'digits:10'],
                'profile' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048']
            ],
            [
                'profile.required' => 'The profile picture must be a file of type: jpg, jpeg, png, webp.'
            ]
        );

        $user = Auth::user();
        $user->name = $request->name;
        $user->phone = $request->phone;

        if ($request->hasFile('profile')) {
            $image = $request->file('profile');

            $uuid = Str::uuid()->toString();    //random string
            $imageName = $image->getClientOriginalName();   // name with extension
            $filename = pathinfo($imageName, PATHINFO_FILENAME);    //name without extension
            $extension = $image->getClientOriginalExtension();  //extension

            $profile_image = $filename . '_' . $uuid . '.' . $extension;
            $image->storeAs('profile', $profile_image, 'public');  //store in public / products
            $user->profile_picture = $profile_image;
        }


        if ($user->save()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Profile Update Successfully'
            ]);
            // return redirect('/home');
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Profile Not Update!'
        ]);
        // return back();
    }
    public function toggle2FA(Request $request)
    {
        // dd($request);
        $validated = $request->validate([
            'enable_two_factors' => ['required', 'in:0,1']
        ]);

        $user = Auth::user();
        $user->enable_two_factors = $request->enable_two_factors ? "enable" : "disable";
        $user->save();

        // If enabling → logout
        // if($request->enable_two_factors == "enable")
        if ($request->enable_two_factors == 1) {
            Auth::logout();
            return response()->json([
                'status' => 'logout',
                'message' => '2FA Enabled'
            ]);
        } else {
            return response()->json([
                'status' => 'success',
                'message' => '2FA Disabled'
            ]);
        }
    }
}
