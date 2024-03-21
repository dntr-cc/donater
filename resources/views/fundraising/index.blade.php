@extends('layouts.base')
@section('page_title', 'Всі збори та фонди - donater.com.ua')
@section('page_description', 'Вся звітність по Фондам та актуальним зборам коштів - donater.com.ua')
@section('og_image', url('/images/index.png'))
@section('og_image_width', '1200')
@section('og_image_height', '470')
@section('og_image_alt', 'donater.com.ua - Донатити будуть всі. Телеграм бот для нагадувань з посиланням на банку: щоденно, раз на тиждень, місяць тощо.')
@php $withJarLink = true; @endphp
@php $withZvitLink = true; @endphp
@php $raffles = true; @endphp
@php $withOwner = true; @endphp
@php $additionalClasses = 'btn-sm'; @endphp
@section('content')
    <div class="container px-4 py-5">
        <h2 class="pb-2">Всі збори</h2>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            @foreach($fundraisings->all() as $it => $fundraising)
                <div class="col">
                    <div class="card h-100 {{ $fundraising->getClassByState() }}">
                        <a href="{{ route('fundraising.show', compact('fundraising')) }}">
                        <img src="{{ url($fundraising->getAvatar()) }}" class="card-img-top"
                             alt="{{ $fundraising->getName() }}"></a>
                        <div class="m-1 mt-3 {{ $fundraising->getClassByState() }} }}">
                            <div class="text-center m-4">
                                <h3 class="mt-3">{{ $fundraising->getName() }}</h3>
                                <a class="btn btn-primary text-center" href="{{ route('fundraising.show', compact('fundraising')) }}">Подробиці</a>
                            </div>
                            @if($fundraising->isEnabled())
                                @include('layouts.monodonat', compact('fundraising'))
                            @elseif($fundraising->donates->count())
                                <h5 class="text-center">Збір закрито</h5>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: 100%"
                                         aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            @else
                                <h5 class="text-center">Збір скоро розпочнеться</h5>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-animated progress-bar-striped bg-secondary" role="progressbar"
                                         style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            @endif
                        </div>
                        <div class="card-footer {{ $fundraising->getClassByState() }} }}">
                            @include('layouts.fundraising_status_new', compact('fundraising'))
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="col-12">
        <div class="row">
            {{ $fundraisings->links('layouts.pagination', ['elements' => $fundraisings]) }}
        </div>
    </div>

    @auth()
        @include('subscribe.modal')
    @endauth
@endsection
