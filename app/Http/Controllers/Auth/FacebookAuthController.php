<?php

namespace DGTournaments\Http\Controllers\Auth;

use DGTournaments\Events\NewUserActivated;
use DGTournaments\Models\User\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use DGTournaments\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class FacebookAuthController extends Controller
{
    protected $user;

    public function __construct(User $user)
    {
        $this->middleware('guest');
        $this->user = $user;
    }

    /**
     * Redirect the user to the Social authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Obtain the user information from Social.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
        try
        {
            $user = Socialite::driver('facebook')->user();

            if(is_null($user->getEmail()))
            {
                Log::info('User did not approve email access.');

                return $this->showFailedResponse();
            }
        }
        catch (\Exception $exception)
        {
            return $this->showFailedResponse();
        }

        $dgtUser = $this->findOrCreateNewUser($user);

        Auth::login($dgtUser, true);

        return redirect('/');
    }

    private function findOrCreateNewUser($user)
    {
        $existingUsers = $this->user->where('email', $user->getEmail())->first();

        if($existingUsers) return $existingUsers;

        $newUser =  $this->user->create([
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'provider' => 'facebook',
            'provider_id' => $user->getId(),
            'token' => $user->token
        ]);

        $newUser->activated = 1;
        $newUser->save();

        event(new Registered($newUser));
        event(new NewUserActivated($user));

        return $newUser;
    }

    private function showFailedResponse()
    {
        return redirect('login')->with('failed', 'Login with Facebook was unsuccessful');
    }
}
