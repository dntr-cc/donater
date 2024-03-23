@php /** @var App\Models\User $volunteer */ @endphp
@php $withOwner = $withOwner ?? false; @endphp
@php $additionalClasses = $additionalClasses ?? ''; @endphp
@php $additionalClassesColor = $additionalClassesColor ?? ''; @endphp
@php $authUser = auth()->user(); @endphp
@php $additionalClasses = 'btn-sm'; @endphp
@php $volunteerFundraisings = $volunteer->getFundraisings(); @endphp
@php $rows = app(App\Services\RowCollectionService::class)->getRowCollection($volunteerFundraisings); @endphp

<div class="row row-cols-2 d-flex justify-content-between align-items-center g-1 m-0">
    <div>
        <img src="{{ url($volunteer->getAvatar()) }}" width="150px"
             class="object-fit-cover-150 card-img-top rounded-circle img-fluid" alt="{{ $volunteer->getFullName() }}">
    </div>
    <div class="card-body">
        <h6 class="card-title mb-4"><strong>{{ ucfirst(sensitive('волонтер', $user)) }}</strong>
            <a href="{{ $volunteer->getUserLink() }}">{{ $volunteer->getFullName() }}</a>
            <a href="{{ $volunteer->getUserLink() }}">[{{ $volunteer->getAtUsername() }}]</a>
        </h6>
        @include('subscribe.button', compact('volunteer', 'authUser', 'additionalClasses'))
    </div>
    <div class="card-footer w-100 text-muted {{ $additionalClassesColor }}">
        Підписалося Донатерів: {{ $volunteer->getSubscribers()->count() }}<br>
        Всього зборів: {{ $volunteerFundraisings->count() }}<br>
        Загалом зібрано коштів: {{ $rows->allSum() }} грн.<br>
        Зібрано від Донатерів: {{ $rows->allSumFromOurDonates() }} грн.<br>
    </div>
</div>
