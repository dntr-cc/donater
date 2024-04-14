@extends('layouts.base')
@section('page_title', 'Часті питання по сайту donater.com.ua')
@section('page_description', 'Часті питання по сайту donater.com.ua')
@section('content')
    <div class="container">
        <h2 class="pb-2 border-bottom">
            Довідка
        </h2>
        <div class="row">
            <div class="col-md-12 px-2 py-2 bg-body-secondary">
                <div class="accordion accordion-flush" id="accordionFAQ">
                    @foreach(\App\Services\FaqService::FAQ as $prefix => $item)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faq-heading{{ $prefix }}">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#faq-collapse{{ $prefix }}" aria-expanded="false"
                                        aria-controls="faq-collapse{{ $prefix }}">
                                    {{ $item['question'] }}
                                </button>
                            </h2>
                            <div id="faq-collapse{{ $prefix }}" class="accordion-collapse collapse" aria-labelledby="faq-heading{{ $prefix }}"
                                 data-bs-parent="#accordionFAQ">
                                <div class="accordion-body">
                                    {{ $item['answer'] }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
