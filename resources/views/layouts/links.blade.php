@php /** @var App\Models\Fundraising $fundraising */ @endphp
@php $withAnalyticsLink = $withAnalyticsLink ?? false; @endphp
@php $withJarLink = $withJarLink ?? false; @endphp
@php $withPageLink = $withPageLink ?? false; @endphp
@php $withPrizes = $withPrizes ?? false; @endphp
@php $disableShortCodes = $disableShortCodes ?? true; @endphp
@php $rowClasses = $rowClasses ?? ''; @endphp
@php $additionalClasses = $additionalClasses ?? ''; @endphp

<div class="row m-2 {{ $rowClasses }}">
    @if($withJarLink)
        <a class="btn m-1 btn-fit-text {{ $additionalClasses }}" target="_blank"
           href="{{ $fundraising->getJarLink() }}">
            <nobr>
                <i class="bi bi-bank"></i>
                Банка
            </nobr>
        </a>
    @endif
    @if($withPageLink)
        <a class="btn m-1 btn-fit-text {{ $additionalClasses }}" target="_blank"
           href="{{ $fundraising->getPage() }}">
            <nobr>
                <i class="bi bi-house-door-fill"></i>
                Сторінка збору
            </nobr>
        </a>
    @endif
    @can('update', $fundraising)
        <a class="btn m-1 btn-fit-text {{ $additionalClasses }}" target="_blank"
            id="mono{{ sha1($fundraising->getKey()) }}">
            <nobr>
                <i class="bi bi-screwdriver"></i>
                Запит в моно
            </nobr>
        </a>
        <a class="btn m-1 btn-fit-text {{ $additionalClasses }}" target="_blank"
           href="{{route('fundraising.edit', ['fundraising' => $fundraising->getKey()])}}">
            <nobr>
                <i class="bi bi-pencil-fill"></i>
                Редагування
            </nobr>
        </a>
        @if($fundraising->isEnabled())
            <a class="btn m-1 btn-fit-text {{ $additionalClasses }}"
               href="{{route('fundraising.stop', ['fundraising' => $fundraising->getKey()])}}">
                <nobr>
                    <i class="bi bi-arrow-down-circle-fill"></i>
                    Зупинити
                </nobr>
            </a>
        @else
            <a href="{{route('fundraising.start', ['fundraising' => $fundraising->getKey()])}}"
               class="btn m-1 btn-fit-text {{ $additionalClasses }}">
                <nobr>
                    <i class="bi bi-arrow-up-circle-fill"></i>
                    Розпочати
                </nobr>
            </a>
        @endif
        @if($withPrizes)
            <a class="btn m-1 btn-fit-text {{ $additionalClasses }}" data-bs-toggle="modal"
               data-bs-target="#fundraisingEditPrizesModal{{ sha1($fundraising->getKey()) }}">
                <nobr>
                    <i class="bi bi-gift"></i>
                    Призи
                </nobr>
            </a>
        @endif
        @if(!$disableShortCodes)
            @can('create', [\App\Models\FundraisingShortCode::class, $fundraising])
                <a class="btn m-1 btn-fit-text {{ $additionalClasses }}"
                   data-bs-toggle="modal"
                   data-bs-target="#fundraisingEditShortCodesModal{{ sha1($fundraising->getKey()) }}">
                    <nobr>
                        <i class="bi bi-share"></i>
                        Коротке посилання
                    </nobr>
                </a>
            @endcan
        @endif
    @endcan
</div>

{{--Can update additional data: js--}}
@can('update', $fundraising)
    <script type="module">
        let monoText{{ sha1($fundraising->getKey()) }} = `{{ $fundraising->getMonoRequest($fundraising->getJarLink(false)) }}`;
        let buttonMono{{ sha1($fundraising->getKey()) }} = $('#mono{{ sha1($fundraising->getKey()) }}');
        buttonMono{{ sha1($fundraising->getKey()) }}.on('click', event => {
            event.preventDefault();
            copyContent(monoText{{ sha1($fundraising->getKey()) }});
            return false;
        });
        toast('Запит в підтримку скопійовано', buttonMono{{ sha1($fundraising->getKey()) }});
    </script>
@endcan

{{--Prizes additional data: modal, js--}}
@can('update', $fundraising)
    @if($withPrizes)
        <div class="modal fade" id="fundraisingEditPrizesModal{{ sha1($fundraising->getKey()) }}" tabindex="-1"
             aria-labelledby="fundraisingEditPrizesModalLabel{{ sha1($fundraising->getKey()) }}"
             aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="fundraisingEditPrizesModalLabel{{ sha1($fundraising->getKey()) }}">Призи для розіграшу</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="form">
                        <div class="modal-body">
                            @php
                                $prizes = $fundraising->getPrizes();
                            @endphp
                            <p class="lead">
                                Додані розіграші: {{ $prizes->count() }}
                            </p>
                            @foreach($prizes as $prize)
                                <div class="card m-2 with-id" data-id="{{ $prize->getId() }}">
                                    <div class="row no-gutters">
                                        <div class="col-auto">
                                            <img src="{{ url($prize->getAvatar()) }}" class="img-fluid"
                                                 style="max-width: 200px" alt="">
                                        </div>
                                        <div class="col">
                                            <div class="card-block px-2">
                                                <h4 class="card-title mt-2">{{ $prize->getName() }}</h4>
                                                <p class="card-text">
                                                    {{ \Illuminate\Support\Str::ucfirst(sensitive('створив', $prize->getDonater())) }}: {!! $prize->getDonater()->getUserHref() !!}</p>
                                                <a href="{{ route('prize.show', ['prize' => $prize->getId()])}}"
                                                   class="btn btn-xs m-1">
                                                    <i class="bi bi-eye"></i>
                                                    Подробиці
                                                </a>
                                                <button class="btn btn-xs btn-danger del-prize-{{ sha1($fundraising->getKey()) }}">
                                                    Видалити з вашого збору
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer w-100 text-muted">
                                        Умови: {{ \App\DTOs\RaffleUser::TYPES[$prize->getRaffleType() ?? ''] ?? ''}}.
                                        Переможців: {{ $prize->getRaffleWinners() }}. Ціна квитка (якщо
                                        треба): {{ $prize->getRafflePrice() }}
                                    </div>
                                </div>
                            @endforeach
                            @php
                                $bookedPrizes = $fundraising->getBookedPrizes();
                            @endphp
                            <p class="lead">
                                Очікують підтвердження: {{ $bookedPrizes->count() }}
                            </p>
                            @foreach($bookedPrizes as $prize)
                                <div class="card m-2 with-id" data-id="{{ $prize->getId() }}">
                                    <div class="row no-gutters">
                                        <div class="col-auto">
                                            <img src="{{ url($prize->getAvatar()) }}" class="img-fluid"
                                                 style="max-width: 200px" alt="">
                                        </div>
                                        <div class="col">
                                            <div class="card-block px-2">
                                                <h4 class="card-title mt-2">{{ $prize->getName() }}</h4>
                                                <p class="card-text">
                                                    {{ \Illuminate\Support\Str::ucfirst(sensitive('створив', $prize->getDonater())) }}: {!! $prize->getDonater()->getUserHref() !!}</p>
                                                <a href="{{ route('prize.show', ['prize' => $prize->getId()])}}"
                                                   class="btn btn-xs m-1">
                                                    <i class="bi bi-eye"></i>
                                                    Подробиці
                                                </a>
                                                <span class="btn btn-xs btn-warning">
                                                    Очікує підтвердження
                                                </span>
                                                <button class="btn btn-xs btn-danger del-prize-{{ sha1($fundraising->getKey()) }}">
                                                    Видалити з вашого збору
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer w-100 text-muted">
                                        Умови: {{ \App\DTOs\RaffleUser::TYPES[$prize->getRaffleType() ?? ''] ?? ''}}.
                                        Переможців: {{ $prize->getRaffleWinners() }}. Ціна квитка (якщо
                                        треба): {{ $prize->getRafflePrice() }}
                                    </div>
                                </div>
                            @endforeach
                            @php
                                $availablePrizes = $fundraising->getAvailablePrizes();
                            @endphp
                            <p class="lead">
                                Доступно для розіграшу: {{ $availablePrizes->count() }}
                            </p>
                            @foreach($availablePrizes as $prize)
                                <div class="card m-2 with-id" data-id="{{ $prize->getId() }}">
                                    <div class="row no-gutters">
                                        <div class="col-auto">
                                            <img src="{{ url($prize->getAvatar()) }}" class="img-fluid"
                                                 style="max-width: 200px" alt="">
                                        </div>
                                        <div class="col">
                                            <div class="card-block px-2">
                                                <h4 class="card-title mt-2">{{ $prize->getName() }}</h4>
                                                <p class="card-text">
                                                    {{ \Illuminate\Support\Str::ucfirst(sensitive('створив', $prize->getDonater())) }}: {!! $prize->getDonater()->getUserHref() !!}</p>
                                                <a href="{{ route('prize.show', ['prize' => $prize->getId()])}}"
                                                   class="btn btn-xs m-1">
                                                    <i class="bi bi-eye"></i>
                                                    Подробиці
                                                </a>
                                                <button class="btn btn-xs btn-success add-prize-{{ sha1($fundraising->getKey()) }}">
                                                    Додати до вашого збору
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer w-100 text-muted">
                                        Умови: {{ \App\DTOs\RaffleUser::TYPES[$prize->getRaffleType() ?? ''] ?? ''}}.
                                        Переможців: {{ $prize->getRaffleWinners() }}. Ціна квитка (якщо
                                        треба): {{ $prize->getRafflePrice() }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-secondary justify-content-evenly"
                                    data-bs-dismiss="modal">
                                Закрити
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script type="module">
            $('.add-prize-{{ sha1($fundraising->getKey()) }}').on('click', event => {
                let item = $(event.target.closest('div.with-id'));
                event.preventDefault();
                $.ajax({
                    url: '{{ route('fundraising.show', compact('fundraising')) }}' + '/prize/' + item.attr('data-id'),
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
            $('.del-prize-{{ sha1($fundraising->getKey()) }}').on('click', event => {
                let item = $(event.target.closest('div.with-id'));
                let url = '{{ route('fundraising.show', compact('fundraising')) }}' + '/prize/' + item.attr('data-id');
                event.preventDefault();
                $.ajax({
                    url: url,
                    type: "DELETE",
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
    @endif
@endcan

{{--Shortcodes additional data: modal, js--}}
@if(!$disableShortCodes)
    @can('create', [\App\Models\FundraisingShortCode::class, $fundraising])
        <div class="modal fade" id="fundraisingEditShortCodesModal{{ sha1($fundraising->getKey()) }}" tabindex="-1"
             aria-labelledby="fundraisingEditShortCodesModalLabel{{ sha1($fundraising->getKey()) }}"
             aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="fundraisingEditShortCodesModalLabel{{ sha1($fundraising->getKey()) }}">Коротке посилання для
                            поширення</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="form">
                        <div class="modal-body">
                            <div class="d-flex justify-content-center mb-2">
                                <div class="form-floating input-group">
                                    <input type="text" class="form-control" id="shortLink{{ sha1($fundraising->getKey()) }}"
                                           value="{{ $fundraising->getShortLink() }}" disabled>
                                    <label for="userShortLink">Коротке посилання</label>
                                    <button class="btn btn-outline-secondary copy-text"
                                            data-message="Коротке посилання" data-text="{{ $fundraising->getShortLink() }}" onclick="return false;">
                                        <i class="bi bi-copy"></i></button>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="basic-url" class="form-label">Замінити шорт-лінк</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="newShortLinkPrefix{{ sha1($fundraising->getKey()) }}">dntr.cc/f/</span>
                                    <input type="text" class="form-control" id="newShortLink{{ sha1($fundraising->getKey()) }}">
                                    <button id="createShortLink{{ sha1($fundraising->getKey()) }}" class="btn btn-outline-success"
                                            onclick="return false;">
                                        <i class="bi bi-plus-circle-fill"></i></button>
                                </div>
                                <div class="form-text" id="basic-addon4">Довжина до 5-20 символів, лише латинка та
                                    цифри
                                </div>
                            </div>
                            <div class="d-flex justify-content-center mb-2">
                                <div class="form-floating input-group">
                                    <span class="input-group-addon"></span>
                                    <label for="newShortLink"></label>

                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-secondary justify-content-evenly"
                                    data-bs-dismiss="modal">
                                Закрити
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script type="module">
            $('#createShortLink{{ sha1($fundraising->getKey()) }}').on('click', event => {
                event.preventDefault();
                let code = $('#newShortLink{{ sha1($fundraising->getKey()) }}').val();
                $.ajax({
                    url: '{{ route('fundraising.link.create', compact('fundraising')) }}',
                    type: "POST",
                    data: {
                        code: code,
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: (data) => {
                        $('#shortLink{{ sha1($fundraising->getKey()) }}').val($('#newShortLinkPrefix{{ sha1($fundraising->getKey()) }}').html() + code);
                        $('#newShortLink{{ sha1($fundraising->getKey()) }}').val('');
                        $('#share-fund-{{ sha1($fundraising->getKey()) }}').text('Поширити збір: dntr.cc/f/' + code);
                        $('#share-fund-btn-{{ sha1($fundraising->getKey()) }}').attr('data-text', 'dntr.cc/f/' + code);
                        $('meta[name="csrf-token"]').attr('content', data.csrf);
                    },
                    error: data => {
                        let empty = $("<a>");
                        toast(JSON.parse(data.responseText).message, empty, 'text-bg-danger');
                        empty.click();
                        $('meta[name="csrf-token"]').attr('content', data.csrf);
                    },
                });
                return false;
            });
        </script>
    @endcan
@endif
