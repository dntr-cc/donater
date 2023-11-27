@php /** @var \Illuminate\Support\Collection|\App\Models\User[] $users */ @endphp
@php $it = 0; @endphp
@forelse($users->filter()->all() as $user)
    @if(0 === $it || 0 === $it % 3)
        <div class="col-lg-12">
            <div class="row">
    @endif
                @php ++$it @endphp
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-body text-center">
                            <a href="{{ $user->getUserLink() }}" class="">
                                <img src="{{ $user->getAvatar() }}"
                                     alt="avatar"
                                     class="rounded-circle img-fluid" style="width: 150px;">
                            </a>
                            <h5 class="my-3">{{ $user->getFullName() }}</h5>
                            <p class="text-muted mb-1">
                                {{ $user->getAtUsername() }}
                                @if ($user->isPremium())
                                    <span title="Створені збори" class="badge bg-golden p-1">
                                    <i class="bi bi-telegram" title="Telegram Premium"
                                       style="color: #fff;"></i>
                                    </span>
                                @endif
                                @if ($user->volunteers->count())
                                    <span title="Створені збори" class="badge p-1 bg-info">
                                    &nbsp;{{ $user->volunteers->count() }}&nbsp;
                                </span>
                                @endif
                                @if ($user->getApprovedDonateCount())
                                    <span title="Завалідовані донати" class="badge p-1 bg-success">
                                    &nbsp;{{ $user->getApprovedDonateCount() }}&nbsp;
                                </span>
                                @endif
                                @if ($user->getNotValidatedDonatesCount())
                                    <span title="Очікують валідації" class="badge p-1 bg-secondary">
                                    &nbsp;{{ $user->getNotValidatedDonatesCount() }}&nbsp;
                                </span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
    @if($it && 0 === $it % 3)
            </div>
        </div>
    @endif
@empty
    <p>Користувачі не знайдені</p>
@endforelse
<div class="col-lg-12">
    <div class="row">
    {{ $users->links('layouts.pagination', ['elements' => $users]) }}
    </div>
</div>

