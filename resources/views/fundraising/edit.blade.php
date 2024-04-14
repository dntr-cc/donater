@extends('layouts.base')
@section('page_title', strtr('Редагувати ":fundraising" - donater.com.ua', [':fundraising' => $fundraising->getName()]))
@section('page_description', strtr('Редагувати ":fundraising" - donater.com.ua', [':fundraising' => $fundraising->getName()]))
@section('breadcrumb-current', strtr('Редагувати ":fundraising"', [':fundraising' => $fundraising->getName()]))
@push('head-scripts')
    @vite(['resources/js/tinymce.js'])
@endpush
@php use App\Models\Fundraising; @endphp
@php /** @var Fundraising $fundraising */ @endphp
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="modal-dialog" role="document">
                <div class="modal-content rounded-4 shadow">
                    <div class="modal-header p-4 pb-2 border-bottom-0 justify-content-center">
                        <h2 class="title" id="updateFundraisingModalLabel">Редагувати збір "{{ $fundraising->getName() }}"</h2>
                    </div>
                    @include('fundraising.fund_form', ['$fundraising' => $fundraising, 'actionId' => 'editFundraising', 'btnText' => 'Оновити'])
                </div>
            </div>
        </div>
    </div>
</div>
@include('fundraising.fund_form_script', ['$fundraising' => $fundraising, 'actionId' => 'editFundraising'])
@endsection
