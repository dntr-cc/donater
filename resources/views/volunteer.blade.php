@extends('layouts.base')
@section('page_title', strtr('Звітність по :volunteer - donater.com.ua', [':volunteer' => $volunteer->getName()]))
@section('page_description', strtr('Звітність по :volunteer - donater.com.ua', [':volunteer' => $volunteer->getName()]))
@php $donaters = new \Illuminate\Support\Collection(); @endphp
@php $donates = new \Illuminate\Support\Collection(); @endphp
@section('content')
    <div class="container px-4 py-5">
        <h2 class="pb-2 border-bottom"><a href="{{ route('zvit') }}" class=""><i class="bi bi-arrow-left"></i></a>
            Звітність {{ $volunteer->getName() }}
        </h2>
        <div class="row">
            <div class="col-md-4 px-2 py-2">
                <div class="card border-0 rounded-4 shadow-lg">
                    <a href="{{ route('zvit.volunteer', ['volunteer' => $volunteer->getKey()]) }}" class="card">
                        <img src="{{ url($volunteer->getAvatar()) }}" class="bg-image-position-center"
                             alt="{{ $volunteer->getName() }}">
                    </a>
                </div>
            </div>
            <div class="col-md-8 px-2 py-2">
                <p class="lead">
                    {!! $volunteer->getDescription() !!}
                </p>
                @include('layouts.links', compact('volunteer'))
            </div>
        </div>
        <ul class="nav nav-tabs mb-3 mt-4" id="icons" role="tablist">
            <li class="nav-item" role="presentation">
                <a data-mdb-tab-init class="nav-link active" id="icons-tab-1" href="#donates-tabs-all" role="tab"
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
        </ul>
        <div class="tab-content" id="icons-content">
            <div class="tab-pane fade show active" id="donates-tabs-all" role="tabpanel" aria-labelledby="icons-tab-1">
                <div class="table-responsive">
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
                </div>
            </div>
            <div class="tab-pane fade" id="donates-tabs-users" role="tabpanel" aria-labelledby="donates-tabs-users">
                @include('layouts.users_block', ['users' => $donaters->unique(fn (\App\Models\User $user) => $user->getUsername())->filter()])
            </div>
        </div>
    </div>
@endsection
