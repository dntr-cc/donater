@extends('layouts.base')

@section('content')
    <h2>Забагато запросів, заборонено.</h2>
    @if ($seconds ?? false)
        <h4>Спробуйте через {{ $seconds }} секунд, будь ласка</h4>
    @else
        <h4>Спробуйте через пару хвилин, будь ласка</h4>
    @endif

@endsection
