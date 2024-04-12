@extends('layouts.default')

@section('title')
    Checkout
@endsection

@section('meta')
    <meta name="description" content="Check out page for orders">
@endsection

@section('breadcrumbs')
    <li><span href="#">Checkout</span></li>
@endsection

@section('content')
    @parent

    @include('laraform::alerts.default')

    <div class="uk-container uk-container-small uk-margin">
        <checkout
                :order="order"
                v-on:order-updated="updateOrder"
                v-on:user-updated="updateUser"
                :user="user"
        ></checkout>
    </div>

@endsection

@section('footer')
    @include('partials.footer', ['size' => 'large'])
@endsection