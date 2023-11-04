<?php

namespace App\Console\Commands;

use App\Models\Donate;
use App\Models\User;
use App\Services\UserCodeService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Throwable;

class MigrateCodesCommand extends Command
{
    protected $signature = 'codes:migrate';

    protected $description = 'Command description';

    public function handle(): void
    {
        $service = app(UserCodeService::class);
        try {
            $collection = Donate::all();
            $count = $collection->count();
            $this->output->createProgressBar($count);
            $this->output->progressStart();
            /** @var Donate $donate */
            foreach ($collection as $donate) {
                $service->createUserCode($donate->getUserId(), $donate->getHash());
                $this->output->progressAdvance();
            }
            $this->output->progressFinish();
        } catch (Throwable $t) {
            Log::error($t->getMessage(), ['trace' => $t->getTraceAsString()]);
        }
        $collection = User::all();
        $count = $collection->count();
        $this->output->createProgressBar($count);
        $this->output->progressStart();
        foreach ($collection as $user) {
            $hash = $service->createCode();
            $service->createUserCode($user->getId(), $hash);
            $this->output->progressAdvance();
        }
        $this->output->progressFinish();
    }
}
