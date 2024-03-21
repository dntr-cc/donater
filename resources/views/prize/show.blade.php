@extends('layouts.base')
@section('page_title', strtr('Приз :prizeName - donater.com.ua', [':prizeName' => $prize->getName()]))
@section('page_description', strtr('Приз :prizeName для користувачів сайту donater.com.ua', [':prizeName' => $prize->getName()]))
@php [$ogImageWidth, $ogImageHeight] = getimagesize(config('app.env') === 'local' ? public_path('/images/banners/default.png') : url($fundraising->getAvatar())); @endphp
@section('og_image', url('/images/donater.com.ua.png'))
@section('og_image_width', $ogImageWidth)
@section('og_image_height', $ogImageHeight)
@section('content')
    <div class="container px-4 py-5">
        <h2 class="pb-2 border-bottom"><a href="{{ url()->previous() }}" class=""><i class="bi bi-arrow-left"></i></a>
            Приз "{{ $prize->getName() }}"
        </h2>
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
    </div>
@endsection
