@php /** @var \Illuminate\Support\Collection|\App\Models\User[] $users */ @endphp
@php $it = 0; @endphp
@php $authUser = auth()?->user(); @endphp
@php $withoutPagination = $withoutPagination ?? false; @endphp
@php $subscribeAllowed = $subscribeAllowed ?? false; @endphp
@forelse($users->filter()->all() as $user)
    @if(0 === $it || 0 === $it % 3)
        <div class="col-lg-12">
            <div class="row">
                @endif
                @php ++$it @endphp
                <div class="col-md-4">
                    <div class="card mb-4">
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
                @if($it && 0 === $it % 3)
            </div>
        </div>
    @endif
@empty
    <p>Користувачі не знайдені</p>
@endforelse
@if(!$withoutPagination)
    <div class="col-12">
        <div class="row">
            {{ $users->links('layouts.pagination', ['elements' => $users]) }}
        </div>
    </div>
@endif

