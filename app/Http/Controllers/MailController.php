<?php

namespace App\Http\Controllers;


use App\Models\User;
use App\Mail\SendEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function sendDemoEmail(Request $request)
    {
        // $to_email = $request->input('to_email');
        // $subject = $request->input('subject');
        // $body = $request->input('body');
        $to_email = $request->query('to_email');
        $subject = $request->query('subject');
        $body = $request->query('body');

        try {
            Mail::to($to_email)->send(new SendEmail($to_email, $subject, $body));
            $message = 'Email sent successfully!';
        } catch (\Exception $e) {
            $message = 'Error sending email: ' . $e->getMessage();
        }

        return response()->json(['message' => $message]);
    }
    public function sendVerificationEmail(User $user, $verificationCode)
    {

        $recipient = $user->email;
        $subject = 'Verification Code';
        $body = 'Your verification code is: ' . $verificationCode;

        try {
            Mail::to($recipient)->send(new SendEmail($recipient, $subject, $body));
            $success = true;
            $message = 'Email sent successfully!';
        } catch (\Exception $e) {
            $success = false;
            $message = 'Error sending email: ' . $e->getMessage();
        }

        return ['success' => $success, 'message' => $message];
    }
}
