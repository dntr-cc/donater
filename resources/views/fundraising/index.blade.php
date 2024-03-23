@extends('layouts.base')
@section('page_title', 'Всі збори волонтерів сайту donater.com.ua')
@section('page_description', 'Перелік всіх зборів, які було додано на сайт donater.com.ua. Виписки та аналітика по з')
@section('og_image', url('/images/donater.com.ua.png'))
@section('og_image_width', '1200')
@section('og_image_height', '630')
@php $raffles = true; @endphp
@php $withOwner = true; @endphp
@php $additionalClasses = 'btn-sm'; @endphp
@php $withVolunteer = true; @endphp
@php $btn = true; @endphp
@section('content')
    <div class="container px-4 py-5">
        <h2 class="pb-2">
            Всі збори
            @auth()
                <a href="{{route('fundraising.new')}}" class="btn ">
                    <i class="bi bi-plus-circle-fill"></i>
                    Створити
                </a>
            @endauth
        </h2>
        <div class="row row-cols-1 row-cols-xl-3 row-cols-lg-2 row-cols-md-2 row-cols-sm-1 g-4">
            @foreach($fundraisings->all() as $fundraising)
                @include('fundraising.item-card', compact('fundraising', 'withVolunteer'))
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

