@php /** @var App\Models\Fundraising $fundraising */ @endphp
@php $info = $info ?? false @endphp

@if ($info)
    <h4 class="text-center">Збір триває, зробити донат</h4>
    <div class="progress">
        <div class="progress-bar progress-bar-animated-reverse active-right progress-bar-striped" role="progressbar"
             style="width: 100%;" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
@endif

<div class="row row-cols-xl-4 row-cols-lg-4 row-cols-md-1 row-cols-sm-1 row-cols-sm-1 d-flex justify-content-around m-4">
    <a class="btn btn-dark btn-xs m-1 btn-fit-text " target="_blank" href="{{ $fundraising->getJarLink(true, 33, '🇺🇦 нехай буде 33 🍩 щодня ❤️ ') }}">🍩 33₴</a>
    <a class="btn btn-dark btn-xs m-1 btn-fit-text " target="_blank" href="{{ $fundraising->getJarLink(true, 333, '🇺🇦 нехай буде 333 🍩 щодня ❤️ ') }}">🍩 333₴</a>
    <a class="btn btn-dark btn-xs m-1 btn-fit-text " target="_blank" href="{{ $fundraising->getJarLink(true, 3333, '🇺🇦 нехай буде 3333 🍩 щодня ❤️ '), }}">🍩 3333₴</a>
</div>
