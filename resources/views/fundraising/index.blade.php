@extends('layouts.base')
@section('page_title', 'Всі збори волонтерів сайту donater.com.ua')
@section('page_description', 'Перелік всіх зборів, які було додано на сайт donater.com.ua. Виписки та аналітика по з')
@section('og_image', url('/images/donater.com.ua.png'))
@section('og_image_width', '1200')
@section('og_image_height', '630')
@section('breadcrumb-current', 'Збори')
@push('head-scripts')
    @vite(['resources/js/masonry.js'])
@endpush
@php $raffles = true; @endphp
@php $withOwner = true; @endphp
@php $withPrizes = false; @endphp
@php $additionalClasses = 'btn-sm'; @endphp
@php $withVolunteer = true; @endphp
@php $btn = true; @endphp
@php $name = true; @endphp
@section('content')
    <div class="container">
        <h2 class="pb-2">
            Збори
            <a href="{{route('fundraising.all')}}" class="btn ">
                <i class="bi bi-gift"></i>
                Всі збори
            </a>
            <a href="{{route('fundraising.open')}}" class="btn ">
                <i class="bi bi-gift"></i>
                Відкриті збори
            </a>
            <a href="{{route('fundraising.wait')}}" class="btn ">
                <i class="bi bi-gift"></i>
                Збори скоро розпочнуться
            </a>
            <a href="{{route('fundraising.close')}}" class="btn ">
                <i class="bi bi-gift-fill"></i>
                Закриті збори
            </a>

            @auth()
                <a href="{{route('fundraising.new')}}" class="btn ">
                    <i class="bi bi-plus-circle-fill"></i>
                    Створити
                </a>
            @endauth
        </h2>
        <div class="row row-cols-1 row-cols-xl-3 row-cols-lg-2 row-cols-md-2 row-cols-sm-1 g-4 masonry-grid">
            @foreach($fundraisings->all() as $fundraising)
                @include('fundraising.item-card', compact('fundraising', 'withVolunteer', 'withPrizes', 'btn', 'name'))
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

