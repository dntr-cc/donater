<?php

declare(strict_types=1);

namespace App\Bot;

use Telegram\Bot\Commands\Command;

class FaqCommand extends Command
{
    protected string $name = 'faq';

    /**
     * @var string Command Description
     */
    protected string $description = 'Довідка';

    /**
     * {@inheritdoc}
     */
    public function handle(): void
    {
        $faqText = route('faq');

        $this->replyWithMessage(['text' => $faqText]);
    }
}
