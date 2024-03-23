@php /** @var \Illuminate\Support\Collection|\App\Models\User[] $users */ @endphp
@php $it = 0; @endphp
@php $authUser = auth()?->user(); @endphp
@php $withoutPagination = $withoutPagination ?? false; @endphp
@php $subscribeAllowed = $subscribeAllowed ?? false; @endphp

<div class="row row-cols-1 row-cols-xl-3 row-cols-lg-3 row-cols-md-2 row-cols-sm-2 row-cols-xs-1 g-1 d-flex justify-content-between">
    @forelse($users->all() as $user)
        <div class="col">
        <div class="card">
            <div class="card-body">
                @if($whoIs === \App\Http\Controllers\UserController::VOLUNTEERS)
                    @php $volunteer = $user; @endphp
                    @include('layouts.volunteer_item', compact('volunteer'))
                @else
                    @include('layouts.user_item', compact('user'))
                @endif
            </div>
        </div>
        </div>
    @empty
        <p>{{ $whoIs }} не знайдені</p>
    @endforelse
</div>
@if(!$withoutPagination)
    <div class="col-12">
        <div class="row">
            {{ $users->links('layouts.pagination', ['elements' => $users]) }}
        </div>
    </div>
@endif

