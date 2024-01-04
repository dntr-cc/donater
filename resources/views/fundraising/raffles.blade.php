@extends('layouts.base')
@section('page_title', 'Всі збори з розіграшами - donater.com.ua')
@section('page_description', 'Всі збори з розіграшами - donater.com.ua')
@php $withZvitLink = true; @endphp
@php $raffles = false; @endphp

@section('content')
    <div class="container px-4 py-5" >
        <h2 class="pb-2 border-bottom">
            Всі збори з розіграшами та призами
        </h2>
    </div>
    @foreach($fundraisings->all() as $it => $fundraising)
        <div class="container px-4 py-5" >
            <h3 class="pb-2 border-bottom">
                @if($fundraising->isEnabled())
                    <span class="btn btn-info">ЗБІР ТРИВАЄ</span>
                @elseif($fundraising->donates->count())
                    <span class="btn btn-danger">ЗБІР ЗАКРИТО</span>
                @else
                    <span class="btn btn-secondary">СКОРО РОЗПОЧНЕТЬСЯ</span>
                @endif
                {{ $fundraising->getName() }}
            </h3>
            <div class="d-flex">
                <div class="row">
                    <div class="col-md-4 px-2 py-2">
                        <div class="card border-0 rounded-4 shadow-lg">
                            <a href="{{ route('fundraising.show', ['fundraising' => $fundraising->getKey()]) }}" class="card">
                                <img src="{{ url($fundraising->getAvatar()) }}" class="bg-image-position-center"
                                     alt="{{ $fundraising->getName() }}">
                            </a>
                        </div>
                    </div>
                    <div class="col-md-8 px-2 py-2">
                        <div>
                            {!! $fundraising->getDescription() !!}
                        </div>
                    @if(request()->user()?->can('update', $fundraising))
                        @php $raffles = true; @endphp
                    @endif
                    @include('layouts.links', compact('fundraising', 'withZvitLink', 'raffles'))
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection
