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
                        <form action="">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <div class="form-check m-2">
                                            <input class="form-check-input" type="radio" name="type" id="type1" checked>
                                            <label class="form-check-label" for="type2">
                                                За сумою донатів (один квиток - фіксована сума)
                                            </label>
                                        </div>
                                        <div class="form-check m-2">
                                            <input class="form-check-input" type="radio" name="type" id="type2">
                                            <label class="form-check-label" for="type1">
                                                Серед учасників, які донатили (сума не важлива)
                                            </label>
                                        </div>
                                    </div>
                                    <div id="price_input" class="col-md-4 mb-3">
                                        <div class="form-floating">
                                            <input type="number" step="0.1" min="0" class="form-control" id="price"
                                                   value="0" >
                                            <label for="price">
                                                Ціна квитка
                                            </label>
                                        </div>
                                    </div>
                                    <div id="price_input" class="col-md-4 mb-3">
                                        <div class="form-floating">
                                            <input type="number" step="1" min="1" class="form-control" id="winners"
                                                   value="1" >
                                            <label for="winners">
                                                Кількість переможців
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div id="winners_input" class="col-md-3">
                                            <button type="submit" class="btn">ПОРАХУВАТИ УЧАСНИКІВ</button>
                                        </div>                                    </div>

                                </div>
                        </form>
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
                                @foreach($volunteer->getDonateCollectionWithAmount()->getRaffleUserCollection()->all() as $it => $raffleUser)
                                    <tr>
                                        <th scope="row">{{ $it + 1 }}</th>
                                        <td><a href="{{ $raffleUser->getUser()->getUserLink() }}"
                                               class="">{{ $raffleUser->getUser()->getUsernameWithFullName() }}</a></td>
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

    <script type="module">
        function showAndHidePrice(event) {
            event.preventDefault();
            let priceInput = $('#price_input');
            if ($('#type2').is(':checked')) {
                priceInput.addClass('hide');
            } else {
                priceInput.removeClass('hide');
            }
        }

        $('#type1').change(event => {
            showAndHidePrice(event);
        });

        $('#type2').change(event => {
            showAndHidePrice(event);
        });
    </script>
@endsection
