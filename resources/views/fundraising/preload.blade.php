@php /* @var \App\Collections\RowCollection $rows */ @endphp
@php /* @var \App\Models\Fundraising $fundraising */ @endphp
@php $donaters = new \Illuminate\Support\Collection(); @endphp
@php $donates = new \Illuminate\Support\Collection(); @endphp

<ul class="nav nav-tabs mb-3" id="icons" role="tablist">
    <li class="nav-item" role="presentation">
        <a data-mdb-tab-init class="nav-link active" id="icons-tab-1" href="#donates-all" role="tab"
           aria-controls="donates-tabs-all" aria-selected="true">
            <i class="bi bi-tablet-landscape-fill"></i> Виписка з банки
        </a>
    </li>
    <li class="nav-item" role="presentation">
        <a data-mdb-tab-init class="nav-link" id="icons-tab-2" href="#donates-tabs-users" role="tab"
           aria-controls="donates-tabs-users" aria-selected="false">
            <i class="bi bi-lightning-fill"></i> Донатери збору
        </a>
    </li>
    @foreach($fundraising->getPrizes() as $it => $prize)
        <li class="nav-item" role="presentation">
            <a data-mdb-tab-init class="nav-link" id="icons-tab-4" href="#donates-raffle{{ $it }}" role="tab"
               aria-controls="donates-tabs-raffle" aria-selected="false">
                <i class="bi bi-dice-{{ ($it % 6) + 1 }}-fill"></i> Розіграш #{{ $it + 1 }}
            </a>
        </li>
    @endforeach
</ul>
<div class="tab-content" id="icons-content">
    <div class="tab-pane fade show active" id="donates-all" role="tabpanel" aria-labelledby="donates-all">
        <div class="table-responsive">
            @if(!empty($rows))
                <table class="table table-sm table-striped table-bordered">
                    <thead class="table-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Дата</th>
                        <th scope="col">Донатер</th>
                        <th scope="col">Коментар</th>
                        <th scope="col">Сума</th>
                        <th scope="col">В банці</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php $count = $rows->count(); @endphp
                    @foreach($rows->all() as $row)
                        @php $donater = $row->getDonater($row->getComment()) @endphp
                        @php $donater ? $donaters->push($donater) : '' @endphp
                        <tr>
                            <th scope="row">{{ $count-- }}</th>
                            <td>{{ $row->getDate() }}</td>
                            <td>
                                @if($donater)
                                    Від: {!! $donater->getUserHref()  !!}
                                @elseif($row->isOwnerTransaction())
                                    Від: Власник банки
                                @else
                                    Від: 🐈‍⬛
                                @endif
                            </td>
                            <td class="font-x-small">{{ $row->getComment() }}</td>
                            <td>{{ $row->getAmount() }}</td>
                            <td>{{ $row->getSum() }}</td>
                        </tr>
                    @endforeach
                </table>
            @else
                <h6>Google Spreadsheet Api повернуло помилку. Повторіть пізніше.</h6>
            @endif
        </div>
    </div>
    <div class="tab-pane fade" id="donates-tabs-users" role="tabpanel" aria-labelledby="donates-tabs-users">
        @include('fundraising.donaters', compact('fundraising'))
    </div>
    @foreach($fundraising->getPrizes() as $it => $prize)
        <div class="tab-pane fade" id="donates-raffle{{ $it }}" role="tabpanel"
             aria-labelledby="donates-tabs-raffle">
            @include('layouts.raffle', compact('fundraising', 'prize'))
        </div>
    @endforeach
</div>
