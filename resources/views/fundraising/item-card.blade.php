@php /** @var App\Models\Fundraising $fundraising */ @endphp
@php $btn = $btn ?? false; @endphp
@php $name = $name ?? false; @endphp
@php $withVolunteer = $withVolunteer ?? false; @endphp
@php $withJarLink = true; @endphp
@php $withPageLink = true; @endphp
@php $withPrizes = $withPrizes ?? true; @endphp
@php $disableShortCodes = false; @endphp
@php $rowClasses = 'row-cols-3 g-0 d-flex justify-content-evenly align-items-center'; @endphp

<div class="col grid-item">
    <div class="card {{ $fundraising->getClassByState() }}">
        <a href="{{ route('fundraising.show', compact('fundraising')) }}">
            <img src="{{ url($fundraising->getAvatar()) }}" class="card-img-top "
                 alt="{{ $fundraising->getName() }}"></a>
        <div class="m-1 mt-3 {{ $fundraising->getClassByState() }} }}">
            @if($btn || $name)
            <div class="text-center m-4">
                @if($name)
                <h3 class="mt-3">{{ $fundraising->getName() }}</h3>
                @endif
                @if($btn)
                    <a class="btn btn-success text-center" href="{{ route('fundraising.show', compact('fundraising')) }}">Детали збору</a>
                @endif
            </div>
            @endif
            @if($fundraising->isEnabled())
                @include('layouts.monodonat', compact('fundraising'))
                    <div class="d-flex justify-content-center mb-2">
                        <label for="basic-url" class="form-label"></label>
                        <div class="input-group mb-3">
                            <span class="input-group-text fw-bold {{ $fundraising->getClassByState() }}">Поширити збір: </span>
                            <input type="text" class="form-control fw-bold {{ $fundraising->getClassByState() }}" id="shortLink"
                                   value="https://{{ $fundraising->getShortLink() }}" disabled>
                            <button id="copyShortLink" class="btn btn-outline-dark"
                                    onclick="return false;">
                                <i class="bi bi-copy"></i></button>
                        </div>


                    </div>
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
            @include('layouts.links', compact('fundraising', 'withJarLink', 'withPageLink', 'withPrizes', 'disableShortCodes'))
        </div>
        @if($withVolunteer)
            <div class="card-footer {{ $fundraising->getClassByState() }} }}">
                @php $volunteer = $fundraising->getVolunteer() @endphp
                @include('layouts.volunteer_item', compact('volunteer'))
            </div>
        @endif
    </div>
</div>
