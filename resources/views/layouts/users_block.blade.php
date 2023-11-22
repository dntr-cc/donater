@php /** @var \Illuminate\Support\Collection|\App\Models\User[] $users */ @endphp

@forelse($users->all() as $it => $user)
    @if(1 === (1 + $it) % 3)
        <div class="col-lg-12">
            <div class="row">
    @endif
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
                                    <i class="bi bi-telegram" title="Telegram Premium"
                                       style="color: #d9b702;"></i>
                                @endif
                            </p>
                            <p class="text-muted mb-1">
                                @if ($user->getApprovedDonateCount())
                                    <span title="Завалідовані донати" class="badge rounded-pill bg-success">
                                    {{ $user->getApprovedDonateCount() }}
                                </span>
                                @endif
                                @if ($user->getNotValidatedDonatesCount())
                                    <span title="Очікують валідації" class="badge rounded-pill bg-secondary">
                                    {{ $user->getNotValidatedDonatesCount() }}
                                </span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
    @if(0 === (1 + $it) % 3)
            </div>
        </div>
    @endif
@empty
    <p>Користувачі не знайдені</p>
@endforelse


