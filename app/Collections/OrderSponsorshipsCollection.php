<?php

namespace DGTournaments\Collections;


use DGTournaments\Models\OrderSponsorship;
use Illuminate\Database\Eloquent\Collection;

class OrderSponsorshipsCollection extends Collection
{
    public function sortSponsorshipsByTournament()
    {
        return $this->groupBy(function (OrderSponsorship $orderSponsorship) {
            return $orderSponsorship->sponsorship->tournament_id;
        });
    }
}