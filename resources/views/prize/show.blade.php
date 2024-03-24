@extends('layouts.base')
@section('page_title', strtr('Приз :prizeName - donater.com.ua', [':prizeName' => $prize->getName()]))
@section('page_description', strtr('Приз :prizeName для Донатерів сайту donater.com.ua', [':prizeName' => $prize->getName()]))
@php [$ogImageWidth, $ogImageHeight] = getimagesize(config('app.env') === 'local' ? public_path('/images/banners/ava-fund-default.png') : url($fundraising->getAvatar())); @endphp
@section('og_image', url('/images/donater.com.ua.png'))
@section('og_image_width', $ogImageWidth)
@section('og_image_height', $ogImageHeight)
@section('breadcrumb-path')
    <li class="breadcrumb-item"><a href="{{ route('prizes') }}">Призи</a></li>
@endsection
@section('breadcrumb-current', $prize->getName())
@section('content')
    <div class="container">
        <h2 class="pb-2 border-bottom"><a href="{{ url()->previous() }}" class=""><i class="bi bi-arrow-left"></i></a>
            Приз "{{ $prize->getName() }}"
        </h2>
        <div class="row">
            <div class="col-md-4 px-2 py-2">
                @include('prize.item-card', compact('prize'))
            </div>
            <div class="col-md-8 px-2 py-2">
                <div class="card">
                    <div class="card-body">
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
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
