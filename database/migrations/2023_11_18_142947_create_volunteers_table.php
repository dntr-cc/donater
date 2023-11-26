<?php

use App\Models\Volunteer;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('volunteers', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('name');
            $table->string('link');
            $table->string('page');
            $table->text('description')->default('');
            $table->string('spreadsheet_id')->default('');
            $table->string('avatar')->default('');
            $table->boolean('is_enabled')->default(false);
            $table->unsignedBigInteger('user_id');
            $table->softDeletes();
            $table->timestamps();
        });
        Volunteer::create([
            'id'             => 1,
            'key'            => 'savelife',
            'name'           => 'Повернись живим',
            'link'           => 'https://send.monobank.ua/jar/91w3asqDZt',
            'page'           => 'https://savelife.in.ua/en/',
            'avatar'         => '/images/banners/savelife.png',
            'spreadsheet_id' => '1YqwMthW7y5SXM059AuAogkmhDg7BVMextxPT3IuB9_s',
            'is_enabled'     => true,
            'user_id'        => 1,
            'description'    => '«Повернись живим» — це благодійний фонд компетентної допомоги армії, а також громадська організація, яка займається аналітикою у секторі безпеки та оборони, реалізує проєкти з реабілітації ветеранів через спорт.',
        ]);
        Volunteer::create([
            'id'             => 2,
            'key'            => 'prytulafoundation',
            'name'           => 'Фонд Сергія Притули',
            'link'           => 'https://send.monobank.ua/jar/4aqbQf23WR',
            'page'           => 'https://prytulafoundation.org/',
            'avatar'         => '/images/banners/prytulafoundation.png',
            'spreadsheet_id' => '1dKiA7w69uv5FaawrSEXg04eiQW1FafDr8vNTGBTKAok',
            'is_enabled'     => true,
            'user_id'        => 1,
            'description'    => 'Благодійний фонд Сергія Притули опікується посиленням Сил Оборони України, а також допомогою цивільному населенню, яке постраждало від російської агресії.',
        ]);
        Volunteer::create([
            'id'             => 3,
            'key'            => 'hospitallers',
            'name'           => 'Медичний батальйон "Госпітальєри"',
            'link'           => 'https://send.monobank.ua/jar/4Mtimtorvu',
            'page'           => 'https://www.hospitallers.life',
            'avatar'         => '/images/banners/hospitallers.png',
            'spreadsheet_id' => '1ZSPaWAdm4aW-ZBwrzdk5u-vQwSda_wigj6bVjrDelOk',
            'is_enabled'     => true,
            'user_id'        => 1,
            'description'    => '“Госпітальєри”— добровольча організація парамедиків. Була заснована Яною Зінкевич на початку бойових дій в Україні у 2014 році. Тоді Росія анексувала Крим і розпочала бойові дії на сході країни.',
        ]);
        Volunteer::create([
            'id'             => 4,
            'key'            => 'letsseethevictory',
            'name'           => 'Фонд "Побачимо Перемогу"',
            'link'           => 'https://send.monobank.ua/jar/4TmC32mY17',
            'page'           => 'https://thevictory.org.ua',
            'avatar'         => '/images/banners/letsseethevictory.png',
            'spreadsheet_id' => '1lB-CZLWPg--o5YMdNbvuokL1Gmv_YzzEwqCa17JZfpA',
            'is_enabled'     => true,
            'user_id'        => 1,
            'description'    => 'Місія Благодійного Фонду полягає в тому, щоб допомагати людям, які втратили зір під час війни. На жаль, пересадка очей неможлива, тому життя людей після такої травми змінюється радикально.',
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('volunteers');
    }
};
