@php /** @var App\Models\Volunteer $volunteer */ @endphp
@php /** @var bool $withZvitLink */ @endphp
@php $withZvitLink = $withZvitLink ?? false; @endphp
@php $withJarLink = $withJarLink ?? false; @endphp
@php $withPageLink = $withPageLink ?? false; @endphp
@php $additionalClasses = $additionalClasses ?? ''; @endphp

@if($withJarLink)
    <a href="{{ $volunteer->getLink() }}" target="_blank" class="btn btn-secondary-outline m-1 {{ $additionalClasses }}">
        <i class="bi bi-bank"></i>
        Банка</a>
@endif
@if($withPageLink)
    <a href="{{ $volunteer->getPage() }}" target="_blank" class="btn btn-secondary-outline m-1 {{ $additionalClasses }}">
        <i class="bi bi-house-door-fill"></i>
        Сторінка збору
    </a>
@endif
@if($volunteer->isEnabled())
<a href="{{route('donate', ['volunteer' => $volunteer->getKey()])}}" class="btn btn-secondary-outline m-1 {{ $additionalClasses }}">
    <i class="bi bi-plus-circle-fill"></i>
    Задонатити
</a>
@endif
@if($withZvitLink)
<a href="{{route('volunteer.show', ['volunteer' => $volunteer->getKey()])}}" class="btn btn-secondary-outline m-1 {{ $additionalClasses }}">
    <i class="bi bi-info-square-fill"></i>
    Звіт
</a>
@endif
@if(request()->user()?->can('update', $volunteer))
<a href="{{route('volunteer.edit', ['volunteer' => $volunteer->getKey()])}}" class="btn btn-secondary-outline m-1 {{ $additionalClasses }}">
    <i class="bi bi-pencil-fill"></i>
    Редагування
</a>
    @if($volunteer->isEnabled())
        <a href="{{route('volunteer.stop', ['volunteer' => $volunteer->getKey()])}}" class="btn btn-secondary-outline m-1 {{ $additionalClasses }}">
            <i class="bi bi-arrow-down-circle-fill"></i>
            Зупинити
        </a>
    @else
        <a href="{{route('volunteer.start', ['volunteer' => $volunteer->getKey()])}}" class="btn btn-secondary-outline m-1 {{ $additionalClasses }}">
            <i class="bi bi-arrow-up-circle-fill"></i>
            Розпочати
        </a>
    @endif
@endif
