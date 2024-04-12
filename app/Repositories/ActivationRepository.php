<?php namespace DGTournaments\Repositories;

use DGTournaments\Models\User\User;
use DGTournaments\Models\User\UserActivation;


class ActivationRepository
{
    protected $userActivation;

    public function __construct(UserActivation $userActivation)
    {
        $this->userActivation = $userActivation;
    }

    protected function getToken()
    {
        return hash_hmac('sha256', str_random(40), config('app.key'));
    }

    public function createActivation(User $user)
    {

        $activation = $this->getActivation($user);

        if (is_null($activation)) {
            return $this->createToken($user);
        }
        return $this->regenerateToken($user);

    }

    private function regenerateToken($user)
    {

        $token = $this->getToken();

        return $this->userActivation->where('user_id', $user->id)->update([
            'token' => $token
        ]);
    }

    private function createToken(User $user)
    {
        $token = $this->getToken();
        $user->activation()->create(['token' => $token]);
        return $token;
    }

    public function getActivation(User $user)
    {
        return $user->activation;
    }

    public function getActivationByToken($token)
    {
        return $this->userActivation->where('token', $token)->first();
    }

    public function deleteActivation($token)
    {
        $this->userActivation->where('token', $token)->delete();
    }
}