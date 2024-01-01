@php use App\Models\Donate; @endphp
@php /** @var App\Models\Fundraising $fundraising */ @endphp
<div class="table-responsive">
    <table class="table table-sm table-striped table-bordered">
        <thead class="table-dark">
        <tr>
            <th scope="col">#</th>
            <th scope="col">Користувач</th>
            <th scope="col">Кількість внесків</th>
            <th scope="col">Сума</th>
        </tr>
        </thead>
        <tbody>
        @foreach($fundraising->getDonateCollectionWithAmount()->getRaffleUserCollection()->all() as $it => $raffleUser)
            <tr>
                <th scope="row">{{ $it + 1 }}</th>
                <td><a href="{{ $raffleUser->getUser()->getUserLink() }}"
                       class="">{{ $raffleUser->getUser()->getUsernameWithFullName() }}</a></td>
                <td>{{ $raffleUser->getDonateCollection()->count() }}</td>
                <td>{{ round($raffleUser->getDonateCollection()->sum(fn(Donate $donate) => $donate->getAmount()), 2) }}</td>
            </tr>
        @endforeach
    </table>
</div>
