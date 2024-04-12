<?php

namespace DGTournaments\Models\User;

use Carbon\Carbon;
use DGTournaments\Models\Activity;
use DGTournaments\Models\ClaimRequest;
use DGTournaments\Models\Follow;
use DGTournaments\Models\Order;
use DGTournaments\Models\RequestLog;
use DGTournaments\Models\Search;
use DGTournaments\Models\Sponsor;
use DGTournaments\Models\StripeAccount;
use DGTournaments\Models\Tournament;
use DGTournaments\Models\Upload;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'last_name',
        'first_name',
        'email',
        'pdga_number',
        'pdga_rating',
        'password',
        'location',
        'provider',
        'provider_id',
        'token',
        'token_secret'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'is_admin', 'activated'
    ];

    protected $appends = [
        'recent_order'
    ];

    /**
     * Route notifications for the Slack channel.
     *
     * @return string
     */
    public function routeNotificationForSlack()
    {
        return 'https://hooks.slack.com/services/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';
    }

    public function activation()
    {
        return $this->hasOne(UserActivation::class);
    }

    public function claims()
    {
        return $this->hasMany(ClaimRequest::class);
    }

    public function managing()
    {
        return $this->belongsToMany(Tournament::class, 'managers')
            ->orderBy('start', 'asc');
    }

    public function managingPast()
    {
        return $this->belongsToMany(Tournament::class, 'managers')
            ->where('start', '<=', Carbon::now())
            ->orderBy('start', 'asc');
    }

    public function hasTournaments()
    {
        return $this->tournaments->count() OR $this->managing->count();
    }

    public function referrals()
    {
        return $this->hasMany(UserReferral::class, 'referred_by');
    }

    public function referredBy()
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    public function emailNotificationSettings()
    {
        return $this->belongsToMany(UserEmailNotificationType::class, 'user_email_notification_settings', 'user_id', 'type_id');
    }

    public function sponsors()
    {
        return $this->hasMany(Sponsor::class);
    }

    public function stripeAccounts()
    {
        return $this->hasMany(StripeAccount::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function getRecentOrderAttribute()
    {
        return $this->orders()->limit(1)->orderBy('created_at', 'desc')->first();
    }

    public function hasAccessToTournament($tournamentId)
    {
        return (bool) in_array($tournamentId, $this->managing()->select(['id'])->get()->pluck('id')->toArray())
            || $this->is_admin;
    }

    // *** new updates
    public function following()
    {
        return $this->hasMany(Follow::class);
    }

    public function followingTournaments()
    {
        return $this->hasMany(Follow::class)
            ->where('resource_type', Tournament::class)
            ->orderByDesc('updated_at')
            ->with('resource');
    }

    public function activities()
    {
        return $this->morphMany(Activity::class, 'resource')->latest();
    }

    public function feed()
    {
        return $this->belongsToMany(Activity::class)->latest();
    }

    public function searches()
    {
        return $this->hasMany(Search::class);
    }

    public function image()
    {
        return $this->belongsTo(Upload::class)->withDefault([
            'id' => null,
            'filename' => 'default-profile-image.png',
            'alt' => 'Default DGT profile image'
        ]);
    }

    public function requests()
    {
        return $this->hasMany(RequestLog::class);
    }

    public function isAdmin()
    {
        return (bool) $this->is_admin;
    }

    public function isNotAdmin()
    {
        return (bool) ! $this->isAdmin();
    }
}
