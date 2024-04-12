<?php

namespace DGTournaments\Http\Controllers\Endpoints;

use DGTournaments\Repositories\OrderRepository;
use Illuminate\Http\Request;
use DGTournaments\Http\Controllers\Controller;

class OrderCurrentEndpointController extends Controller
{
    protected $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function __invoke(Request $request)
    {
        $order = $this->orderRepository->getOrderByUnique($request->cookie('_oo'));

        return is_null($order) ? null : $order->load('sponsorships.sponsorship.tournament.poster', 'user');
    }
}
