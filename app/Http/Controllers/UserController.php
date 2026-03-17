<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;


class UserController extends Controller
{
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
            // if(Auth::attempt($request->only('email','password'))){
            $user = User::where('email', $request->email)->first();

            if ($user && Hash::check($request->password, $user->password)) {
                if ($user->status === "active") {
                    // Generate OTP
                    $otp = rand(100000, 999999);

                    $user->otp=$otp;
                    $user->otp_expire_at =now()->addMinutes(5);
                    $user->save();
                    // Store OTP and user ID in session
                    session([
                        'login_otp' => $otp,
                        'login_user_id' => $user->id
                    ]);

                    // Logout until OTP verified
                    Auth::logout();

                    // Send OTP mail
                    //Mail::to($user->email)->send(new OtpMail($otp));

                    return response()->json([
                        'status' => 'otp_required',
                        'otp' => $otp,
                        'message' => 'OTP sent to your email'
                    ]);

                } else {
                    Auth::logout();
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Your account is inactive'
                    ]);
                }
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Invalid email or password'
            ]);
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

            $user = User::where('otp',$request->otp)->first();
            if (!empty($user) && now() <= $user->otp_expire_at) {
                Auth::login($user);
                $request->session()->regenerate();
                session()->forget(['login_otp', 'login_user_id']);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Login successful'
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Invalid OTP'
            ]);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

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
        $user = Auth::user();
        return view('profile', ['user' => $user]);
    }

    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:100', 'regex:/^[a-zA-Z0-9\s]+$/'],
            'phone' => ['required', 'digits:10'],
            'profile_picture' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048']
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->phone = $request->phone;

        if ($request->hasFile('profile_picture')) {
            $image = $request->file('profile_picture');

            $uuid = Str::uuid()->toString();    //random string
            $imageName = $image->getClientOriginalName();   // name with extension
            $filename = pathinfo($imageName, PATHINFO_FILENAME);    //name without extension
            $extension = $image->getClientOriginalExtension();  //extension

            $profile_image = $filename . '_' . $uuid . '.' . $extension;
            $image->storeAs('profile', $profile_image, 'public');  //store in public / products
            $user->profile_picture = $profile_image;
        }


        if ($user->save()) {
            notify()->success('Profile Updated Successfully');
            return redirect('/home');
        }
        return back();
    }
}
