<?php

namespace DGTournaments\Http\Resources;

use DGTournaments\Data\Dates;
use DGTournaments\Data\Location;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;

class Tournament extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        Resource::withoutWrapping();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'city' => $this->city,
            'state_province' => $this->state_province,
            'country' => $this->country,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'start' => $this->start->format('Y-m-d'),
            'end' => $this->end->format('Y-m-d'),
            'director' => $this->director,
            'email' => $this->email,
            'phone' => $this->phone,
            'timezone' => $this->timezone,
            'description' => $this->description,
            'paypal' => $this->paypal,
            'format_id' => $this->format_id,
            'format' => $this->format,
            'poster' => $this->poster,
            'managers' => $this->managers,
            'special_event_types' => $this->specialEventTypes,
            'classes' => $this->classes,
            'pdga_tiers' => $this->pdgaTiers,
            'dates' => Dates::make($this->start, $this->end)->formattedDateSpan(),
            'location' => Location::make($this->city, $this->country, $this->state_province)->formatted(),
            'special_event_type_ids' => $this->specialEventTypes->pluck('id'),
            'class_ids' => $this->classes->pluck('id'),
            'registration' => $this->registration,
            'path' => $this->path,
            'courses' => $this->courses,
            'schedule' => $this->schedule,
            'schedule_by_day' => $this->schedule->groupedByDay(),
            'player_packs' => $this->playerPacks,
            'links' => $this->links,
            'media' => $this->media,
            'stripe_account' => $this->when($this->isManager(), $this->stripeAccount),
            'stripe_account_id' => $this->when($this->isManager(), $this->stripe_account_id),
            'can_except_online_payments' => $this->canExceptOnlinePayments(),
            'sponsorships' => $this->load('sponsorships.tournamentSponsors.sponsor.logo')->sponsorships,
            'claim_request' => $this->claimRequest
        ];
    }

    private function isManager()
    {
        if(! Auth::check()) return false;

        return Auth::user()->hasAccessToTournament($this->id);
    }
}
