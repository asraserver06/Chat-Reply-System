@extends('layouts.user')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

        <div>
            <h1 class="text-2xl font-bold text-gray-800">Subscription Plans</h1>
            <p class="text-gray-500 text-sm mt-1">Choose the plan that's right for you.</p>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl">✅ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl">⚠️ {{ session('error') }}</div>
        @endif

        {{-- Current Status --}}
        @if($subscription)
        <div class="bg-indigo-50 border border-indigo-200 rounded-2xl p-5 flex items-center justify-between">
            <div>
                <div class="font-semibold text-indigo-800">Current Plan: <span class="capitalize">{{ $currentPlan }}</span></div>
                <div class="text-sm text-indigo-600 mt-0.5">
                    Status: <span class="font-medium">{{ ucfirst($subscription->stripe_status) }}</span>
                    @if($subscription->ends_at)
                        · Ends {{ $subscription->ends_at->format('M d, Y') }}
                    @endif
                </div>
            </div>
            <div class="flex gap-2">
                @if($subscription->onGracePeriod())
                <form method="POST" action="{{ route('user.subscription.resume') }}">
                    @csrf
                    <button class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-sm font-medium hover:bg-indigo-700 transition">Resume</button>
                </form>
                @else
                <form method="POST" action="{{ route('user.subscription.cancel') }}"
                      onsubmit="return confirm('Cancel your subscription?')">
                    @csrf
                    <button class="px-4 py-2 border border-red-300 text-red-600 rounded-xl text-sm font-medium hover:bg-red-50 transition">Cancel</button>
                </form>
                @endif
            </div>
        </div>
        @endif

        {{-- Plans Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($plans as $plan)
            @php $isCurrent = $currentPlan === $plan->slug; @endphp
            <div class="bg-white rounded-2xl shadow {{ $plan->slug === 'pro' ? 'ring-2 ring-indigo-500' : 'border border-gray-200' }} p-6 relative flex flex-col">

                @if($plan->slug === 'pro')
                <div class="absolute -top-3 left-1/2 -translate-x-1/2 bg-indigo-600 text-white text-xs font-bold px-3 py-1 rounded-full">MOST POPULAR</div>
                @endif

                <div class="text-lg font-bold text-gray-800 mb-1">{{ $plan->name }}</div>
                <div class="text-3xl font-extrabold text-gray-900 mb-1">
                    ${{ number_format($plan->price, 2) }}
                    <span class="text-sm font-normal text-gray-400">/mo</span>
                </div>
                <div class="text-xs text-gray-500 mb-4">
                    {{ $plan->message_limit ? $plan->message_limit . ' messages/month' : 'Unlimited messages' }}
                </div>

                <ul class="space-y-2 mb-6 flex-1">
                    @foreach(($plan->features ?? []) as $feature)
                    <li class="flex items-center gap-2 text-sm text-gray-600">
                        <span class="text-green-500 font-bold">✓</span> {{ $feature }}
                    </li>
                    @endforeach
                </ul>

                @if($isCurrent)
                <div class="w-full py-2.5 rounded-xl bg-gray-100 text-center text-sm font-medium text-gray-500">Current Plan</div>
                @elseif($plan->isFree())
                <form method="POST" action="{{ route('user.subscription.subscribe') }}">
                    @csrf
                    <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                    <button class="w-full py-2.5 rounded-xl bg-gray-700 text-white text-sm font-medium hover:bg-gray-800 transition">Switch to Free</button>
                </form>
                @else
                <button onclick="openSubscribeModal({{ $plan->id }}, '{{ $plan->name }}')"
                    class="w-full py-2.5 rounded-xl {{ $plan->slug === 'pro' ? 'bg-indigo-600 hover:bg-indigo-700' : 'bg-gray-800 hover:bg-gray-900' }} text-white text-sm font-medium transition">
                    Subscribe
                </button>
                @endif
            </div>
            @endforeach
        </div>

    </div>
</div>

{{-- Subscribe Modal --}}
<div id="subscribeModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-1">Subscribe to <span id="modalPlanName"></span></h2>
        <p class="text-sm text-gray-500 mb-4">Enter your Stripe payment method ID (from Stripe.js in production).</p>
        <form method="POST" action="{{ route('user.subscription.subscribe') }}">
            @csrf
            <input type="hidden" name="plan_id" id="modalPlanId">
            <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method ID</label>
            <input type="text" name="payment_method" placeholder="pm_card_visa"
                   class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 mb-4"
                   required>
            <div class="flex gap-3 justify-end">
                <button type="button" onclick="document.getElementById('subscribeModal').classList.add('hidden')"
                    class="px-4 py-2 rounded-xl border text-sm text-gray-600 hover:bg-gray-50">Cancel</button>
                <button type="submit"
                    class="px-4 py-2 rounded-xl bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700">Confirm</button>
            </div>
        </form>
    </div>
</div>

<script>
function openSubscribeModal(planId, planName) {
    document.getElementById('modalPlanId').value = planId;
    document.getElementById('modalPlanName').textContent = planName;
    document.getElementById('subscribeModal').classList.remove('hidden');
}
</script>
@endsection
