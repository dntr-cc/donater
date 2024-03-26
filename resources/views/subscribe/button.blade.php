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
            data-bs-first-message-at="{{ $subscribe?->getNextSubscribesMessage()?->getScheduledAt()->format('Y-m-d H:i') ?? date('Y-m-d H:i', strtotime('+1 hour')) }}">
        🍩 <i class="bi bi-currency-exchange"></i> {{ $subscribe ? 'Редагувати' : 'Підписатися' }}
    </button>
@endauth
@guest
    <a class="btn btn-primary my-2 text-nowrap {{ $additionalClasses }}"
       data-bs-toggle="modal"
       data-bs-target="#subscribe-{{ $volunteer->getKey() }}-guest-modal">
            Підписатися
    </a>
@endguest
</nobr>
<div class="modal fade" id="subscribe-{{ $volunteer->getKey() }}-guest-modal" tabindex="-1"
     aria-labelledby="subscribe-{{ $volunteer->getKey() }}-guest-modalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="subscribe-{{ $volunteer->getKey() }}-guest-modalLabel">Для підписки треба підключити телеграм бота</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-footer justify-content-between">
                <a id="enableBot" target="_blank"
                   href="{{ config('telegram.bots.donater-bot.url') }}?start={{ session()->get(\App\Http\Controllers\Auth\LoginController::LOGIN_HASH, '') }}"
                   class="btn btn-primary my-2">Підключити бота</a>
                <button type="button" class="btn btn-secondary justify-content-evenly"
                        data-bs-dismiss="modal">
                    Закрити
                </button>
            </div>
        </div>
    </div>
</div>

