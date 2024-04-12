<?php

namespace DGTournaments\Repositories;


use DGTournaments\Models\User\User;
use DGTournaments\Services\Auth\UserActivation;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class UserRepository
{
    protected $user;

    protected $userActivation;

    public function __construct(User $user, UserActivation $userActivation)
    {
        $this->user = $user;
        $this->userActivation = $userActivation;
    }

    public function registerNewUser($attributes)
    {
        event(new Registered($user = $this->user->create($attributes)));

        $this->userActivation->sendActivationMail($user);

        return $user;
    }

    public function getUsersWithNotifiableSearches() : Collection
    {
        $users = $this->user->has('searches')->get();

        return $users->map(function (User $user) {
            return [
                'user' => $user,
                'searches' => $user->searches()->where('wants_notification', 1)->get()
            ];
        })->reject(function($item) {
            return $item['searches']->isEmpty();
        });
    }
}