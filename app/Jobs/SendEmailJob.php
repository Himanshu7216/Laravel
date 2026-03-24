<?php

namespace App\Jobs;
use App\Mail\SendEmail;
use App\Models\EmailRecord;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;


class sendEmailJob implements ShouldQueue
{
    use Queueable;
    public $subject;
    public $message;
    public $email;
    public $filename;

    /**
     * Create a new job instance.
    */
    public function __construct($email, $subject, $message , $filename)
    {
        $this->subject=$subject;
        $this->message=$message;
        $this->email=$email;
        $this->filename = $filename;
Log::info('file name :'.$this->filename);
        }

        /**
         * Execute the job.
        */
        public function handle(): void
        {
            try{
                Log::info('success log');
                Mail::to($this->email)->send(new SendEmail($this->subject,$this->message,$this->filename));
                EmailRecord::where('to',$this->email)
                ->latest()
                ->first()
                ->update(['status'=>'success']);

            }catch(Exception $e){
                Log::info('Exception');
                EmailRecord::where('to',$this->email)
                ->latest()
                ->first()
                ->update(['status'=>'failed']);
                Log::info('error :'.$e);

            }
    }
}
