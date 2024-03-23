@extends('layouts.base')
@section('page_title', 'Призи для Донатерів сайту donater.com.ua')
@section('page_description', 'Призи розігруються лише серед Донатерів сайту, щоб заохочити донатерів стати серійними донатерами')
@section('content')
@push('head-scripts')
    @vite(['resources/js/masonry.js'])
@endpush
@php $btn = true; @endphp
@php $withPrizeInfo = true; @endphp
    <div class="container px-4 py-5">
        <h2 class="pb-2 border-bottom">Призи для Донатерів сайту
            <a href="{{route('prizes')}}" class="btn ">
                <i class="bi bi-gift"></i>
                Всі призи
            </a>
            <a href="{{route('prizes.free')}}" class="btn ">
                <i class="bi bi-gift"></i>
                Вільні призи
            </a>
            <a href="{{route('prizes.booked')}}" class="btn ">
                <i class="bi bi-gift-fill"></i>
                Зайняті призи
            </a>
            <a href="{{route('prizes.spent')}}" class="btn ">
                <i class="bi bi-gift-fill"></i>
                Розіграні призи
            </a>
            @auth()
                <a href="{{route('prize.new')}}" class="btn ">
                    <i class="bi bi-plus-circle-fill"></i>
                    Створити
                </a>
            @endauth
        </h2>

        <div class="row row-cols-1 row-cols-xl-3 row-cols-lg-2 row-cols-md-2 row-cols-sm-1 g-4 grid">
            @foreach($prizes->all() as $prize)
                @include('prize.item-card', compact('prize', 'btn', 'withPrizeInfo'))
            @endforeach
        </div>
    </div>
    <div class="col-12">
        <div class="row">
            {{ $prizes->links('layouts.pagination', ['elements' => $prizes]) }}
        </div>
    </div>
    <script type="module">
        $('.add-prize-by-fund').on('click', event => {
            event.preventDefault();
            console.log()
            let button = event.target.closest('.add-prize-by-fund');
            $.ajax({
                url: '{{ route('fundraising.all') }}' + '/' + $(button).attr('data-fund-key') + '/prize/' + $(button).attr('data-prize-id'),
                type: "POST",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: data => {
                    window.location.assign(data.url ?? '{{ route('my') }}');
                },
            });
            return false;
        });
    </script>
@endsection
