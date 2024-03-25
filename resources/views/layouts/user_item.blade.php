@php use Illuminate\Support\Str; @endphp
@php /** @var App\Models\User $user */ @endphp
@php $userBanner = url(app(\App\Services\OpenGraphImageService::class)->getUserImage($user)) @endphp
@php $whoIs = $whoIs ?? '' @endphp
@php $additionalClasses = $additionalClasses ?? '' @endphp
@php $mansory = $mansory ?? '' @endphp
@php $statService = app(\App\Services\UserStatService::class) @endphp
<div class="col {{ $mansory }}">
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
                                <div class="">{{ \App\DTOs\VolunteerInfo::TOTAL_FUNDS_RAISED }}</div>
                                <div class="">{{ $volunteerInfo->getAmountSum() }} грн.</div>
                            </div>
                            <div class="col-12 small mt-2 d-flex d-flex justify-content-between">
                                <div class="">{{ \App\DTOs\VolunteerInfo::TOTAL_DONATIONS_RECEIVED }}</div>
                                <div class="">{{ $volunteerInfo->getAmountDonates() }} грн.</div>
                            </div>
                        @else
                            @php $donaterInfo = $statService->getDonaterInfo($user) @endphp
                            <div class="col-12 small mt-2 d-flex d-flex justify-content-between">
                                <div class="">{{ \App\DTOs\DonaterInfo::VOLUNTEER_SUBSCRIPTIONS }}</div>
                                <div class="">{{ $donaterInfo->getSubscribes() }} ос.</div>
                            </div>
                            <div class="col-12 d-flex d-flex justify-content-between">
                                <div class="">{{ \App\DTOs\DonaterInfo::DONATION_COUNT }}</div>
                                <div class="">{{ $donaterInfo->getDonationCount() }} шт.</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
