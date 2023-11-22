<?php

use App\Models\Volunteer;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        Volunteer::create([
            'id'             => 7,
            'key'            => 'rusoriz',
            'name'           => 'Русоріз пана Стерненко',
            'link'           => 'https://send.monobank.ua/jar/9ekqtYDMca',
            'page'           => 'https://twitter.com/sternenko',
            'avatar'         => '/images/banners/rusoriz.png',
            'spreadsheet_id' => '1UbzlN19o1ahNK-rmnDwB5pPkR1E-O5YiGy6MwMyyn38',
            'is_enabled'     => true,
            'user_id'        => 1,
            'description'    => <<<HTML
                    🫡 Спільнота пана Стерненко допомогли війську на понад 480 (!!!) мільйонів гривень!<br>
                    <br>
                    Саме такий є проміжний звіт за період з 1 квітня 2022 по 12 серпня 2023. <br>
                    Насправді сума і кількість переданого майна вже більша. <br>
                    <br>
                    На ці кошти ми закупили у армію:<br>
                    - понад 6000 FPV-дронів<br>
                    - майже 1000 інших БПЛА<br>
                    - 110 тепловізорів<br>
                    - 107 ПНБ<br>
                    - 225 одиниць транспорту <br>
                    Та багато-багато іншого!<br>
                    Зокрема, 1 надводний дрон та вклали кошти у дрони дальнього радіусу дії😉<br>
                    <br>
                    Також до цієї справи долучився і я, сума моїх донатів склала 2 658 984 грн. <br>
                    Але ваш внесок — величезний!<br>
                    Ви дуже істотно допомогли фронту. <br>
                    <br>
                    Дякую вам!<br>
                    <br>
                    Волонтерські картки: <br>
                    4441114454997899 моно<br>
                    5168745030910761 приват<br>
                    HTML,
        ]);
    }

    public function down(): void
    {
        Volunteer::find(7)?->delete();
    }
};
