@php /** @var App\Models\Volunteer $volunteer */ @endphp

<a href="{{ $volunteer->getLink() }}" target="_blank" class="btn btn-outline-success">
    <i class="bi bi-bank"></i>
    Банка</a>
<a href="{{ $volunteer->getPage() }}" target="_blank" class="btn btn-outline-success">
    <i class="bi bi-house-door-fill"></i>
    Сторінка збору
</a>
<a href="{{route('donate')}}" class="btn btn-outline-success">
    <i class="bi bi-plus-circle-fill"></i>
    Задонатити
</a>
