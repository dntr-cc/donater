@php /** @var App\Models\Donate $donate */ @endphp
@foreach($donates as $it => $donate)
    <div data-hash="{{ $donate->getHash() }}" class="col-md-12 m-2 hashes border-0 shadow">
        <div class="row m-2 p-2">
            <div class="col-md-9">
                @php $user = $donate->donater()->first(); @endphp
                <div class="fw-bold">
                    <a href="{{ route('user', compact('user')) }}"
                       class="me-auto" style="color: rgba(var(--bs-body-color-rgb),var(--bs-text-opacity, 1))">
                        {{ $user->getAtUsername() }} - {{ $user->getFullName() }}</a>
                    <br>
                    <a href="{{ route('fundraising.show', ['fundraising' => $donate->getFundraising()]) }}"
                       class="me-auto" style="color: rgba(var(--bs-body-color-rgb),var(--bs-text-opacity, 1))">
                        {{ $donate->getHumanType() }}
                    </a>
                </div>
                Код внеску <span class="code text-decoration-underline"
                                 data-code="{{ $donate->getHash() }}">{{ $donate->getHash() }}</span>.
                Створено {{ $donate->getCreatedAt()->format('Y-m-d H:i:s') }}.

            </div>
            <div class="col-md-3 d-inline align-items-baseline">
                    <span class="badge text-bg-success">
                Завалідовано
                <i class="bi bi-check-circle-fill text-bg-success"></i>
            </span>
            </div>
        </div>
    </div>
@endforeach
<script type="module">
    $('.code').on('click', event => {
        event.preventDefault();
        copyContent($(event.target).attr('data-code'));
        let empty = $("<a>");
        toast('Код скопійовано', empty);
        empty.click();
        return false;
    });
</script>
