@php /** @var App\Models\Prize $prize */ @endphp
@php $btn = $btn ?? false; @endphp
@php $withPrizeInfo = $withPrizeInfo ?? false; @endphp
@php $authUser = auth()?->user(); @endphp

<div class="col masonry-grid-item">
    <div class="card {{ $prize->getBgClassByState() }}-subtle">
        <div class="progress progress-bar-as-header">
            @if($prize->fundraising)
                <div
                    class="progress-bar progress-bar-striped {{ $prize->getBgClassByState() }} {{ $prize->getTextClassByState() }} fs-4"
                    role="progressbar"
                    style="width: 100%;" aria-valuenow="100" aria-valuemin="0"
                    aria-valuemax="100">
                    @if($prize->isEnabled())
                        @if ($prize->fundraising && $prize->isNeedApprove())
                            Очікує підтвердження на зборі
                        @elseif ($prize->fundraising)
                            Додано до збору
                        @endif
                    @else
                        Розіграно на зборі
                    @endif
                    <a href="{{ url(route('fundraising.show', ['fundraising' => $prize->fundraising->getKey()])) }}"
                       class="{{ $prize->getTextClassByState() }} text-decoration-none">{{ $prize->fundraising->getName() }}</a>
                </div>
            @else
                <div class="progress-bar progress-bar-striped bg-success fs-4 {{ $prize->getTextClassByState() }}"
                     role="progressbar" style="width: 100%;"
                     aria-valuenow="100" aria-valuemin="0"
                     aria-valuemax="100">ВІЛЬНИЙ ЛОТ
                </div>
            @endif
        </div>

        <div class="text-center m-4">
            <h4 class="mt-3">{{ $prize->getName() }}</h4>
        </div>
        <a href="{{ route('prize.show', compact('prize')) }}">
            <img src="{{ url($prize->getAvatar()) }}" class="card-img-top "
                 alt="{{ $prize->getName() }}"></a>
        <div class="m-1 mt-3 {{ $prize->getBgClassByState() }}-subtle">
            @if($authUser && $authUser->fundraisings->count())
                @php $enabledFundraisings = $authUser->fundraisings->filter(fn ($f) => $f->isEnabled()); @endphp
                @if($enabledFundraisings->count() && $prize->isEnabled() && $prize->getAvailableStatus() === \App\Models\Prize::STATUS_NEW && $authUser && $prize->availableFor($authUser))
                    <h5 class="text-center fw-bold mt-2">Додати до вашого збору</h5>
                    @foreach($enabledFundraisings->all() as $fundraising)
                        <div class="row m-3">
                            <div class="col-sm-12 d-flex justify-content-between align-items-baseline">
                                <h6 href="{{ (route('fundraising.show', ['fundraising' => $fundraising->getKey()])) }}">
                                    {{ $fundraising->getName() }}
                                </h6>
                                <button class="btn btn-xs btn-outline-success add-prize-by-fund"
                                        data-prize-id="{{ $prize->getId() }}"
                                        data-fund-key="{{ $fundraising->getKey() }}">
                                    <i class="bi bi-plus-circle-fill"></i>
                                </button>
                            </div>
                        </div>
                        <hr>
                    @endforeach
                @elseif($authUser && !$prize->availableFor($authUser) && !$prize->fundraising)
                    <h5 class="text-center">Приз недоступний для ваших зборів</h5>
                @endif
            @elseif($prize->isEnabled() && $prize->getAvailableStatus() === \App\Models\Prize::STATUS_NEW)
                <h5 class="text-center">Очікуйте поки цей приз волонтери додадуть на свій збір</h5>
            @endif
            @if($authUser && $prize->fundraising && $prize->isEnabled() && $prize->getAvailableStatus() === \App\Models\Prize::STATUS_GRANTED)
                <h5 class="text-center">Виграти цей приз за донат</h5>
                @include('layouts.monodonat', ['fundraising' => $prize->fundraising])
            @endif
        </div>
        <div class="card-footer">
            @if($withPrizeInfo)
            <p class="card-text">Створив: {!! $prize->getDonater()->getUserHref() !!}</p>
            <p class="card-text">
                <b>Умови:</b> {{ \App\DTOs\RaffleUser::TYPES[$prize->getRaffleType() ?? ''] ?? ''}}.
            </p>
            <p class="card-text">
                <b>Умови використання:</b> {{ \App\Models\Prize::AVAILABLE_TYPES[$prize->getAvailableType()] ?? '' }}
            </p>
            <p class="card-text"><b>Переможців:</b> {{ $prize->getRaffleWinners() }}.</p>
            <p class="card-text"><b>Ціна квитка (якщо треба):</b> {{ $prize->getRafflePrice() }}</p>
            @endif
            @if (auth()?->user()?->can('update', $prize))
                <div class="row row-cols-1 d-flex justify-content-between g-1 m-0">
                    <a href="{{ route('prize.edit', compact('prize')) }}" class="btn btn-xs sm-1">
                        <i class="bi bi-pencil-fill"></i>
                        Редагування
                    </a>
                </div>
                @if($prize->isNeedApprove())
                    <hr>
                    <h5>Приз додано до збору <a
                            href="{{ url(route('fundraising.show', ['fundraising' => $prize->fundraising->getKey()])) }}"
                            class="">
                            {{ $prize->fundraising->getName() }}
                        </a></h5>
                    <div class="row row-cols-1 d-flex justify-content-between g-1 m-0">
                        <a href="{{ route('prize.decline', compact('prize')) }}"
                           class="btn btn-xs btn-danger sm-1">
                            <i class="bi bi-check-circle-fill"></i>
                            Скаcувати
                        </a>
                        <a href="{{ route('prize.approve', compact('prize')) }}"
                           class="btn btn-xs btn-success sm-1">
                            <i class="bi bi-check-circle-fill"></i>
                            Підтвердити
                        </a>
                    </div>
                @endif
            @endif
            @if(!$prize->isEnabled() && $prize->winners->count())
                <hr>
                <h5 class="text-center">Переможці призу</h5>
                @foreach($prize->winners as $it => $winner)
                    <div class="m-3 lead text-danger">
                        Переможець #{{ $it + 1 }}: {!! $winner->getWinner()->getUserHref() !!}
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
