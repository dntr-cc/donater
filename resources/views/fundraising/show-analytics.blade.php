@extends('layouts.base')
@php [$ogImageWidth, $ogImageHeight] = getimagesize(config('app.env') === 'local' ? public_path('/images/banners/default.png') : url($fundraising->getAvatar())); @endphp
@php $additionalAnalyticsText = ' по збору ' . $fundraising->getName(); @endphp
@section('page_title', strtr(':fundraising: aналітика руху коштів- donater.com.ua', [':fundraising' => $fundraising->getName()]))
@section('page_description', strtr('Аналітика по збору :fundraising з рухом коштів та відсотки донатів по сумі - donater.com.ua', [':fundraising' => $fundraising->getName()]))
@section('og_image', url($fundraising->getAvatar()))
@section('og_image_width', $ogImageWidth)
@section('og_image_height', $ogImageHeight)
@php $withJarLink = true; @endphp
@php $withPageLink = true; @endphp
@php $withOwner = true; @endphp
@php $withPrizes = true; @endphp
@php $disableShortCodes = false; @endphp
@php $additionalClasses = 'btn-xs'; @endphp
@php $btn = false; @endphp
@section('content')
    <div class="container px-4 py-5">
        <h2 class="pb-2 border-bottom"><a href="{{ url()->previous() }}" class=""><i class="bi bi-arrow-left"></i></a>
            @include('layouts.fundraising_status', compact('fundraising', 'withOwner', 'additionalClasses'))
        </h2>
        <div class="row">
            <div class="col-md-4 px-2 py-2">
                @include('fundraising.item-card', compact('fundraising', 'btn'))
            </div>
            <div class="col-md-8 px-2 py-2">
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="m-3">
                            <a href="{{route('fundraising.show', ['fundraising' => $fundraising->getKey()])}}"
                               class="btn btn-secondary-outline m-1 ">
                                <i class="bi bi-eye"></i>
                                Загальна інформація
                            </a>
                            <a href="{{route('fundraising.analytics', ['fundraising' => $fundraising->getKey()])}}"
                               class="btn btn-secondary-outline m-1 ">
                                <i class="bi bi-eye"></i>
                                Аналітика
                            </a>
                        </div>
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
                                    <button id="copyCode" class="btn btn-outline-secondary" onclick="return false;">
                                        <i class="bi bi-copy"></i></button>
                                </div>
                            </div>
                        @endguest
                        @include('layouts.links', compact('fundraising', 'withJarLink', 'withPageLink', 'withPrizes', 'disableShortCodes'))
                    </div>
                </div>
                <div class="card mb-2">
                    <div class="card-body py-2 px-2">
                        @include('layouts.analytics', compact('rows', 'charts', 'charts2', 'charts3', 'additionalAnalyticsText'))
                    </div>
                </div>
            </div>
        </div>
    </div>
    @auth()
        @include('subscribe.modal')
    @endauth
@endsection
