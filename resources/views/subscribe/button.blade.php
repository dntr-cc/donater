@php $subscribe = $volunteer->getSubscribe($authUser->getId()); @endphp
<p class="mb-1">
    <button type="button"
            class="btn {{ $subscribe ? 'btn-outline-primary' : 'btn-outline-success' }}"
            data-bs-toggle="modal"
            data-bs-target="#subscribe"
            data-bs-volunteer-id="{{ $volunteer->getId() }}"
            data-bs-volunteer-key="{{ $volunteer->getUsername() }}"
            data-bs-volunteer-name="{{ $volunteer->getFullName() }}"
            data-bs-update="{{ $subscribe ? '1' : '0' }}"
            data-bs-url="{{ $subscribe ? route('subscribe.update', compact('subscribe')) : route('subscribe.create') }}"
            data-bs-del-url="{{ $subscribe ? route('subscribe.delete', compact('subscribe')) : '' }}"
            data-bs-amount="{{ $subscribe?->getAmount() ?? 33 }}"
            data-bs-sum="{{ ($subscribe?->getAmount() ?? 33) * 30 }}"
            data-bs-scheduled-at="{{ $subscribe?->getScheduledAt() ?? '10:00' }}"
            data-bs-use-random="{{ $subscribe?->isUseRandom() ? '1' : '0' }}">
        🍩 <i class="bi bi-currency-exchange"></i>
    </button>
</p>
