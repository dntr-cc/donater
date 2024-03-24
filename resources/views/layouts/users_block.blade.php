@php /** @var \Illuminate\Support\Collection|\App\Models\User[] $users */ @endphp
@php $askAs = 'донатер' @endphp
<div class="row row-cols-1 row-cols-xl-3 row-cols-lg-3 row-cols-md-2 row-cols-sm-2 row-cols-xs-1 g-1 d-flex justify-content-between">
    @forelse($users->all() as $user)
        @include('layouts.user_item', ['user' => $user])
    @empty
        <p>{{ $whoIs }} не знайдені</p>
    @endforelse
</div>
<div class="col-12">
    <div class="row">
        {{ $users->links('layouts.pagination', ['elements' => $users]) }}
    </div>
</div>

