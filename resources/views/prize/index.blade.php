@extends('layouts.base')
@section('page_title', 'Призи для Донатерів сайту donater.com.ua')
@section('page_description', 'Призи розігруються лише серед Донатерів сайту, щоб заохочити донатерів стати серійними донатерами')
@section('content')
    <div class="container px-4 py-5">
        <h2 class="pb-2 border-bottom">Призи для Донатерів сайту
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
        @foreach($prizes->all() as $it => $prize)
            <h3 class="pb-2 border-bottom mt-4">
                Приз "{{ $prize->getName() }}"
            </h3>
            <div class="row">
                <div class="col-md-4 px-2 py-2">
                    <div class="card border-0 rounded-4 shadow-lg">
                        <a href="{{ route('prize.show', ['prize' => $prize->getId()]) }}" class="card">
                            <img src="{{ url($prize->getAvatar()) }}" class="bg-image-position-center"
                                 alt="{{ $prize->getName() }}">
                        </a>
                    </div>
                </div>
                <div class="col-md-8 px-2 py-2">
                    <div>
                        <p class="card-text"><span class="btn
                            @if($prize->fundraising)
                                btn-secondary
                            @else
                                btn-success
                            @endif
                        ">
                            @if($prize->fundraising)
                                Збір: <a href="{{ url(route('fundraising.show', ['fundraising' => $prize->fundraising->getKey()])) }}"
                                        class="text-decoration-none text-white">
                                        {{ $prize->fundraising->getName() }}
                                    </a>
                            @else
                                ВІЛЬНИЙ ЛОТ
                            @endif
                            </span></p>
                            <div class="mt-3 lead text-danger">
                                @if(!$prize->isEnabled())
                                    @foreach($prize->getWinners() as $it => $winner)
                                        {!! strtr(App\Models\Winner::WINNER_TEMPLATE, [':number' => $it + 1, ':winner' => $winner->getWinner()->getUserHref()])  !!}
                                    @endforeach
                                @endif
                            </div>
                        <p class="card-text">Створив: {!! $prize->getDonater()->getUserHref() !!}</p>
                        <p class="card-text">
                            <b>Умови:</b> {{ \App\DTOs\RaffleUser::TYPES[$prize->getRaffleType() ?? ''] ?? ''}}.
                        </p>
                        <p class="card-text">
                            <b>Умови використання:</b> {{ \App\Models\Prize::AVAILABLE_TYPES[$prize->getAvailableType()] ?? '' }}
                        </p>
                        <p class="card-text"><b>Переможців:</b> {{ $prize->getRaffleWinners() }}.</p>
                        <p class="card-text"><b>Ціна квитка (якщо треба):</b> {{ $prize->getRafflePrice() }}</p>
                        {!! $prize->getDescription() !!}
                        @if (auth()?->user()?->can('update', $prize))
                            <a href="{{ route('prize.edit', compact('prize')) }}" class="btn m-1 ">
                                <i class="bi bi-pencil-fill"></i>
                                Редагування
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="col-12">
        <div class="row">
            {{ $prizes->links('layouts.pagination', ['elements' => $prizes]) }}
        </div>
    </div>
@endsection
