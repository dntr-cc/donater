@extends('layouts.base')
@section('page_title', strtr('Звітність по :volunteer - donater.com.ua', [':volunteer' => $volunteer->getName()]))
@section('page_description', strtr('Звітність по :volunteer - donater.com.ua', [':volunteer' => $volunteer->getName()]))

@section('content')
    <div class="container px-4 py-5" id="custom-cards">
        <h2 class="pb-2 border-bottom"><a href="{{ route('zvit') }}" class=""><i class="bi bi-arrow-left"></i></a>
            Звітність {{ $volunteer->getName() }}
        </h2>
        <div class="d-flex">
            <div class="row">
                <div class="col-md-4 px-2 py-2">
                    <span
                        class="card card-cover h-100 overflow-hidden text-bg-dark rounded-4 shadow-lg bg-image-position-center"
                        style="background-image: url('{{ url($volunteer->getAvatar()) }}');">
                        <div class="d-flex flex-column min-vh-25 h-100 p-4 pb-3 text-shadow-1">
                        </div>
                    </span>
                </div>
                <div class="col-md-8 px-2 py-2">
                    <p class="lead">
                        {!! $volunteer->getDescription() !!}
                    </p>
                    <a href="{{ $volunteer->getLink() }}" target="_blank" class="btn btn-outline-success">Банка</a>
                    <a href="{{ $volunteer->getPage() }}" target="_blank" class="btn btn-outline-success">Сторінка
                        збору</a>
                </div>
            </div>
        </div>
        <h3 class="mt-3 pb-2 border-bottom">
            <i class="bi bi-tablet-landscape-fill"></i> Виписка з банки
        </h3>
        <div class="table-responsive">
            <table class="table table-sm table-striped table-bordered">
                <thead class="table-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Дата</th>
                    <th scope="col">Користувач</th>
                    <th scope="col">Коментар</th>
                    <th scope="col">Сума</th>
                    <th scope="col">В банці</th>
                </tr>
                </thead>
                <tbody>
                @php $count = $rows->count(); @endphp
                @foreach($rows->all() as $row)
                    @php $donater = $row->getDonater($row->getComment()) @endphp
                    <tr>
                        <th scope="row">{{ $count-- }}</th>
                        <td>{{ $row->getDate() }}</td>
                        <td>
                            @if($donater)
                                <a href="{{$donater->getUserLink()}}" class="">
                                    Від: {{$donater->getFullName() }} ({{ $donater->getAtUsername() }})
                                </a>
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
        </div>
    </div>
@endsection
