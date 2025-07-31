<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;

class OtpVerificationMail extends Notification
{
    use Notifiable;

    private $otp;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($otp)
    {
        $this->otp = $otp;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        // Get Time Diff
        $startTime = Carbon::parse($notifiable->is_otp_valid);
        $finishTime = Carbon::now();
        return (new MailMessage)->mailer('zepto')->view('mail.otpVerification',
        [
            'otp' => $this->otp,
            'name'=>$notifiable->name,
            'expireTime'=> $startTime->diff($finishTime)->format('%I:%S'),

        ])->subject('One-Time Verification Code: Access Your Account')
        ->from(env('MAIL_FROM_ADDRESS'), 'IFRS Tech Team');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
