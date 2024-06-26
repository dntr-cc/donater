@php $isVolunteer = \App\Http\Controllers\UserController::VOLUNTEERS === $whoIs; @endphp
@extends('layouts.base')
@section('page_title', $whoIs . ' сайту donater.com.ua')
@section('page_description', 'Донатити будуть всі. Телеграм бот для нагадувань з посиланням на банку: щоденно, раз на тиждень, місяць тощо.')
@section('og_image', url('/images/donater.com.ua.png'))
@section('og_image_width', '1200')
@section('og_image_height', '630')
@push('head-scripts')
    @vite(['resources/js/masonry.js'])
@endpush
@section('breadcrumb-current', $whoIs)
@section('content')
    <h2>{{ $whoIs }}</h2>
    <div class="row row-cols-1 row-cols-xl-3 row-cols-lg-2 row-cols-md-2 row-cols-sm-1 g-4 masonry-grid">
    @php /** @var \Illuminate\Support\Collection|\App\Models\User[] $users */ @endphp
    @forelse($users->shuffle()->all() as $user)
            @include('layouts.user_item', ['user' => $user, 'masonry' => 'masonry-grid-item', 'isVolunteer' => $isVolunteer])
        @empty
            <p>{{ $whoIs }} не знайдені</p>
        @endforelse
    </div>
    @if($isVolunteer && auth()?->user())
        @include('subscribe.modal')
    @endif
@endsection
