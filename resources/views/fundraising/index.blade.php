@extends('layouts.base')
@section('page_title', 'Всі збори сайту donater.com.ua')
@section('page_description', '')
@section('breadcrumb-current', 'Призи')
@section('content')
@push('head-scripts')
    @vite(['resources/js/masonry.js'])
@endpush
@php $withVolunteer = true; @endphp
@php $withDelete = true; @endphp
    <div class="container">
        <h2 class="pb-2 border-bottom">Всі збори
            <a href="{{route('fundraisings')}}" class="btn ">
                <i class="bi bi-gift"></i>
                Всі збори
            </a>
            <a href="{{route('fundraisings.opened')}}" class="btn ">
                <i class="bi bi-gift"></i>
                Відкриті збори
            </a>
            <a href="{{route('fundraisings.deleted')}}" class="btn ">
                <i class="bi bi-gift-fill"></i>
                Видалені збори
            </a>
{{--            <a href="{{route('prizes.spent')}}" class="btn ">--}}
{{--                <i class="bi bi-gift-fill"></i>--}}
{{--                Розіграні призи--}}
{{--            </a>--}}
{{--            @auth()--}}
{{--                <a href="{{route('prize.new')}}" class="btn ">--}}
{{--                    <i class="bi bi-plus-circle-fill"></i>--}}
{{--                    Створити--}}
{{--                </a>--}}
{{--            @endauth--}}
        </h2>

        <div class="row row-cols-1 row-cols-xl-3 row-cols-lg-2 row-cols-md-2 row-cols-sm-1 g-4 masonry-grid">
            @foreach($fundraisings->all() as $fundraising)
                @include('fundraising.item-card', compact('fundraising', 'withVolunteer', 'withDelete'))
            @endforeach
        </div>
    </div>
    <div class="col-12">
        <div class="row">
            {{ $fundraisings->links('layouts.pagination', ['elements' => $fundraisings]) }}
        </div>
    </div>
@endsection
