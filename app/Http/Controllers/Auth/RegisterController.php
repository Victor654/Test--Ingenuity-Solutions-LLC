<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {

        $rules = array(
            'name' => 'required|string|max:250',
            'email' => 'required|string|email|max:300|unique:users',
            'password' => 'required|string|min:8|confirmed'
        );    
        $messages = array(
            'name.required' => 'Please enter your name, to continue with your registration.',
            'name.string' => 'Please enter a correct name, only Alphabetic format can be used.',
            'name.max' => 'Sorry, the name is too long, please enter a shorter one, maximum 250.',
            'email.required' => 'Please enter your email, to continue with your registration.',
            'email.string' => 'Please enter a correct name, only Alphabetic format can be used.',
            'email.max' => 'Sorry, the email is too long, please enter a shorter one, maximum 300.',
            'email.unique' => 'We are sorry, this email is already in use, try to log in or place a different email.',
            'password.required' => 'Please enter your password, to continue with your registration.',
            'password.string' => 'Please enter a correct name, only Alphabetic format can be used.',
            'password.min' => 'Sorry, the password is too short, please enter a password longer than 8 characters.',
            'password.confirmed' => 'Sorry, the passwords do not match, please try again.',
        );
        return Validator::make($data, $rules, $messages);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
