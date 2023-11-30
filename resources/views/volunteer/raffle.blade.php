@php use App\Models\User; @endphp
@php use App\Models\Volunteer; @endphp
@php use App\Models\Donate; @endphp
@extends('layouts.base')
@section('page_title', strtr('Розіграш для донаторів :volunteer - donater.com.ua', [':volunteer' => $volunteer->getName()]))
@section('page_description', strtr('Розіграш для донаторів :volunteer - donater.com.ua', [':volunteer' => $volunteer->getName()]))
@push('head-scripts')
    @vite(['resources/js/tabs.js'])
@endpush
@php /** @var Volunteer $volunteer */@endphp
@section('content')
    <div class="container px-4 py-5">
        <h2 class="pb-2 border-bottom"><a href="{{ route('volunteer.all') }}" class=""><i class="bi bi-arrow-left"></i></a>
            Розіграш для донаторів {{ $volunteer->getName() }}
        </h2>
        <div class="row">
            <div class="col-md-12 px-2 py-2">
                <ul class="nav nav-tabs mb-3 mt-4" id="icons" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a data-mdb-tab-init class="nav-link active" id="icons-tab-1" href="#donates-tabs-all"
                           role="tab"
                           aria-controls="donates-tabs-all" aria-selected="true">
                            <i class="bi bi-tablet-landscape-fill"></i> Розіграш
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
                    <div class="tab-pane fade show active" id="donates-tabs-all" role="tabpanel"
                         aria-labelledby="icons-tab-1">

                    </div>
                    <div class="tab-pane fade" id="donates-tabs-users" role="tabpanel"
                         aria-labelledby="donates-tabs-users">
                        <div class="table-responsive">
                            <table class="table table-sm table-striped table-bordered">
                                <thead class="table-dark">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Користувач</th>
                                    <th scope="col">Кількість внесків</th>
                                    <th scope="col">Сума</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($volunteer->with('donatesWithAmount')->get()->getRaffleUserCollection()->all() as $it => $raffleUser)
                                    <tr>
                                        <th scope="row">{{ $it + 1 }}</th>
                                        <td><a href="{{ $raffleUser->getUser()->getUserLink() }}" class="">{{ $raffleUser->getUser()->getUsernameWithFullName() }}</a></td>
                                        <td>{{ $raffleUser->getDonateCollection()->count() }}</td>
                                        <td>{{ round($raffleUser->getDonateCollection()->sum(fn(Donate $donate) => $donate->getAmount()), 2) }}</td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
