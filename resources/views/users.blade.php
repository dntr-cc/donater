@extends('layouts.base')
@section('page_title', $whoIs . ' сайту donater.com.ua')
@section('page_description', $whoIs . ' сайту donater.com.ua')

@section('content')
    <div class="row">
        <h2>{{ $whoIs }}</h2>
        @include('layouts.users_block', compact('users'))
    </div>
@endsection
