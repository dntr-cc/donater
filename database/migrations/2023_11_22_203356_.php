<?php

use App\Models\Fundraising;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        Fundraising::create([
            'id'             => 8,
            'key'            => '52_fpv_01',
            'name'           => 'Зб*р на 5 FPV для 52-го ОСБ 🐝',
            'link'           => 'https://send.monobank.ua/jar/5qL3tGuQ5t',
            'page'           => 'https://twitter.com/a_vodianko/status/1725862229768016335',
            'avatar'         => '/images/banners/52_fpv_01.png',
            'spreadsheet_id' => '18E4505ukqmyVpXDV1_ANqqt67iGsR6Ob9ibdPT-xOEw',
            'is_enabled'     => true,
            'user_id'        => 1,
            'description'    => <<<HTML
                    Зб*р на 5 FPV для 52-го ОСБ 🐝<br>
                    Ситуація на Авдіївському напрямку складна. Ворог не економить FPV, запускає цілі рої др*нів по нашій піхоті. 5 FPV це мало, але зберемо більше-купимо більше!<br>
                    Номер карти моно: 5375411209848097<br>
                    PayPal: a.vodianko@gmail.com<br>
                    <br>
                    Розіграш Світлодіодний LED ліхтар Unibrother LY01 | З роботою до 60 годин | 15600 mAh | 278 LED | 80W за донат з кодом! Кожні 10грн донату - 1 квиток<br>
                    HTML,
        ]);
    }

    public function down(): void
    {
        Fundraising::find(8)?->delete();
    }
};
