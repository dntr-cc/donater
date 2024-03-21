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
@php $btn = true; @endphp
@section('content')
    <div class="container px-4 py-5">
        <h2 class="pb-2">Всі збори</h2>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            @foreach($fundraisings->all() as $it => $fundraising)
                @include('fundraising.item-card', compact('fundraising'))
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
