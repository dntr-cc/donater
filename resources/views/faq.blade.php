@extends('layouts.base')
@section('page_title', 'Часті питання по сайту donater.com.ua')
@section('page_description', 'Часті питання по сайту donater.com.ua')

@section('content')
    <div class="container px-4 py-5">
        <h2 class="pb-2 border-bottom">
            Довідка
        </h2>
        <div class="row">
            <div class="col-md-12 px-2 py-2 bg-body-secondary">
                <div class="accordion accordion-flush" id="accordionFAQ">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq-headingOne">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-collapseOne" aria-expanded="false" aria-controls="faq-collapseOne">
                                Як відредагувати свій профіль?
                            </button>
                        </h2>
                        <div id="faq-collapseOne" class="accordion-collapse collapse" aria-labelledby="faq-headingOne" data-bs-parent="#accordionFAQ">
                            <div class="accordion-body">Увійти на свою сторінку та натиснути на своє фото. Відкриється поп-ап, де ви можете змінити фото, ім‘я, прізвище, посилання на профіль тощо</div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq-headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-collapseTwo" aria-expanded="false" aria-controls="faq-collapseTwo">
                                Як поставити питання?
                            </button>
                        </h2>
                        <div id="faq-collapseTwo" class="accordion-collapse collapse" aria-labelledby="faq-headingTwo" data-bs-parent="#accordionFAQ">
                            <div class="accordion-body">Написати в телеграм @setnemo - якщо питання поширене, то я додам в цей розділ</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
