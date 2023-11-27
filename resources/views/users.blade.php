@extends('layouts.base')
@section('page_title', 'Користувачі сайту donater.com.ua')
@section('page_description', 'Всі користувачі сайту donater.com.ua')

@section('content')
    <div class="row">
        <h2>Користувачі</h2>
        @include('layouts.users_block', compact('users'))
    </div>
@endsection
