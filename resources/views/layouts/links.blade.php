@php /** @var App\Models\Fundraising $fundraising */ @endphp
@php /** @var bool $withZvitLink */ @endphp
@php $withZvitLink = $withZvitLink ?? false; @endphp
@php $withJarLink = $withJarLink ?? false; @endphp
@php $withPageLink = $withPageLink ?? false; @endphp
@php $additionalClasses = $additionalClasses ?? ''; @endphp
@php $raffles = $raffles ?? false; @endphp

@if($withJarLink)
    <a href="{{ $fundraising->getLink() }}" target="_blank" class="btn m-1 {{ $additionalClasses }}">
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
@if(request()->user()?->can('update', $fundraising))
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
                                        <img src="{{ url($prize->getAvatar()) }}" class="img-fluid" style="max-width: 200px" alt="">
                                    </div>
                                    <div class="col">
                                        <div class="card-block px-2">
                                            <h4 class="card-title mt-2">{{ $prize->getName() }}</h4>
                                            <p class="card-text">Створив: {!! $prize->getDonater()->getUserHref() !!}</p>
                                            <a href="{{ route('prize.show', ['prize' => $prize->getId()])}}"
                                               class="btn btn-xs m-1">
                                                <i class="bi bi-eye"></i>
                                                Подробиці
                                            </a>
                                            <button class="btn btn-xs btn-danger del-prize" data-id="{{ $prize->getId() }}">
                                                Видалити з вашого збору
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer w-100 text-muted">
                                    Умови: {{ \App\DTOs\RaffleUser::TYPES[$prize->getRaffleType() ?? ''] ?? ''}}.
                                    Переможців: {{ $prize->getRaffleWinners() }}. Ціна квитка (якщо треба): {{ $prize->getRafflePrice() }}
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
                                        <img src="{{ url($prize->getAvatar()) }}" class="img-fluid" style="max-width: 200px" alt="">
                                    </div>
                                    <div class="col">
                                        <div class="card-block px-2">
                                            <h4 class="card-title mt-2">{{ $prize->getName() }}</h4>
                                            <p class="card-text">Створив: {!! $prize->getDonater()->getUserHref() !!}</p>
                                            <a href="{{ route('prize.show', ['prize' => $prize->getId()])}}"
                                               class="btn btn-xs m-1">
                                                <i class="bi bi-eye"></i>
                                                Подробиці
                                            </a>
                                            <button class="btn btn-xs btn-success add-prize" data-id="{{ $prize->getId() }}">
                                                Додати до вашого збору
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer w-100 text-muted">
                                    Умови: {{ \App\DTOs\RaffleUser::TYPES[$prize->getRaffleType() ?? ''] ?? ''}}.
                                    Переможців: {{ $prize->getRaffleWinners() }}. Ціна квитка (якщо треба): {{ $prize->getRafflePrice() }}
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
