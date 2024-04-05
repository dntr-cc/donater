@php use Illuminate\Support\Str; @endphp
@php /** @var App\Models\User $user */ @endphp
@php $userBanner = url(app(\App\Services\OpenGraphImageService::class)->getUserImage($user)) @endphp
@php $whoIs = $whoIs ?? '' @endphp
@php $additionalClasses = $additionalClasses ?? '' @endphp
@php $masonry = $masonry ?? '' @endphp
@php $trust = $trust ?? null @endphp
@php $trustStyle = $trustStyle ?? null @endphp
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
                        @if($whoIs === \App\Http\Controllers\UserController::VOLUNTEERS)
                            @include('subscribe.button', ['volunteer' => $user, 'authUser' => auth()->user(), 'additionalClasses' => 'btn-xs'])
                            @php $volunteerInfo = $statService->getVolunteerInfo($user) @endphp
                            <div class="col-12 small mt-2 d-flex d-flex justify-content-between">
                                <div class="">{{ \App\DTOs\VolunteerInfo::TOTAL_DONATIONS_COUNT }}</div>
                                <div class="">{{ $volunteerInfo->getDonationsCountAll() }}</div>
                            </div>
                            <div class="col-12 small mt-2 d-flex d-flex justify-content-between">
                                <div class="">{{ \App\DTOs\VolunteerInfo::DONATIONS_COUNT }}</div>
                                <div class="">{{ $volunteerInfo->getDonationsCount() }}</div>
                            </div>
                        @else
                            @php $donaterInfo = $statService->getDonaterInfo($user) @endphp
                            <div class="col-12 small mt-2 d-flex d-flex justify-content-between">
                                <div class="">{{ \App\DTOs\DonaterInfo::VOLUNTEER_SUBSCRIPTIONS }}</div>
                                <div class="">{{ $donaterInfo->getSubscribes() }}</div>
                            </div>
                            <div class="col-12 small d-flex d-flex justify-content-between">
                                <div class="">{{ \App\DTOs\DonaterInfo::DONATION_COUNT }}</div>
                                <div class="">{{ $donaterInfo->getDonationCount() }}</div>
                            </div>
                        @endif
                        @if(!is_null($trust))
                            <div class="col-12 small d-flex d-flex justify-content-center">
                                <div class="">Рівень достовірності підписки</div>
                            </div>

                            <div class="progress">
                                <div class="progress-bar progress-bar-animated progress-bar-striped {{ $trustStyle }}" role="progressbar"
                                     style="width: {{ $trust < 11 ? 6 : $trust }}%" aria-valuenow="{{ $trust }}" aria-valuemin="0" aria-valuemax="100">
                                    <div class="text-black overflow-visible">{{ $trust }}%</div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
