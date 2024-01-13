@extends('layouts.base')
@section('page_title', $whoIs . ' сайту donater.com.ua')
@section('page_description', $whoIs . ' сайту donater.com.ua')
@php $subscribeAllowed = true; @endphp
@section('content')
    <div class="row">
        <h2>{{ $whoIs }}</h2>
        @include('layouts.users_block', compact('users', 'subscribeAllowed'))
        @if(\App\Http\Controllers\UserController::VOLUNTEERS === $whoIs && auth()?->user())
            @include('subscribe.modal')
        @endif
    </div>
@endsection
