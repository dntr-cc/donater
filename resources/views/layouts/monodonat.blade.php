@php /** @var App\Models\Fundraising $fundraising */ @endphp
<h4 class="text-center">Збір триває, зробити донат</h4>
<div class="progress">
    <div class="progress-bar progress-bar-animated-reverse active-right progress-bar-striped" role="progressbar"
         style="width: 100%;" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
</div>
<div class="row row-cols-xl-4 row-cols-lg-4 row-cols-md-1 row-cols-sm-1 row-cols-sm-1 d-flex justify-content-around m-4">
    <a class="btn btn-dark btn-xs m-1 btn-fit-text " target="_blank" href="{{ $fundraising->getJarLink(true, 100, 'With ❤️ to 🇺🇦') }}">🍩 100грн.</a>
    <a class="btn btn-dark btn-xs m-1 btn-fit-text " target="_blank" href="{{ $fundraising->getJarLink(true, 500, 'With ❤️ to 🇺🇦') }}">🍩 500грн.</a>
    <a class="btn btn-dark btn-xs m-1 btn-fit-text " target="_blank" href="{{ $fundraising->getJarLink(true, 1000, 'With ❤️ to 🇺🇦'), }}">🍩 1000грн.</a>
</div>
