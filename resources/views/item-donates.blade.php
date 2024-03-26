@php /** @var App\Models\User $user */ @endphp
@php use App\Models\Donate; @endphp
@php $masonry = $masonry ?? '' @endphp
@php $statService = app(\App\Services\UserStatService::class) @endphp
<div class="col {{ $masonry }}">
    <div class="card">
        <div class="card {{ $additionalClasses }}">
            <div class="row g-0">
                <div class="col-4">
                    <a href="{{ route('user', compact('user')) }}">
                        <img src="{{ $user->getAvatar() }}" class="img-fluid rounded-start card-img-cover"
                             alt="{{ $user->getFullName() }} - {{ $user->getUsername() }}">
                    </a>
                </div>
                <div class="col-8">
                    <div class="card-body">
                        <h5 class="m-0 card-title">{{ $user->getFullName() }}</h5>
                        <p class="card-text"><small class="text-body-secondary">{{ '@' . $user->getUsername() }}</small>
                        </p>
                        <div class="col-12 small mt-2 d-flex d-flex justify-content-between">
                            <div class="">Задоначено</div>
                            <div class="">{{ $item->amount ?? 0 }} ₴</div>
                        </div>
                            <div class="col-12 small d-flex d-flex justify-content-between">
                                <div class="">Кількість донатів</div>
                                <div class="">{{ $item->count ?? 0 }}</div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
