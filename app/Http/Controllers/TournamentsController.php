<?php

namespace DGTournaments\Http\Controllers;

use DGTournaments\Models\SpecialEventType;
use DGTournaments\Models\Tournament;
use DGTournaments\Models\TournamentCourse;
use Illuminate\Http\Request;
use DGTournaments\Repositories\TournamentRepository;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;


class TournamentsController extends Controller
{
    protected $tournamentRepository;

    protected $specialEventTypes;

//    protected $tournamentLoad = [
//        'claimRequest',
//        'courses',
//        'format',
//        'poster',
//        'links',
//        'uploads.upload',
//        'sponsors',
//        'registration',
//        'specialEventTypes',
//        'playerPacks.items',
//        'stripeAccount',
//        'managers'
//    ];

    public function __construct(TournamentRepository $tournamentRepository, SpecialEventType $specialEventTypes)
    {
        $this->tournamentRepository = $tournamentRepository;
        $this->specialEventTypes = $specialEventTypes;
    }

    public function submit()
    {
        return view('pages.tournament.submit');
    }

    public function index($tournamentId, $slug, $section = 'show')
    {
        // Important! This fails once our internal IDs exceed the PDGA IDs
        // But at that time we won't need it.
        $tournament = $this->legacy3($tournamentId);

        if(is_null($tournament)) $tournament = Tournament::whereId($tournamentId)->first();

        if(is_null($tournament)) abort(404);

        if($tournament->slug != $slug) return redirect(url($tournament->path), 301);

        if(method_exists($this, $section)) return $this->$section($tournament);

        abort(404);
    }

    public function show($tournament)
    {
        $weather = $this->tournamentRepository->getWeather($tournament);

        $markers = [
            'headquarters' => $this->getHeadquartersMarkerData($tournament),
            'courses' => $tournament->courses->map(function(TournamentCourse $tournamentCourse) {
                return [
                    'popup' => view('partials.markers.tournament_course')->withCourse($tournamentCourse)->render(),
                    'latitude' => $tournamentCourse->latitude,
                    'longitude' => $tournamentCourse->longitude
                ];
            })
        ];

        $bounds = $this->getBoundsFromMarkers($tournament, $markers);

        return view('pages.tournament.index')
            ->withTournament($tournament)
            ->withMarkers($markers)
            ->withBounds($bounds)
            ->withWeather($weather);
    }

    public function sponsors($tournament)
    {
        $markers = [
            'headquarters' => $this->getHeadquartersMarkerData($tournament),
            'courses' => $tournament->courses->map(function(TournamentCourse $tournamentCourse) {
                return [
                    'popup' => view('partials.markers.tournament_course')->withCourse($tournamentCourse)->render(),
                    'latitude' => $tournamentCourse->latitude,
                    'longitude' => $tournamentCourse->longitude
                ];
            })
        ];

        $bounds = $this->getBoundsFromMarkers($tournament, $markers);

        return view('pages.tournament.sponsors')
            ->withTournament($tournament->load('sponsorships'))
            ->withMarkers($markers)
            ->withBounds($bounds);
    }

    public function getVenues(Tournament $tournament)
    {
        $venues = $this->tournamentRepository->getNearbyVenues($tournament);

        return response()->json([
            'venues' => $venues
        ]);
    }

    private function legacy3($tournamentId)
    {
        return $this->tournamentRepository->getPDGAListing($tournamentId);
    }

    private function getBoundsFromMarkers(Tournament $tournament, $markers)
    {
        $markers = collect($markers);

        $padding = .005;

        // No headquarters and no courses (0, 0)
        if(! $tournament->headquartersWasUpdated() && $markers['courses']->isEmpty()) return [
            'center' => [$tournament->latitude, $tournament->longitude],
            'latitude' => [
                'min' => $tournament->latitude - $padding,
                'max' => $tournament->latitude + $padding
            ],
            'longitude' => [
                'min' => $tournament->longitude - $padding,
                'max' => $tournament->longitude + $padding
            ]
        ];

        // Headquarters but no courses (1, 0)
        if($tournament->headquartersWasUpdated() && $markers['courses']->isEmpty()) return [
            'center' => [$markers['headquarters']['latitude'], $markers['headquarters']['longitude']],
            'latitude' => [
                'min' => $markers['headquarters']['latitude'] - $padding,
                'max' => $markers['headquarters']['latitude'] + $padding
            ],
            'longitude' => [
                'min' => $markers['headquarters']['longitude'] - $padding,
                'max' => $markers['headquarters']['longitude'] + $padding
            ]
        ];

        // Headquarters and courses (1, 1)
        if($tournament->headquartersWasUpdated()) {
            $minLatitude = collect([$markers['headquarters']['latitude']]);
            $maxLatitude = collect([$markers['headquarters']['latitude']]);
            $minLongitude = collect([$markers['headquarters']['longitude']]);
            $maxLongitude = collect([$markers['headquarters']['longitude']]);
        } else {
            $minLatitude = collect([]);
            $maxLatitude = collect([]);
            $minLongitude = collect([]);
            $maxLongitude = collect([]);
        }

        $minLatitude->push($markers['courses']->min('latitude'));
        $maxLatitude->push($markers['courses']->max('latitude'));
        $minLongitude->push($markers['courses']->min('longitude'));
        $maxLongitude->push($markers['courses']->max('longitude'));

        return [
            'center' => [
                ($minLatitude->min() + $minLatitude->max()) / 2,
                ($minLongitude->min() + $minLongitude->max()) / 2,
            ],
            'latitude' => [
                'min' => $minLatitude->min() - $padding,
                'max' => $maxLatitude->max() + $padding
            ],
            'longitude' => [
                'min' => $minLongitude->min() - $padding,
                'max' => $minLongitude->max() + $padding
            ]
        ];
    }

    /**
     * @param $tournament
     * @return array
     */
    private function getHeadquartersMarkerData(Tournament $tournament): array
    {
        if(! $tournament->headquartersWasUpdated()) return [];

        return [
            'popup' => view('partials.markers.headquarters')->withTournament($tournament)->render(),
            'latitude' => $tournament->latitude,
            'longitude' => $tournament->longitude
        ];
    }
}
