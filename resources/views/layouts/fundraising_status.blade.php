@php /** @var App\Models\Fundraising $fundraising */ @endphp
@php $withOwner = $withOwner ?? false; @endphp
@php $additionalClasses = $additionalClasses ?? ''; @endphp
@php $owner = $fundraising->getVolunteer(); @endphp
@php $href = route('fundraising.show', ['fundraising' => $fundraising->getKey()]); @endphp
@if($fundraising->isEnabled())
    <a href="{{ $href }}" class="btn btn-success {{ $additionalClasses }}">ЗБІР ТРИВАЄ</a>
@elseif($fundraising->donates->count())
    <a href="{{ $href }}" class="btn btn-outline-dark {{ $additionalClasses }}">ЗБІР ЗАКРИТО</a>
@else
    <a href="{{ $href }}" class="btn btn-secondary {{ $additionalClasses }}">СКОРО РОЗПОЧНЕТЬСЯ</a>
@endif
{{ $fundraising->getName() }}
<a href="{{route('fundraising.show', ['fundraising' => $fundraising->getKey()])}}"
   class="btn btn-secondary-outline btn-xs">
    <i class="bi bi-eye"></i>
    Загальна інформація
</a>
<a href="{{route('fundraising.analytics', ['fundraising' => $fundraising->getKey()])}}"
   class="btn btn-secondary-outline btn-xs">
    <i class="bi bi-eye"></i>
    Аналітика
</a>
