@php /** @var App\Models\Fundraising $fundraising */ @endphp
@php $withOwner = $withOwner ?? false; @endphp
@php $additionalClasses = $additionalClasses ?? ''; @endphp
@php $owner = $fundraising->getVolunteer(); @endphp
@php $href = route('fundraising.show', ['fundraising' => $fundraising->getKey()]); @endphp
@php
    $progressStyle = 'bg-secondary';
    $textStyle = 'text-white fw-bold';
    $text = 'Збір скоро розпочнеться';
    if($fundraising->isEnabled()) {
        $progressStyle = 'bg-success';
        $text = 'Збір Триває';
    } elseif($fundraising->donates->count()) {
        $progressStyle = 'bg-danger';
        $text = 'Збір закрито';
    }
@endphp
<div class="progress progress-bar-as-header">
    <div class="progress-bar progress-bar-striped {{ $progressStyle }} {{ $textStyle }} fs-4"
        role="progressbar"
        style="width: 100%;" aria-valuenow="100" aria-valuemin="0"
        aria-valuemax="100">
        <div class="fw-4 m-2">{{ $text }}</div>
    </div>
</div>

<div class="d-flex justify-content-center m-2">
    <div class="btn-group shadow-0 btn-no-radius" role="group" aria-label="Basic example">
        <a href="{{ route('fundraising.show', ['fundraising' => $fundraising->getKey()]) }}"
           class="btn btn-xs btn-outline-secondary active" data-mdb-color="dark" data-mdb-ripple-init>Подробиці</a>
        <a href="{{ route('fundraising.show', ['fundraising' => $fundraising->getKey()]) }}"
           class="btn btn-xs btn-outline-secondary" data-mdb-color="dark" data-mdb-ripple-init>Донати</a>
        <a href="{{ route('fundraising.analytics', ['fundraising' => $fundraising->getKey()]) }}"
           class="btn btn-xs btn-outline-secondary" data-mdb-color="dark" data-mdb-ripple-init>Аналітика</a>
        <a href="{{ route('fundraising.show', ['fundraising' => $fundraising->getKey()]) }}"
           class="btn btn-xs btn-outline-secondary" data-mdb-color="dark" data-mdb-ripple-init>Реквізити</a>
    </div>
</div>
<div class="d-flex justify-content-center m-2">
    {{ $fundraising->getName() }}
</div>



