@php /** @var App\Models\Donate $donate */ @endphp
@php $donatesWithUser = $donatesWithUser ?? false; @endphp
<div class="table-responsive">
    <table class="table table-sm table-striped table-bordered">
        <thead class="table-dark">
        <tr>
            <th scope="col">
                @if($donatesWithUser)
                    Донатер
                @else
                    #
                @endif
            </th>
            <th scope="col">Збір</th>
            <th scope="col">Дата</th>
            <th scope="col" colspan="2">Сума</th>
        </tr>
        </thead>
        <tbody>
        @php $count = $donates->count(); @endphp
        @foreach($donates->all() as $donate)
            <tr>
                <th scope="row">
                    @if($donatesWithUser)
                        {!! $donate->withDonater()->donater->getUserHref() !!}
                    @else
                        {{ $count-- }}
                    @endif
                </th>
                <td>
                    @include('fundraising.status', [
                        'fundraising' => $donate->getFundraising(),
                        'additionalClasses' => 'btn-xs'
                    ])
                </td>
                <td>{{ $donate->getCreatedAt() }}</td>
                <td>{{ $donate->getAmount() }}</td>
                @can('delete', $donate)
                    <td><span class="btn btn-xs btn-outline-danger delete-donate" data-id="{{ $donate->getId() }}"><i
                                class="mx-2 bi bi-x-octagon"></i></span></td>
                @endcan
            </tr>
        @endforeach
    </table>
</div>
<script type="module">
    $('.delete-donate').on('click', event => {
        event.preventDefault();
        if (!confirm('Видалений донат відновитсья з виписки протягом 5-15хв. Донатера буде повідомлено про валідацію донату. Видалити?')) {
            return;
        }
        $.ajax({
            url: '{{ route('donates') }}' + '/' + $(event.target).attr('data-id'),
            type: "DELETE",
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function () {
                location.reload();
            },
        });
        return false;
    });
</script>
