@extends('layouts.base')
@section('page_title', 'Створити новий збір - donater.com.ua')
@section('page_description', 'Створити новий збір - donater.com.ua')
@section('breadcrumb-current', 'Створити новий збір')
@push('head-scripts')
    @vite(['resources/js/tinymce.js'])
@endpush
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="modal-dialog" role="document">
                <div class="modal-content rounded-4 shadow">
                    <div class="modal-header p-4 pb-2 border-bottom-0 justify-content-center">
                        <h2 class="title" id="createFundraisingModalLabel">Створити новий збір</h2>
                    </div>
                    @include('fundraising.fund_form')
                </div>
            </div>
        </div>
    </div>
</div>
@include('fundraising.fund_form_script', ['actionId' => 'createFundraising'])
@endsection
