@php /** @var App\Models\Donate $donate */ @endphp
@php $donatesWithUser = $donatesWithUser ?? false; @endphp
<div class="table-responsive">
    <table class="table table-sm table-striped table-bordered">
        <thead class="table-dark">
        <tr>
            <th scope="col">
                @if($donatesWithUser)
                    Користувач
                @else
                    #
                @endif
            </th>
            <th scope="col">Збір</th>
            <th scope="col">Дата</th>
            <th scope="col">Сума</th>
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
                    @include('layouts.fundraising_status', [
                        'fundraising' => $donate->getFundraising(),
                        'additionalClasses' => 'btn-xs'
                    ])
                </td>
                <td>{{ $donate->getCreatedAt() }}</td>
                <td>{{ $donate->getAmount() }}</td>
            </tr>
        @endforeach
    </table>
</div>
