<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Jobs\SendEmailJob;
use Exception;
use Illuminate\Http\Request;
use App\Models\EmailRecord;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    function emailForm()
    {
        $user = User::select('id', 'email')->get();
        return view('emails.sendEmail', ['users' => $user]);
    }
    function send_email(Request $request)
    {
        try{
            $request->validate([
            'subject' => 'required|string|min:3|max:50',
            'emails' => 'required|array|min:1',
            'emails.*' => 'required|email',
            'message' => 'required|string',
            'file' => 'nullable|file|mimes:jpg,webp,jpeg,png,pdf,doc,docx|max:20480'
        ]);

        $emails = $request->emails;
        $subject = $request->subject;
        $message = $request->message;

        $filename = null;
        if ($request->hasFile('file')) {
            $filename = time() . ".".$request->file('file')->extension();
            $request->file('file')->move('email_files', $filename);
        }


        foreach ($emails as $email) {
            // Log::info('inside log');
            SendEmailJob::dispatch($email, $subject, $message,$filename);

            EmailRecord::create([
                'to'=>$email,
                'subject'=>$subject,
                'message'=>$message,
                'shared_file'=>$filename,
                'sent_at'=> now()->format('Y-m-d H:i:s')
            ]);

        }
        return back()->with('success', 'Emails sent successfully!');
        } catch(Exception $e){
            \Log::info('error :'.$e);
        }
    }
}
