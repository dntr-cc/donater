@php $subscribeAllowed = \App\Http\Controllers\UserController::VOLUNTEERS === $whoIs; @endphp
@extends('layouts.base')
@section('page_title', $whoIs . ' сайту donater.com.ua')
@section('page_description', 'Донатити будуть всі. Телеграм бот для нагадувань з посиланням на банку: щоденно, раз на тиждень, місяць тощо.')
@section('og_image', url('/images/donater.com.ua.png'))
@section('og_image_width', '1200')
@section('og_image_height', '630')
@section('breadcrumb-path')
    @if($subscribeAllowed)
        <li class="breadcrumb-item"><a href="{{ route('users') }}">Донатери</a></li>
    @else
        <li class="breadcrumb-item"><a href="{{ route('volunteers') }}">Волонтери</a></li>
    @endif
    @endsection
@section('breadcrumb-current', $whoIs)
@section('content')
    <div class="row">
        <h2>{{ $whoIs }}</h2>
        @include('layouts.users_block', compact('users', 'subscribeAllowed'))
        @if($subscribeAllowed && auth()?->user())
            @include('subscribe.modal')
        @endif
    </div>
@endsection
