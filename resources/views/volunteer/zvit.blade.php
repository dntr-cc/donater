@extends('layouts.base')
@section('page_title', 'Звітність по Фондам та Зборам - donater.com.ua')
@section('page_description', 'Вся звітність по Фондам та актуальним зборам коштів - donater.com.ua')
@php $withZvitLink = true; @endphp
@php $raffles = false; @endphp
@section('content')
    <div class="container px-4 py-5">
        <h2 class="pb-2 border-bottom">Звітність по Фондам та Зборам</h2>
        @foreach($volunteers->all() as $it => $volunteer)
            @php $owner = $volunteer->owner()->get()->first(); @endphp
            <div class="container px-4 py-5">
                <h3 class="pb-2 border-bottom">
                    @if($volunteer->isEnabled())
                        <span class="btn btn-info">ЗБІР ТРИВАЄ</span>
                    @elseif($volunteer->donates->count())
                        <span class="btn btn-danger">ЗБІР ЗАКРИТО</span>
                    @else
                        <span class="btn btn-secondary">СКОРО РОЗПОЧНЕТЬСЯ</span>
                    @endif
                    {{ $volunteer->getName() }}. Збирає <a href="{{ $owner->getUserLink() }}">{{ $owner->getFullName() }} [{{ $owner->getAtUsername() }}]</a>
                </h3>
                <div class="row">
                    <div class="col-md-4 px-2 py-2">
                        <div class="card border-0 rounded-4 shadow-lg">
                            <a href="{{ route('volunteer.show', ['volunteer' => $volunteer->getKey()]) }}" class="card">
                                <img src="{{ url($volunteer->getAvatar()) }}" class="bg-image-position-center"
                                     alt="{{ $volunteer->getName() }}">
                            </a>
                        </div>
                    </div>
                    <div class="col-md-8 px-2 py-2">
                        <div>
                            {!! $volunteer->getDescription() !!}
                        </div>
                        @if(request()->user()?->can('update', $volunteer))
                            @php $raffles = true; @endphp
                        @endif
                        @include('layouts.links', compact('volunteer', 'withZvitLink', 'raffles'))
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
