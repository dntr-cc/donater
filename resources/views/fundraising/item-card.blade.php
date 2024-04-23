@php /** @var App\Models\Fundraising $fundraising */ @endphp
@php $btn = $btn ?? false; @endphp
@php $info = true; @endphp
@php $name = $name ?? false; @endphp
@php $withVolunteer = $withVolunteer ?? false; @endphp
@php $withJarLink = true; @endphp
@php $withPageLink = true; @endphp
@php $withPrizes = $withPrizes ?? true; @endphp
@php $disableShortCodes = false; @endphp
@php $rowClasses = 'row-cols-3 g-0 d-flex justify-content-evenly align-items-center'; @endphp

<div class="col masonry-grid-item">
    <div class="card {{ $fundraising->getClassByState() }}">
        <a href="{{ route('fundraising.show', compact('fundraising')) }}">
            <img src="{{ url($fundraising->getAvatar()) }}" class="card-img-top "
                 alt="{{ $fundraising->getName() }}"></a>
        <h6 class="m-3 text-muted text-center">Дата створення {{ $fundraising->getCreatedAt() }}</h6>
        <div class="m-1 mt-3 {{ $fundraising->getClassByState() }}">
            @if($btn || $name)
                <div class="text-center m-4">
                    @if($name)
                        <h3 class="mt-3">{{ $fundraising->getName() }}</h3>
                    @endif
                    @if($btn)
                        <a class="btn btn-success text-center"
                           href="{{ route('fundraising.show', compact('fundraising')) }}">Подивитися збір</a>
                    @endif
                </div>
            @endif
            @if(app(\App\Services\RowCollectionService::class)->getRowCollection($fundraisings)->all())
                @include('layouts.monodonat', compact('fundraising', 'info'))
                <div class="d-flex justify-content-center mb-2">
                    <div class="form-floating input-group {{ $fundraising->getClassByState() }}">
                        <input type="text" class="form-control border-secondary text-truncate {{ $fundraising->getClassByState() }}" id="share-fund-{{ sha1($fundraising->getKey()) }}"
                               value="{{ $fundraising->getShortLink() }}" disabled>
                        <label for="share-fund-{{ sha1($fundraising->getKey()) }}" class="form-floating-{{ $fundraising->getClassByState() }}">
                            Коротке посилання
                        </label>
                        <button id="share-fund-btn-{{ sha1($fundraising->getKey()) }}"
                                class="btn btn-outline-dark copy-text" data-message="Посилання"
                                data-text="{{ $fundraising->getShortLink() }}" onclick="return false;">
                            <i class="bi bi-copy"></i></button>
                    </div>
                </div>
            @elseif($fundraising->donates->count())
                <h5 class="text-center">Збір закрито</h5>
                <div class="progress">
                    <div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: 100%"
                         aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            @else
                <h5 class="text-center">Збір скоро розпочнеться</h5>
                <div class="progress mb-2">
                    <div class="progress-bar progress-bar-animated progress-bar-striped bg-secondary" role="progressbar"
                         style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            @endif
            @if($cardMono = $fundraising?->getDetails()?->getCardMono())
                <div class="d-flex justify-content-center mb-2">
                    <div class="form-floating input-group {{ $fundraising->getClassByState() }}">
                        <input type="text" class="form-control border-secondary {{ $fundraising->getClassByState() }}"
                               id="share-mono-{{ sha1($cardMono) }}" value="{{ $cardMono }}" disabled>
                        <label for="share-mono-{{ sha1($cardMono) }}" class="form-floating-{{ $fundraising->getClassByState() }}">Картка моно</label>
                        <button id="share-fund-btn-{{ sha1($fundraising->getKey()) }}"
                                class="btn btn-outline-dark copy-text" data-message="Картка моно"
                                data-text="{{ $cardMono }}" onclick="return false;">
                            <i class="bi bi-copy"></i></button>
                    </div>
                </div>
            @endif
            @if($cardPrivat = $fundraising?->getDetails()?->getCardPrivat())
                <div class="d-flex justify-content-center mb-2">
                    <div class="form-floating input-group {{ $fundraising->getClassByState() }}">
                        <input type="text" class="form-control border-secondary {{ $fundraising->getClassByState() }}"
                               id="share-mono-{{ sha1($cardPrivat) }}" value="{{ $cardPrivat }}" disabled>
                        <label for="share-mono-{{ sha1($cardPrivat) }}" class="form-floating-{{ $fundraising->getClassByState() }}">Картка Приват</label>
                        <button id="share-fund-btn-{{ sha1($fundraising->getKey()) }}"
                                class="btn btn-outline-dark copy-text" data-message="Картка Приват"
                                data-text="{{ $cardPrivat }}" onclick="return false;">
                            <i class="bi bi-copy"></i></button>
                    </div>
                </div>
            @endif
            @if($paypal = $fundraising?->getDetails()?->getPayPal())
                <div class="d-flex justify-content-center mb-2">
                    <div class="form-floating input-group {{ $fundraising->getClassByState() }}">
                        <input type="text" class="form-control border-secondary {{ $fundraising->getClassByState() }}"
                               id="share-mono-{{ sha1($paypal) }}" value="{{ $paypal }}" disabled>
                        <label for="share-mono-{{ sha1($paypal) }}" class="form-floating-{{ $fundraising->getClassByState() }}">PayPal</label>
                        <button id="share-fund-btn-{{ sha1($fundraising->getKey()) }}"
                                class="btn btn-outline-dark copy-text" data-message="PayPal"
                                data-text="{{ $paypal }}" onclick="return false;">
                            <i class="bi bi-copy"></i></button>
                    </div>
                </div>
            @endif
            @include('layouts.links', compact('fundraising', 'withJarLink', 'withPageLink', 'withPrizes', 'disableShortCodes'))
        </div>
        @if($withVolunteer)
            <div class="{{ $fundraising->getClassByState() }} p-3">
                <h5 class="text-center">{{ \Illuminate\Support\Str::ucfirst(sensitive('волонтер', $fundraising->getVolunteer())) }}</h5>
                @include('layouts.user_item', [
                        'user' => $fundraising->getVolunteer(),
                        'whoIs' => \App\Http\Controllers\UserController::VOLUNTEERS,
                        'additionalClasses' => $fundraising->getClassByState(),
                ])
            </div>
        @endif
    </div>
</div>
