@extends('layouts.base')
@php $title = strtr('Збір :fundraising. Збирає :volunteer', [':fundraising' => $fundraising->getName(), ':volunteer' => $fundraising->getVolunteer()->getUsernameWithFullName()]); @endphp
@php $fundraisingBanner = url(app(\App\Services\OpenGraphImageService::class)->getFundraisingImage($fundraising)) @endphp
@section('page_title', $title)
@section('page_description', strtr('Звітність по :fundraising - donater.com.ua', [':fundraising' => $fundraising->getName()]))
@section('og_image_title', $title)
@section('og_image_alt', 'Створить нагадування задонатити на збір ' . $fundraising->getName() . ' на сайті donater.com.ua')
@section('og_image', url($fundraisingBanner))
@section('breadcrumb-current', $fundraising->getName())
@push('head-scripts')
    @vite(['resources/js/tabs.js'])
@endpush
@php $withOwner = true; @endphp
@php $additionalClasses = 'btn-xs'; @endphp
@php $withVolunteer = true; @endphp
@section('content')
    <div class="container">
        <h2 class="pb-2 border-bottom">
            @include('fundraising.status', compact('fundraising', 'withOwner', 'additionalClasses'))
        </h2>
        <div class="row">
            <div class="col-xl-6 col-lg-6 col-md-12 px-2 py-2">
                <div>
                    <img src="{{ url($fundraising->getAvatar()) }}" class="card-img-top "
                         alt="{{ $fundraising->getName() }}">
                </div>
                <div class="card">
                    <div class="d-flex justify-content-center m-2">
                        <h2>Швидкий донат</h2>
                    </div>
                    <div class="d-flex justify-content-center">
                        @include('layouts.monodonat', compact('fundraising'))
                    </div>
                    <div class="d-flex justify-content-center mb-2">
                        <div class="p-3">
                            <h5 class="text-center">{{ \Illuminate\Support\Str::ucfirst(sensitive('волонтер', $fundraising->getVolunteer())) }}</h5>
                            @include('layouts.user_item', [
                                    'user' => $fundraising->getVolunteer(),
                                    'whoIs' => \App\Http\Controllers\UserController::VOLUNTEERS,
                            ])
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-12 px-2 py-2">
                <div class="card mb-2">
                    <div class="card-body">
                        <div>
                            {!! $fundraising->getDescription() !!}
                        </div>
                        @foreach($fundraising->getPrizes() as $pIt => $prize)
                            @foreach($prize->getWinners() as $wIt => $winner)
                                <div class="m-3 lead text-danger">
                                    Приз #{{ $pIt + 1 }}, переможець #{{ $wIt + 1 }}
                                    : {!! $winner->getWinner()->getUserHref() !!}
                                </div>
                            @endforeach
                        @endforeach()
                        <div class="mt-3"></div>
                        @guest
                            <p class="lead"><a href="{{ route('login') }}" class="">Авторизуйтеся</a> за допомогою
                                телеграму щоб
                                отримати код донатера</p>
                        @else
                            <div class="d-flex justify-content-center mb-2 px-2 py-2">
                                <div class="form-floating input-group">
                                    <input type="text" class="form-control" id="userCode"
                                           value="{{ auth()?->user()->getUserCode() }}" disabled>
                                    <label for="userCode">Код донатера</label>
                                    <button class="btn btn-outline-secondary copy-text"
                                            data-message="Код донатера"
                                            data-text="{{ auth()?->user()->getUserCode() }}" onclick="return false;">
                                        <i class="bi bi-copy"></i></button>
                                </div>
                            </div>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    @auth()
        @include('subscribe.modal')
    @endauth
@endsection
