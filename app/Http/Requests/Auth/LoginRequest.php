<?php

namespace App\Http\Requests\Auth;

use App\Services\UserService;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    private  $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = new UserService();
    }
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate()
    {
        $this->ensureIsNotRateLimited();

        //updating + ['active' => 1] so that ONLY active users can login
        if (! Auth::attempt($this->only('email', 'password') + ['is_active' => 1], $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            if (!$this->userService->emailExists($this->email)) {
                throw ValidationException::withMessages([
                    'email' => __('auth.not_registered'),
                ]);
            }

            if ($this->userService->isActive($this->email) === false) {
                throw ValidationException::withMessages([
                    'email' => __('auth.not_active'),
                ]);
            }


            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }
        // update is otp verified on login
        $user = Auth::user();
        $user->is_otp_verified = 0;
        $user->otp = null;
        $user->is_otp_valid = null;
        $user->save();

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited()
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     *
     * @return string
     */
    public function throttleKey()
    {
        return Str::lower($this->input('email')).'|'.$this->ip();
    }
}
