<?php

namespace App\Console\Commands;

use App\Models\Donate;
use App\Models\User;
use App\Services\GoogleServiceSheets;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Telegram;
use Throwable;

class AllUsersRaffleCommand extends Command
{
    protected $signature = 'raffle:all';

    protected $description = 'Command description';
    private GoogleServiceSheets $service;

    public function __construct()
    {
        parent::__construct();
        $this->service = app(GoogleServiceSheets::class);
    }

    public function getService(): GoogleServiceSheets
    {
        return $this->service;
    }

    public function handle(): void
    {
        $users = $all = [];
        try {
            /** @var Donate $donate */
            $collection = Donate::query()->whereNotIn('user_id', [1, 3])
                ->where('amount', '>', 0)->get();
            foreach ($collection->all() as $donate) {
                $users[$donate->getUserId()] = $users[$donate->getUserId()] ?? 0;
                $users[$donate->getUserId()] += $donate->getAmount();
            }
            foreach ($users as $userId => $amount) {
                /** @var User $user */
                $user = User::find($userId);
                $this->output->info(strtr(':user donate :sum', [':user' => $user->getFullName(), ':sum' => $amount]));
                $all = array_merge($all, array_fill(0, (int)$amount, $userId));
            }
            $count = count($all);
            $this->output->info(strtr('All tickets: :count', [':count' => $count]));
            while (1) {
                shuffle($all);
                shuffle($all);
                shuffle($all);
                $winnerNumber = mt_rand(0, $count);
                $this->output->info(strtr('Winner number is :number', [':number' => $winnerNumber]));
                $this->output->info(strtr('Winner is :winner', [':winner' => User::find($all[$winnerNumber])?->getFullName() ?? '']));
            }
        } catch (Throwable $t) {
            Log::error($t->getMessage(), ['trace' => $t->getTraceAsString()]);
        }
    }
}
