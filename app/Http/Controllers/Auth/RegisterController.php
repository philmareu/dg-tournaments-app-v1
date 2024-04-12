<?php

namespace DGTournaments\Http\Controllers\Auth;

use DGTournaments\Events\NewUserActivated;
use DGTournaments\Models\User\UserReferral;
use DGTournaments\Models\User\User;
use DGTournaments\Http\Controllers\Controller;
use DGTournaments\Services\Auth\UserActivation;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

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
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    protected $userActivation;

    protected $referral;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserActivation $userActivation, UserReferral $referral)
    {
        $this->middleware('guest');
        $this->userActivation = $userActivation;
        $this->referral = $referral;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:50',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        if($request->has('referral'))
        {
            $referral = $this->referral->where('code', $request->referral)->first();
            $user->referredBy()->associate($referral->referredBy)->save();
            $referral->delete();
        }

        $this->userActivation->sendActivationMail($user);
        return redirect('login')->with('success', 'Please check your email to activation your account. If you do not receive the email, please check your spam folder or contact us at admin@dgtournaments.com. Thanks!');
    }

    public function activateUser($token)
    {
        if ($user = $this->userActivation->activateUser($token)) {
            event(new NewUserActivated($user));
            auth()->login($user);
            return redirect($this->redirectPath());
        }
        abort(404);
    }
}