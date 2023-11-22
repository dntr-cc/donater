@extends('layouts.base')
@section('page_title', 'Користувачі сайту donater.com.ua')
@section('page_description', 'Всі користувачі сайту donater.com.ua')

@section('content')
    <div class="row">
        <h2>Користувачі <span class="badge rounded-pill text-bg-info">{{ $users->count() }}</span></h2>
        @include('layouts.users_block', compact('users'))
    </div>
@endsection
