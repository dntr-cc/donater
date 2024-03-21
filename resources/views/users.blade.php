@extends('layouts.base')
@php $title = $whoIs . ' сайту donater.com.ua'; @endphp
@section('page_title', $title)
@section('page_description', $title)
@section('og_image', url('/images/donater.com.ua.png'))
@section('og_image_width', '1200')
@section('og_image_height', '630')
@section('og_image_title', $title)
@section('og_image_alt', 'Донатити будуть всі. Телеграм бот для нагадувань з посиланням на банку: щоденно, раз на тиждень, місяць тощо.')
@php $subscribeAllowed = \App\Http\Controllers\UserController::VOLUNTEERS === $whoIs; @endphp
@section('content')
    <div class="row">
        <h2>{{ $whoIs }}</h2>
        @include('layouts.users_block', compact('users', 'subscribeAllowed'))
        @if($subscribeAllowed && auth()?->user())
            @include('subscribe.modal')
        @endif
    </div>
@endsection
