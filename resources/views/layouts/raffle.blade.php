@php use App\Models\Fundraising; @endphp
@php /** @var Fundraising $fundraising */@endphp
<div class="tab-pane fade show active" id="" role="tabpanel"
     aria-labelledby="icons-tab-1">
    <form action="">
        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="form-check m-2">
                    <input class="form-check-input" type="radio" name="type" id="type1" checked>
                    <label class="form-check-label" for="type2">
                        За сумою донатів (один квиток - фіксована сума)
                    </label>
                </div>
                <div class="form-check m-2">
                    <input class="form-check-input" type="radio" name="type" id="type2">
                    <label class="form-check-label" for="type1">
                        Серед учасників, які донатили (сума не важлива)
                    </label>
                </div>
            </div>
            <div id="price_input" class="col-md-4 mb-3">
                <div class="form-floating">
                    <input type="number" step="1" min="1" class="form-control" id="price"
                           value="1">
                    <label for="price">
                        Ціна квитка
                    </label>
                </div>
            </div>
            <div id="price_input" class="col-md-4 mb-3">
                <div class="form-floating">
                    <input type="number" step="1" min="1" class="form-control" id="winners"
                           value="1">
                    <label for="winners">
                        Кількість переможців
                    </label>
                </div>
            </div>
            <div class="col-md-12">
                <div class="col-md-3">
                    <button id="winners_input" type="submit" class="btn">ПОРАХУВАТИ УЧАСНИКІВ</button>
                </div>
            </div>
        </div>
    </form>
</div>
<script type="module">
    let type1 = $('#type1');
    let type2 = $('#type2');

    function showAndHidePrice(event) {
        event.preventDefault();
        let priceInput = $('#price_input');
        if (type2.is(':checked')) {
            priceInput.addClass('hide');
        } else {
            priceInput.removeClass('hide');
        }
    }

    type1.change(event => {
        showAndHidePrice(event);
    });

    type2.change(event => {
        showAndHidePrice(event);
    });

    $('#winners_input').on('click', event => {
        event.preventDefault();
        $.ajax({
            url: '{{ route('fundraising.raffles.predict', compact('fundraising')) }}',
            type: "POST",
            data: {
                settings: {
                    @foreach(\App\Models\UserSetting::SETTINGS_MAP as $key => $name)
                        {{ $key }}: $('#{{ $key }}').is(':checked') ? 1 : 0,
                    @endforeach
                },
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: data => {
                console.log(data);
                $('meta[name="csrf-token"]').attr('content', data.csrf);
            },
            error: data => {
                let empty = $("<a>");
                toast(JSON.parse(data.responseText).message, empty, 'text-bg-danger');
                empty.click();
                $('meta[name="csrf-token"]').attr('content', data.csrf);
            },
        });
        return false;
    });
</script>
