@extends('layouts.base')
@section('page_title', strtr('Звітність по :fundraising - donater.com.ua', [':fundraising' => $fundraising->getName()]))
@section('page_description', strtr('Звітність по :fundraising - donater.com.ua', [':fundraising' => $fundraising->getName()]))
@push('head-scripts')
    @vite(['resources/js/tabs.js'])
@endpush
@php /* @var App\Models\Donate $donate */ @endphp
@php /* @var \App\Collections\RowCollection $rows */ @endphp
@php $withJarLink = true; @endphp
@php $withPageLink = true; @endphp
@php $raffles = true; @endphp
@php $donaters = new \Illuminate\Support\Collection(); @endphp
@php $donates = new \Illuminate\Support\Collection(); @endphp
@php $owner = $fundraising->owner()->get()->first(); @endphp
@section('content')
    <div class="container px-4 py-5">
        <h2 class="pb-2 border-bottom"><a href="{{ route('fundraising.all') }}" class=""><i class="bi bi-arrow-left"></i></a>
            @if($fundraising->isEnabled())
                <span class="btn btn-info">ЗБІР ТРИВАЄ</span>
            @elseif($fundraising->donates->count())
                <span class="btn btn-danger">ЗБІР ЗАКРИТО</span>
            @else
                <span class="btn btn-secondary">СКОРО РОЗПОЧНЕТЬСЯ</span>
            @endif
            Звітність {{ $fundraising->getName() }}. Збирає <a href="{{ $owner->getUserLink() }}">{{ $owner->getFullName() }} [{{ $owner->getAtUsername() }}]</a>
        </h2>
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
                <div class="mt-3"></div>
                @include('layouts.links', compact('fundraising', 'withJarLink', 'withPageLink', 'raffles'))
            </div>
        </div>
        <ul class="nav nav-tabs mb-3 mt-4" id="icons" role="tablist">
            <li class="nav-item" role="presentation">
                <a data-mdb-tab-init class="nav-link active" id="icons-tab-1" href="#donates-all" role="tab"
                   aria-controls="donates-tabs-all" aria-selected="true">
                    <i class="bi bi-tablet-landscape-fill"></i> Виписка з банки
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a data-mdb-tab-init class="nav-link" id="icons-tab-2" href="#donates-tabs-users" role="tab"
                   aria-controls="donates-tabs-users" aria-selected="false">
                    <i class="bi bi-lightning-fill"></i> Донатери збору
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a data-mdb-tab-init class="nav-link" id="icons-tab-3" href="#donates-waiting" role="tab"
                   aria-controls="donates-tabs-users" aria-selected="false">
                    <i class="bi bi-lightning-fill"></i> Очікують валідації
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a data-mdb-tab-init class="nav-link" id="icons-tab-4" href="#donates-analytics" role="tab"
                   aria-controls="donates-tabs-users" aria-selected="false">
                    <i class="bi bi-pie-chart-fill"></i> Аналітика
                </a>
            </li>
        </ul>
        <div class="tab-content" id="icons-content">
            <div class="tab-pane fade show active" id="donates-all" role="tabpanel" aria-labelledby="donates-all">
                <div class="table-responsive">
                    @if($rows)
                    <table class="table table-sm table-striped table-bordered">
                        <thead class="table-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Дата</th>
                            <th scope="col">Користувач</th>
                            <th scope="col">Коментар</th>
                            <th scope="col">Сума</th>
                            <th scope="col">В банці</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php $count = $rows->count(); @endphp
                        @foreach($rows->all() as $row)
                            @php $donater = $row->getDonater($row->getComment()) @endphp
                            @php $donater ? $donaters->push($donater) : '' @endphp
                            <tr>
                                <th scope="row">{{ $count-- }}</th>
                                <td>{{ $row->getDate() }}</td>
                                <td>
                                    @if($donater)
                                        <a href="{{$donater->getUserLink()}}" class="">
                                            Від: {{$donater->getFullName() }} ({{ $donater->getAtUsername() }})
                                        </a>
                                    @elseif($row->isOwnerTransaction())
                                        Від: Власник банки
                                    @else
                                        Від: 🐈‍⬛
                                    @endif
                                </td>
                                <td class="font-x-small">{{ $row->getComment() }}</td>
                                <td>{{ $row->getAmount() }}</td>
                                <td>{{ $row->getSum() }}</td>
                            </tr>
                        @endforeach
                    </table>
                    @else
                        <h6>Google Spreadsheet Api повернуло помилку. Повторіть пізніше.</h6>
                    @endif
                </div>
            </div>
            <div class="tab-pane fade" id="donates-tabs-users" role="tabpanel" aria-labelledby="donates-tabs-users">
                @include('fundraising.donaters', compact('fundraising'))
            </div>
            <div class="tab-pane fade" id="donates-waiting" role="tabpanel" aria-labelledby="donates-tabs-waiting">
                <div class="table-responsive">
                    <table class="table table-sm table-striped table-bordered">
                        <thead class="table-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Користувач</th>
                            <th scope="col">Час створення внеску</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($fundraising->getDonateCollectionWithoutValidation()->all() as $it => $donate)
                            <tr>
                                <th scope="row">{{ $it + 1 }}</th>
                                <td><a href="{{ $donate->donater()->first()->getUserLink() }}"
                                       class="">{{ $donate->donater()->first()->getUsernameWithFullName() }}</a></td>
                                <td>{{ $donate->getCreatedAt()->toString() }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
            <div class="tab-pane fade" id="donates-analytics" role="tabpanel" aria-labelledby="donates-tabs-analytics">
                @include('layouts.analytics', compact('rows', 'charts', 'charts2', 'charts3'))
            </div>
        </div>
    </div>
@endsection
