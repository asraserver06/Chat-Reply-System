@extends('layouts.admin')
@section('page-title', 'Subscriptions')

@section('content')

<!-- ── Plans Overview ── -->
<div class="card" style="margin-bottom:28px;">
    <div class="card-header">
        <span class="card-title">📦 Subscription Plans</span>
        <span style="color:var(--text-muted); font-size:13px;">{{ $plans->count() }} plans</span>
    </div>
    <div class="card-body">
        @if($plans->isEmpty())
        <div style="text-align:center; padding:40px; color:var(--text-muted);">
            No subscription plans found. Add plans in your Stripe dashboard and seed them here.
        </div>
        @else
        <div style="display:grid; grid-template-columns:repeat(auto-fill,minmax(240px,1fr)); gap:20px; padding:20px;">
            @foreach($plans as $plan)
            <div style="border:1px solid var(--border); border-radius:12px; padding:22px; background:{{ $plan->is_active ? '#f0fdf4' : '#f8fafc' }};">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                    <div style="font-size:16px; font-weight:700;">{{ $plan->name }}</div>
                    <span class="badge {{ $plan->is_active ? 'badge-green' : 'badge-gray' }}">
                        {{ $plan->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                <div style="font-size:28px; font-weight:800; color:var(--text-main); margin-bottom:4px;">
                    ${{ number_format($plan->price, 2) }}
                    <span style="font-size:13px; color:var(--text-muted); font-weight:400;">/mo</span>
                </div>
                <div style="font-size:13px; color:var(--text-muted); margin-bottom:12px;">
                    Msg limit: {{ $plan->message_limit ?? 'Unlimited' }}
                </div>
                @if($plan->features)
                <ul style="padding-left:0; list-style:none; font-size:13px; line-height:2;">
                    @foreach($plan->features as $feature)
                    <li>✔ {{ $feature }}</li>
                    @endforeach
                </ul>
                @endif
                <div style="margin-top:14px; font-size:11px; color:#94a3b8; word-break:break-all;">
                    Stripe ID: {{ $plan->stripe_price_id }}
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>

<!-- ── Subscribers Table ── -->
<div class="card">
    <div class="card-header">
        <span class="card-title">👥 Subscribed Users</span>
        <span style="color:var(--text-muted); font-size:13px;">{{ $subscribers->total() }} users</span>
    </div>
    <div class="card-body">
        <table>
            <thead>
                <tr>
                    <th>User</th>
                    <th>Email</th>
                    <th>Plan / Type</th>
                    <th>Stripe Status</th>
                    <th>Trial Ends</th>
                    <th>Ends At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subscribers as $user)
                    @foreach($user->subscriptions as $sub)
                    <tr>
                        <td>
                            <div style="display:flex; align-items:center; gap:9px;">
                                <div style="width:30px;height:30px;border-radius:50%;background:linear-gradient(135deg,#6366f1,#a855f7);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:11px;flex-shrink:0;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <span style="font-weight:600;">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td style="color:var(--text-muted); font-size:13px;">{{ $user->email }}</td>
                        <td><span class="badge badge-blue">{{ $sub->type }}</span></td>
                        <td>
                            @php
                                $statusColors = [
                                    'active'   => 'badge-green',
                                    'trialing' => 'badge-blue',
                                    'past_due' => 'badge-yellow',
                                    'canceled' => 'badge-red',
                                    'incomplete'=> 'badge-gray',
                                ];
                                $color = $statusColors[$sub->stripe_status] ?? 'badge-gray';
                            @endphp
                            <span class="badge {{ $color }}">{{ ucfirst($sub->stripe_status) }}</span>
                        </td>
                        <td style="color:var(--text-muted); font-size:13px;">
                            {{ $sub->trial_ends_at ? $sub->trial_ends_at->format('M d, Y') : '—' }}
                        </td>
                        <td style="color:var(--text-muted); font-size:13px;">
                            {{ $sub->ends_at ? $sub->ends_at->format('M d, Y') : '—' }}
                        </td>
                        <td>
                            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline btn-sm">👤 Profile</a>
                        </td>
                    </tr>
                    @endforeach
                @empty
                <tr>
                    <td colspan="7" style="text-align:center; padding:40px; color:var(--text-muted);">
                        No subscribed users yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($subscribers->hasPages())
    <div class="pagination-wrap">
        {{ $subscribers->links() }}
    </div>
    @endif
</div>

@endsection
