@extends('layouts.base')
@section('page_title', $whoIs . ' сайту donater.com.ua')
@section('page_description', $whoIs . ' сайту donater.com.ua')
@section('og_image', url('/images/index.png'))
@section('og_image_width', '1200')
@section('og_image_height', '470')
@section('og_image_alt', 'donater.com.ua - Донатити будуть всі. Телеграм бот для нагадувань з посиланням на банку: щоденно, раз на тиждень, місяць тощо.')
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
