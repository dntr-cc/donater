<?php

use App\Models\Volunteer;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        Volunteer::find(5)->setDescription("Збор коштів на мережеве обладнання для роти звʼязку 80 окремого батальйону управління КМП та тепловізійного монокуляру взводу охорони того ж батальйону. Підрозділ займається забезпеченням звʼязку в Миколаївській та Херсонській областях.<br><br>Розіграш павербанку BASEUS 10000mAh 22.5w 1 донат від 1грн, 1 код - 1 квиток.")->save();
        Volunteer::create([
            'id'             => 6,
            'key'            => 'setnemo_twitter_subscribe',
            'name'           => 'Збір Госпам від setnemo',
            'link'           => 'https://send.monobank.ua/jar/3irfquacv1',
            'page'           => 'https://twitter.com/setnemo/status/1721256589582049664',
            'avatar'         => '/images/banners/setnemo_twitter_subscribe.png',
            'spreadsheet_id' => '1y7TY9Cuo48kvXA4V4WNKouR8UroF7E514Oc8aKifylI',
            'is_enabled'     => true,
            'user_id'        => 1,
            'description'    => "Збір госпам в адміна. Донатить в банку в за кожного підписника в тві. Дедлайн - 25 грудня 2023. За донат в банку від 100грн - шанс виграти нічник Місяць. Всього буде розиграно 2 нічника, один за реєстрацію по посиланню після донату, другий по донатам з кодами з цього сайту.<br><br>Розіграш 2 x Moon Lamp 20sm. 100грн донату - 1 квиток",
        ]);
    }

    public function down(): void
    {
        Volunteer::find(6)?->delete();
    }
};
