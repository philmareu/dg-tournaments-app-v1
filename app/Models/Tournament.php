<?php

namespace DGTournaments\Models;

use Carbon\Carbon;
use DGTournaments\Collections\TournamentsCollection;
use DGTournaments\Data\Dates;
use DGTournaments\Data\Location;
use DGTournaments\Events\Models\TournamentCreated;
use DGTournaments\Events\Models\TournamentUpdating;
use DGTournaments\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Tournament extends Model
{
    use SoftDeletes, Searchable;

    protected $appends = [
        'location',
        'date_span',
        'path',
        'special_event_type_ids',
        'class_ids',
        'schedule_by_day'
    ];

    protected $hidden = [
        'authorization_email'
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float'
    ];

    protected $fillable = [
        'name',
        'slug',
        'city',
        'state_province',
        'country',
        'latitude',
        'longitude',
        'start',
        'end',
        'email',
        'phone',
        'description',
        'poster_id',
        'format_id',
        'timezone',
        'director',
        'paypal'
    ];

    protected $dates = [
        'start',
        'end'
    ];

    protected $dispatchesEvents = [
        'created' => TournamentCreated::class,
        'updating' => TournamentUpdating::class
    ];

    public function newCollection(array $models = [])
    {
        return new TournamentsCollection($models);
    }

    public function classes()
    {
        return $this->belongsToMany(Classes::class, 'class_tournament', null, 'class_id');
    }

    public function divisions()
    {
        return $this->belongsToMany(Division::class)->withPivot('quantity', 'cost');
    }

    public function pdgaTiers()
    {
        return $this->belongsToMany(PdgaTier::class);
    }

    public function sponsorships()
    {
        return $this->hasMany(Sponsorship::class)->orderBy('tier');
    }

    public function specialEventTypes()
    {
        return $this->belongsToMany(SpecialEventType::class);
    }

    public function media()
    {
        return $this->belongsToMany(Upload::class, 'media');
    }

    public function format()
    {
        return $this->belongsTo(Format::class);
    }

    public function sponsors()
    {
        return $this->hasMany(TournamentSponsor::class);
    }

    public function stripeAccount()
    {
        return $this->belongsTo(StripeAccount::class);
    }

    public function managers()
    {
        return $this->belongsToMany(User::class, 'managers');
    }

    public function getPathAttribute()
    {
        return '/disc-golf-tournament/' . $this->id . '/' . $this->slug;
    }

    public function courses()
    {
        return $this->hasMany(TournamentCourse::class);
    }

    public function schedule()
    {
        return $this->hasMany(Schedule::class, 'tournament_id');
    }

    public function canExceptOnlinePayments()
    {
        return (bool) ! is_null($this->stripe_account_id);
    }

    public function getLocationAttribute()
    {
        return Location::make($this->city, $this->country, $this->state_province);
    }

    public function getSpecialEventTypeIdsAttribute()
    {
        return $this->specialEventTypes->pluck('id');
    }

    public function getClassIdsAttribute()
    {
        return $this->classes->pluck('id');
    }

    public function getScheduleByDayAttribute()
    {
        return $this->schedule->groupedByDay();
    }

    public function followers()
    {
        return $this->hasMany(Follow::class, 'resource_id')->where('resource_type', Tournament::class);
    }

    public function transfers()
    {
        return $this->hasMany(Transfer::class);
    }

    public function validForSearch()
    {
        return $this->isBeforeDateCutoff()
            && $this->hasLatLng();
    }

    /**
     * @return mixed
     */
    private function isBeforeDateCutoff()
    {
        return $this->end->addDays(3)->isFuture();
    }

    public function hasLatLng()
    {
        return ! (is_null($this->latitude) || is_null($this->longitude));
    }

    public function toSearchableArray()
    {
        if(! $this->validForSearch()) return [];

        $this->load('sponsorships');

        return [
            'objectID' => $this->id,
            'name' => $this->name,
            'city' => $this->city,
            'state_province' => $this->state_province,
            'country' => $this->country,
            'latitude' => (float) $this->getLat(),
            'longitude' => (float) $this->getLng(),
            'dates' => $this->dateSpan->formattedDateSpan(),
            'month_number' => (int) $this->start->format('n'),
            'start_unix' => (int) $this->start->format('U'),
            'year_month' => (int) $this->start->format('Ym'),
            'end_timestamp' => (int) $this->end->format('U'),
            'path' => $this->path,
            'classes' => $this->classes->pluck('title')->toArray(),
            'pdga_tiers' => $this->pdgaTiers->pluck('code')->toArray(),
            'format' => $this->format->title,
            'month' => $this->start->format('M'),
            'weekday' => $this->start->format('D'),
            'day' => $this->start->format('j'),
            'year' => $this->start->format('Y'),
            'special_event_types' => $this->specialEventTypes->pluck('title')->toArray(),
            'sanctioned' => $this->getSanctionedByPdgaAttribute() ? 'PDGA' : 'Unsanctioned',
            'has_sponsorships' => $this->sponsorships->count() ? 'Yes' : 'No',
            'sponsorship_prices' => $this->getSponsorshipPrices()
        ];
    }

    public function flags()
    {
        return $this->belongsToMany(FlagType::class, 'flags')->withPivot(['id', 'notes', 'review_on'])->withTimestamps();
    }

    public function activeFlags()
    {
        return $this->belongsToMany(FlagType::class, 'flags')
            ->wherePivot('review_on', '<', Carbon::now())
            ->withPivot(['id', 'notes', 'review_on']);
    }

//    public function hasActiveFlags()
//    {
//        return (bool) $this->flags->where('review_on', '<', Carbon::now())->count();
//    }

    /**
     * @return array
     */
    private function getSponsorshipPrices() : array
    {
        return array_map(function ($cents) {
            return $cents / 100;
        }, $this->sponsorships->pluck('cost')->toArray());
    }

    public function relatedByEmail()
    {
        return $this->hasMany(Tournament::class, 'authorization_email', 'authorization_email')
            ->orderBy('start')
            ->where('end', '>', Carbon::now()->subDay(1));
    }

    public function relatedByEmailWithFlags()
    {
        return $this->hasMany(Tournament::class, 'authorization_email', 'authorization_email')
            ->where('end', '>', Carbon::now()->subDay(1))
            ->whereHas('flags', function($query) {
                $query->where('review_on', '<', Carbon::now());
            });
    }

    public function headquartersWasUpdated() : bool
    {
        return $this->activities()->where('type', 'tournament.headquarters.updated')->exists();
    }







    public function scopePast($query)
    {
        return $query->where('end', '<', Carbon::now());
    }

    public function scopeFuture($query)
    {
        return $query->where('end', '>=', Carbon::now());
    }

    public function scopeWithCourses($query, $yesNo)
    {
        if($yesNo) return $query->has('courses', '>', 0);

        return $query->has('courses', '=', 0);
    }

    public function getDateSpanAttribute()
    {
        return Dates::make($this->start, $this->end);
    }

    public function getIsPastAttribute()
    {
        return $this->end->isPast() ? 1 : 0;
    }

    public function staff()
    {
        return $this->hasMany(Staff::class);
    }

    public function issues()
    {
        return $this->hasMany(Issue::class, 'tournament_id');
    }

    public function courseMapBounds()
    {
        $extend = .0625;

        return [
            'north' => $this->courses->max('latitude') + $extend,
            'east' => $this->courses->min('longitude') - $extend,
            'south' => $this->courses->min('latitude') - $extend,
            'west' => $this->courses->max('longitude') + $extend
        ];
    }

    public function hasLocation()
    {
        return $this->courses->count() OR ! is_null($this->latitude);
    }

    public function hasCourses()
    {
        return $this->courses->count();
    }

    public function getLat()
    {
        return $this->latitude;
    }

    public function getLng()
    {
        return $this->longitude;
    }

    public function getLatLng()
    {
        return [$this->getLat(), $this->getLng()];
    }

    public function getWeatherAttribute($value)
    {
        return unserialize($value);
    }

    public function setWeatherAttribute($value)
    {
        $this->attributes['weather'] = serialize($value);
    }

    public function getVenuesAttribute($value)
    {
        return unserialize($value);
    }

    public function setVenuesAttribute($value)
    {
        $this->attributes['venues'] = serialize($value);
    }

    public function activities()
    {
        return $this->morphMany(Activity::class, 'resource')->latest();
    }

    public function links()
    {
        return $this->hasMany(Link::class, 'tournament_id');
    }

    public function claimRequest()
    {
        return $this->hasOne(ClaimRequest::class);
    }

    public function getSanctionedByPdgaAttribute()
    {
        return (bool) $this->pdgaTiers->count();
    }

    public function dataSource()
    {
        return $this->belongsTo(DataSource::class);
    }

    public function poster()
    {
        return $this->belongsTo(Upload::class)->withDefault([
            'id' => null,
            'filename' => 'default-poster.png',
            'alt' => 'Default DGT poster'
        ]);
    }

    public function registration()
    {
        return $this->hasOne(Registration::class)->withDefault([
            'id' => null,
            'opens_at' => null,
            'closes_at' => null,
            'url' => null
        ]);
    }

    public function playerPacks()
    {
        return $this->hasMany(PlayerPack::class);
    }
}
