@php /** @var App\Models\User $volunteer */ @endphp
@php $withOwner = $withOwner ?? false; @endphp
@php $additionalClasses = $additionalClasses ?? ''; @endphp
@php $additionalClassesColor = $additionalClassesColor ?? ''; @endphp
@php $authUser = auth()->user(); @endphp
@php $additionalClasses = 'btn-sm'; @endphp
@php $volunteerFundraisings = $volunteer->getFundraisings(); @endphp
@php $rows = app(App\Services\RowCollectionService::class)->getRowCollection($volunteerFundraisings); @endphp

    <div class="row d-flex justify-content-center">
        <div class="col-4 p-2 d-flex align-items-center">
            <img src="{{ url($volunteer->getAvatar()) }}" class="card-img-top rounded-circle img-fluid" alt="...">
        </div>
        <div class="col-4 d-flex align-items-center">
            <div class="card-body">
                <h5 class="card-title mb-4">Волонтер
                <a href="{{ $volunteer->getUserLink() }}">{{ $volunteer->getFullName() }}
                        [{{ $volunteer->getAtUsername() }}]</a></h5>
                @auth
                    @include('subscribe.button', compact('volunteer', 'authUser', 'additionalClasses'))
                @endauth
            </div>
        </div>
        <div class="card-footer w-100 text-muted {{ $additionalClassesColor }}">
            Підписалося користувачів: {{ $volunteer->getSubscribers()->count() }}<br>
            Всього зборів: {{ $volunteerFundraisings->count() }}<br>
            Загалом зібрано коштів: {{ $rows->allSum() }} грн.<br>
            Зібрано від користувачів: {{ $rows->allSumFromOurDonates() }} грн.<br>
        </div>
    </div>
