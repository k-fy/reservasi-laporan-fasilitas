<?php

namespace App\Http\Requests\Auth;

use App\Http\Controllers\AdminController;
use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        // Email & password benar, tetapi hanya akun berstatus "active" yang boleh masuk
        $this->ensureAccountIsActive();

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Tolak login untuk akun yang menunggu verifikasi, ditolak, atau ditangguhkan.
     *
     * @throws ValidationException
     */
    protected function ensureAccountIsActive(): void
    {
        $user = Auth::user();

        if ($user->status === User::STATUS_ACTIVE) {
            return;
        }

        $message = match ($user->status) {
            AdminController::STATUS_PENDING  => 'Akun kamu masih menunggu verifikasi dari Admin. Silakan coba lagi setelah akun diverifikasi.',
            AdminController::STATUS_REJECTED => 'Pendaftaran akun kamu ditolak oleh Admin. Hubungi bagian sarana prasarana untuk informasi lebih lanjut.',
            User::STATUS_SUSPENDED           => 'Akun kamu sedang ditangguhkan. Hubungi Admin untuk mengaktifkan kembali.',
            default                          => 'Akun kamu tidak aktif. Hubungi Admin untuk informasi lebih lanjut.',
        };

        // Batalkan sesi yang baru saja dibuat oleh Auth::attempt()
        Auth::guard('web')->logout();

        throw ValidationException::withMessages([
            'email' => $message,
        ]);
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
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
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}