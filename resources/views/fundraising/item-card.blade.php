@php /** @var App\Models\Fundraising $fundraising */ @endphp
@php $btn = $btn ?? false; @endphp
<div class="col">
    <div class="card h-100 {{ $fundraising->getClassByState() }}">
        <a href="{{ route('fundraising.show', compact('fundraising')) }}">
            <img src="{{ url($fundraising->getAvatar()) }}" class="card-img-top"
                 alt="{{ $fundraising->getName() }}"></a>
        <div class="m-1 mt-3 {{ $fundraising->getClassByState() }} }}">
            @if($btn)
            <div class="text-center m-4">
                <h3 class="mt-3">{{ $fundraising->getName() }}</h3>
                <a class="btn btn-primary text-center" href="{{ route('fundraising.show', compact('fundraising')) }}">Подробиці</a>
            </div>
            @endif
            @if($fundraising->isEnabled())
                @include('layouts.monodonat', compact('fundraising'))
            @elseif($fundraising->donates->count())
                <h5 class="text-center">Збір закрито</h5>
                <div class="progress">
                    <div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: 100%"
                         aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            @else
                <h5 class="text-center">Збір скоро розпочнеться</h5>
                <div class="progress">
                    <div class="progress-bar progress-bar-animated progress-bar-striped bg-secondary" role="progressbar"
                         style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            @endif
        </div>
        <div class="card-footer {{ $fundraising->getClassByState() }} }}">
            @php $volunteer = $fundraising->getVolunteer() @endphp
            @include('layouts.volunteer_item', compact('volunteer'))
        </div>
    </div>
</div>
