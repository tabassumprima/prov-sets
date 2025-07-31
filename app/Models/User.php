<?php

namespace App\Models;

use App\Traits\{TwoFactorAuthenticatable,CheckPermission,EventTrait, Loggable};
use Illuminate\Database\Eloquent\Casts\Attribute;
use Lab404\Impersonate\Models\Impersonate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Hash;
use App\Notifications\PasswordReset;
// use App\Traits\CheckPermission;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Notifications\OtpVerificationMail;
// use App\Traits\EventTrait;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, TwoFactorAuthenticatable, Impersonate, CheckPermission, EventTrait,Loggable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'is_active',
        'organization_id',
        'google2fa_secret',
        'password',
        'otp',
        'is_otp_valid',
        'is_otp_verified',
        'verification_type'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    protected function email(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Str::lower($value),
        );
    }

    public function tickets()
    {
        return $this->hasMany(UserTicket::class);
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new PasswordReset($token));
    }

    /**
     * Route notifications for the mail channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return array|string
     */
    public function routeNotificationForMail($notification)
    {
        // Return email address and name...
        return [$this->email => $this->name];
    }

    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    // Otp verification
    public function verifyOtp()
    {
        // Conditions
        if ($this->otp && $this->is_otp_valid && $this->is_otp_verified == false) {
            return false;
        }else if($this->is_otp_verified == false){
            $this->sendOtp();
        }
        else if ($this->is_otp_verified == true) {
            return true;
        }
        else{
            return false;
        }
    }

    // Generate otp and update in database
    public function sendOtp()
    {
        $otp = rand(100000,999999);
        $hash = Hash::make($otp);
        $this->update(['otp' => $hash,'is_otp_valid' => Carbon::now()->addMinutes(5)]);
        $this->sendOtpEmail($otp);
    }

    // Send otp email
    public function sendOtpEmail($otp)
    {
        $this->notify(new OtpVerificationMail($otp));
    }
}
