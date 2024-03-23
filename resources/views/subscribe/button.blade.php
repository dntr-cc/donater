<nobr>

@auth
    @php $subscribe = $volunteer->getSubscribe($authUser->getId()); @endphp
    @php $additionalClasses = $additionalClasses ?? '' @endphp
    <button type="button"
            class="btn {{ $subscribe ? 'btn-outline-primary' : 'btn-outline-success' }} {{ $additionalClasses }}"
            data-bs-toggle="modal"
            data-bs-target="#subscribe"
            data-bs-volunteer-id="{{ $volunteer->getId() }}"
            data-bs-volunteer-key="{{ $volunteer->getUsername() }}"
            data-bs-volunteer-name="{{ $volunteer->getFullName() }}"
            data-bs-update="{{ $subscribe ? '1' : '0' }}"
            data-bs-url="{{ $subscribe ? route('subscribe.update', compact('subscribe')) : route('subscribe.create') }}"
            data-bs-del-url="{{ $subscribe ? route('subscribe.delete', compact('subscribe')) : '' }}"
            data-bs-amount="{{ $subscribe?->getAmount() ?? 33 }}"
            data-bs-frequency="{{ $subscribe?->getNextSubscribesMessage()?->getFrequency() ?? \App\Models\SubscribesMessage::DAILY_NAME }}"
            data-bs-first-message-at="{{ $subscribe?->getNextSubscribesMessage()?->getScheduledAt()->format('Y-m-d H:i') ?? date('Y-m-d H:i', strtotime('+1 hour')) }}"
            data-bs-use-random="{{ $subscribe?->isUseRandom() ? '1' : '0' }}">
        🍩 <i class="bi bi-currency-exchange"></i> {{ $subscribe ? 'Редагувати' : 'Підписатися' }}
    </button>
    @endauth
    @guest
        <a target="_blank" href="{{ config('telegram.bots.donater-bot.url') }}?start={{ app(App\Services\LoginService::class)->getNewLoginHash() }}" class="btn btn-primary my-2">Підписатися</a>
    @endguest
</nobr>

