@php /** @var App\Models\Fundraising $fundraising */ @endphp
@php /** @var bool $withZvitLink */ @endphp
@php $withZvitLink = $withZvitLink ?? false; @endphp
@php $withJarLink = $withJarLink ?? false; @endphp
@php $withPageLink = $withPageLink ?? false; @endphp
@php $additionalClasses = $additionalClasses ?? ''; @endphp
@php $withPrizes = $withPrizes ?? false; @endphp
@php $disableShortCodes = $disableShortCodes ?? true; @endphp
@php $rows = $rows ?? false; @endphp

@if($withJarLink)
    <a href="{{ $fundraising->getJarLink() }}" target="_blank" class="btn m-1 {{ $additionalClasses }}">
        <i class="bi bi-bank"></i>
        Банка</a>
@endif
@if($withPageLink)
    <a href="{{ $fundraising->getPage() }}" target="_blank" class="btn m-1 {{ $additionalClasses }}">
        <i class="bi bi-house-door-fill"></i>
        Сторінка збору
    </a>
@endif

@if($withZvitLink)
    <a href="{{route('fundraising.show', ['fundraising' => $fundraising->getKey()])}}"
       class="btn m-1 {{ $additionalClasses }}">
        <i class="bi bi-eye"></i>
        Подробиці
    </a>
@endif
@can('update', $fundraising)
    <a id="mono" class="btn m-1 {{ $additionalClasses }}">
        <i class="bi bi-screwdriver"></i>
            Запит в моно
    </a>
    <a href="{{route('fundraising.edit', ['fundraising' => $fundraising->getKey()])}}"
       class="btn m-1 {{ $additionalClasses }}">
        <i class="bi bi-pencil-fill"></i>
        Редагування
    </a>
    @if($fundraising->isEnabled())
        <a href="{{route('fundraising.stop', ['fundraising' => $fundraising->getKey()])}}"
           class="btn m-1 {{ $additionalClasses }}">
            <i class="bi bi-arrow-down-circle-fill"></i>
            Зупинити
        </a>
    @else
        <a href="{{route('fundraising.start', ['fundraising' => $fundraising->getKey()])}}"
           class="btn m-1 {{ $additionalClasses }}">
            <i class="bi bi-arrow-up-circle-fill"></i>
            Розпочати
        </a>
    @endif
    @if($withPrizes)
        <a class="btn m-1 {{ $additionalClasses }}"
           data-bs-toggle="modal"
           data-bs-target="#fundraisingEditPrizesModal">
            <i class="bi bi-gift"></i>
            Призи
        </a>
        <div class="modal fade" id="fundraisingEditPrizesModal" tabindex="-1"
             aria-labelledby="fundraisingEditPrizesModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="fundraisingEditPrizesModalLabel">Призи для розіграшу</h1>
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
                                <div class="card m-2">
                                    <div class="row no-gutters">
                                        <div class="col-auto">
                                            <img src="{{ url($prize->getAvatar()) }}" class="img-fluid"
                                                 style="max-width: 200px" alt="">
                                        </div>
                                        <div class="col">
                                            <div class="card-block px-2">
                                                <h4 class="card-title mt-2">{{ $prize->getName() }}</h4>
                                                <p class="card-text">
                                                    Створив: {!! $prize->getDonater()->getUserHref() !!}</p>
                                                <a href="{{ route('prize.show', ['prize' => $prize->getId()])}}"
                                                   class="btn btn-xs m-1">
                                                    <i class="bi bi-eye"></i>
                                                    Подробиці
                                                </a>
                                                <button class="btn btn-xs btn-danger del-prize"
                                                        data-id="{{ $prize->getId() }}">
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
                                <div class="card m-2">
                                    <div class="row no-gutters">
                                        <div class="col-auto">
                                            <img src="{{ url($prize->getAvatar()) }}" class="img-fluid"
                                                 style="max-width: 200px" alt="">
                                        </div>
                                        <div class="col">
                                            <div class="card-block px-2">
                                                <h4 class="card-title mt-2">{{ $prize->getName() }}</h4>
                                                <p class="card-text">
                                                    Створив: {!! $prize->getDonater()->getUserHref() !!}</p>
                                                <a href="{{ route('prize.show', ['prize' => $prize->getId()])}}"
                                                   class="btn btn-xs m-1">
                                                    <i class="bi bi-eye"></i>
                                                    Подробиці
                                                </a>
                                                <span class="btn btn-xs btn-warning">
                                                    Очікує підтвердження
                                                </span>
                                                <button class="btn btn-xs btn-danger del-prize"
                                                        data-id="{{ $prize->getId() }}">
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
                                <div class="card m-2">
                                    <div class="row no-gutters">
                                        <div class="col-auto">
                                            <img src="{{ url($prize->getAvatar()) }}" class="img-fluid"
                                                 style="max-width: 200px" alt="">
                                        </div>
                                        <div class="col">
                                            <div class="card-block px-2">
                                                <h4 class="card-title mt-2">{{ $prize->getName() }}</h4>
                                                <p class="card-text">
                                                    Створив: {!! $prize->getDonater()->getUserHref() !!}</p>
                                                <a href="{{ route('prize.show', ['prize' => $prize->getId()])}}"
                                                   class="btn btn-xs m-1">
                                                    <i class="bi bi-eye"></i>
                                                    Подробиці
                                                </a>
                                                <button class="btn btn-xs btn-success add-prize"
                                                        data-id="{{ $prize->getId() }}">
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
            $('.add-prize').on('click', event => {
                event.preventDefault();
                $.ajax({
                    url: '{{ route('fundraising.show', compact('fundraising')) }}' + '/prize/' + $(event.target).attr('data-id'),
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
            $('.del-prize').on('click', event => {
                let url = '{{ route('fundraising.show', compact('fundraising')) }}' + '/prize/' + $(event.target).attr('data-id');
                console.log(url);
                console.log(event.target);
                console.log($(event.target).attr('data-id'));
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
    @if(!$disableShortCodes)
    @can('create', [\App\Models\FundraisingShortCode::class, $fundraising])
        <a class="btn m-1 {{ $additionalClasses }}"
           data-bs-toggle="modal"
           data-bs-target="#fundraisingEditShortCodesModal">
            <i class="bi bi-share"></i>
            Шорт-лінк
        </a>
        <div class="modal fade" id="fundraisingEditShortCodesModal" tabindex="-1"
             aria-labelledby="fundraisingEditShortCodesModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="fundraisingEditShortCodesModalLabel">Коротке посилання для поширення</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="form">
                        <div class="modal-body">
                            <div class="d-flex justify-content-center mb-2">
                                <div class="form-floating input-group">
                                    <input type="text" class="form-control" id="shortLink"
                                           value="{{ $fundraising->getShortLink() }}" disabled>
                                    <label for="userShortLink">Шорт-лінк</label>
                                    <button id="copyShortLink" class="btn btn-outline-secondary" onclick="return false;">
                                        <i class="bi bi-copy"></i></button>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="basic-url" class="form-label">Замінити шорт-лінк</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="newShortLinkPrefix">dntr.cc/f/</span>
                                    <input type="text" class="form-control" id="newShortLink">
                                    <button id="createShortLink" class="btn btn-outline-success" onclick="return false;">
                                        <i class="bi bi-plus-circle-fill"></i></button>
                                </div>
                                <div class="form-text" id="basic-addon4">Довжина до 5-20 символів, лише латинка та цифри</div>
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
            let copyCode = $('#copyShortLink');
            copyCode.on('click', event => {
                event.preventDefault();
                copyContent($('#shortLink').val());
                return false;
            });
            toast('Шорт-лінк скопійовано', copyCode);
            $('#createShortLink').on('click', event => {
                event.preventDefault();
                let code = $('#newShortLink').val();
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
                        $('#shortLink').val($('#newShortLinkPrefix').html() + code);
                        $('#newShortLink').val('');
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
    <script type="module">
        let monoText = `{{ $fundraising->getMonoRequest($fundraising->getJarLink(false)) }}`;
        let buttonMono = $('#mono');
        buttonMono.on('click', event => {
            event.preventDefault();
            copyContent(monoText);
            return false;
        });
        toast('Запит в підтримку скопійовано', buttonMono);
    </script>
@endcan
