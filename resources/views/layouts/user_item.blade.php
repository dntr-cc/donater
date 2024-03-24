@php use Illuminate\Support\Str; @endphp
@php /** @var App\Models\User $user */ @endphp
@php $userBanner = url(app(\App\Services\OpenGraphImageService::class)->getUserImage($user)) @endphp
@php $whoIs = $whoIs ?? '' @endphp
@php $additionalClasses = $additionalClasses ?? '' @endphp
<div class="col">
    <div class="card m-1 {{ $additionalClasses }}">
        <a href="{{ route('user', compact('user')) }}">
            <img src="{{ $userBanner }}.small.png" class="col-12 crop-banner" alt="Інфографіка {{ $user->getFullName() }}">
        </a>
        @if($whoIs === \App\Http\Controllers\UserController::VOLUNTEERS)
            <div class="d-flex justify-content-center m-2 ">
                @include('subscribe.button', ['volunteer' => $user, 'authUser' => auth()->user()])
            </div>
        @endif
    </div>
</div>
