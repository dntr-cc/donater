@php /** @var App\Models\Volunteer $volunteer */ @endphp
@php /** @var bool $withZvitLink */ @endphp
@php $withZvitLink = $withZvitLink ?? false; @endphp
@php $additionalClasses = $additionalClasses ?? ''; @endphp

<a href="{{ $volunteer->getLink() }}" target="_blank" class="btn btn-outline-success m-1 {{ $additionalClasses }}">
    <i class="bi bi-bank"></i>
    Банка</a>
<a href="{{ $volunteer->getPage() }}" target="_blank" class="btn btn-outline-success m-1 {{ $additionalClasses }}">
    <i class="bi bi-house-door-fill"></i>
    Сторінка збору
</a>
<a href="{{route('donate', ['volunteer' => $volunteer->getKey()])}}" class="btn btn-outline-success m-1 {{ $additionalClasses }}">
    <i class="bi bi-plus-circle-fill"></i>
    Задонатити
</a>
@if($withZvitLink)
    <a href="{{route('volunteer.show', ['volunteer' => $volunteer->getKey()])}}" class="btn btn-outline-success m-1 {{ $additionalClasses }}">
        <i class="bi bi-info-square-fill"></i>
        Звіт
    </a>
@endif
