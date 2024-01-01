<?php

namespace App\Console\Commands;

use App\Models\Donate;
use App\Models\Fundraising;
use App\Models\User;
use App\Services\GoogleServiceSheets;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Laravel\Facades\Telegram;
use Throwable;

class RaffleCommand extends Command
{
    protected $signature = 'raffle:fundraising {fundraising_prefix : Short uniq fundraising prefix} {ticket=1} {winners=1}';

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
        if ($prefix = $this->argument('fundraising_prefix')) {
            $winnersIdsFilter = [1, 3];
            $all = $this->getAll($winnersIdsFilter, $prefix);
            $count = count($all);
            $this->output->info(strtr('All tickets: :count', [':count' => $count]));
            $winners = 0;
            $realWinners = (int)$this->argument('winners');
            while (1) {
                shuffle($all);
                shuffle($all);
                shuffle($all);
                $count = count($all) - 1;
                $winnerNumber = mt_rand(0, $count);
                $this->output->info(strtr('Winner number is :number', [':number' => $winnerNumber]));
                $winner = User::find($all[$winnerNumber]);
                $this->output->info(strtr('Winner is :winner', [':winner' => $winner?->getFullName() . ' ' . $winner->getUserLink()]));
                try {
                    $winnersIdsFilter = array_merge($winnersIdsFilter, [$winner->getId()]);
                    $this->output->info($winnersIdsFilter);
                    $all = $this->getAll($winnersIdsFilter, $prefix);
                    Telegram::sendMessage(['chat_id' => $winner?->getTelegramId(), 'text' => 'Вітаю! Ви виграли подарунок, пишить @setnemo в приватні повідомлення']);
                    $winners++;
                    if ($realWinners === $winners) {
                        break;
                    }
                } catch (Throwable $t) {
                    $this->output->info('Skip...');
                    Log::error($t->getMessage(), [':winner' => $winner?->getFullName(), 'trace' => $t->getTraceAsString()]);
                }
            }
        }
    }

    /**
     * @param array $winnersIdsFilter
     * @param string $prefix
     * @return array
     */
    protected function getAll(array $winnersIdsFilter, string $prefix): array
    {
        $users = $all = [];
        /** @var Donate $donate */
        $collection = Donate::query()->whereNotIn('user_id', $winnersIdsFilter)
            ->where('amount', '>', 0)
            ->where('fundraising_id', '=', Fundraising::query()->where('key', '=', $prefix)->first()->getId())
            ->get();
        foreach ($collection->all() as $donate) {
            $users[$donate->getUserId()] = $users[$donate->getUserId()] ?? 0;
            $users[$donate->getUserId()] += $donate->getAmount();
        }
        $usersTmp = [];
        foreach ($users as $userId => $amount) {
            if ($amount >= $this->argument('ticket')) {
                $usersTmp[$userId] = $amount;
            }
        }
        $users = $usersTmp;
        foreach ($users as $userId => $amount) {
            /** @var User $user */
            $user = User::find($userId);
            $this->output->info(strtr(':user donate :sum', [':user' => $user->getFullName(), ':sum' => $amount]));
            $all = array_merge($all, array_fill(0, (int)($amount / $this->argument('ticket')), $userId));
        }

        return $all;
    }
}
