<?php

namespace App\Otp;

use SadiqSalau\LaravelOtp\Contracts\OtpInterface as Otp;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;

class UserRegistrationOtp implements Otp
{
    // username
    // email
    // firstname
    // country_code
    // phone
    // lastname
    /**
     * Constructs Otp class
     * @param string $username
     * @param string $phone
     * @param string $email
     * @param string $firstname
     * @param string $country_code
     * @param string $lastname
     */
    public function __construct(
        protected string $username,
        protected string $phone,
        protected string $email,
        protected string $firstname,
        protected string $country_code,
        protected string $lastname,
    ) {
    }

    /**
     * Processes the Otp
     *
     * @return mixed
     */
    public function process()
    {
        /** @var User */
        $user = User::unguarded(function () {
            return User::create([
                'username'  => $this->username,
                'phone' => $this->phone,
                'email' => $this->email,
                'firstname' => $this->firstname,
                'country_code' => $this->country_code,
                'lastname' => $this->lastname,
                'password' => Hash::make(random_bytes(10)),
            ]);
        });

        event(new Registered($user));

        Auth::login($user);

        return [
            'user' => $user
        ];
    }
}
