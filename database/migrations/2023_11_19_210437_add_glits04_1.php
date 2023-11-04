<?php

use App\Models\Volunteer;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        Volunteer::create([
            'id'             => 4,
            'key'            => 'glits04_1',
            'name'           => 'Збір на зв\'язок',
            'link'           => 'https://send.monobank.ua/jar/6nfMdq4Wph',
            'page'           => 'https://twitter.com/Gilts04',
            'avatar'         => '/images/banners/glits04_1.png',
            'spreadsheet_id' => '1l47ghNanVRl5Q4lIxhFsA13iMNXXcKxbCdD_OWNnGvs',
            'is_enabled'     => true,
            'user_id'        => 1,
            'description'    => 'Збор коштів на мережеве обладнання для роти звʼязку 80 окремого батальйону управління КМП та тепловізійного монокуляру взводу охорони того ж батальйону. Підрозділ займається забезпеченням звʼязку в Миколаївській та Херсонській областях',
        ]);
    }

    public function down(): void
    {
        Volunteer::find(4)?->delete();
    }
};
