@extends('layouts.admin')
@section('page-title', 'Users')

@section('content')

<div class="card">
    <div class="card-header">
        <span class="card-title">👥 All Users</span>
        <span style="color:var(--text-muted); font-size:13px;">{{ $users->total() }} total</span>
    </div>
    <div class="card-body">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Verified</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td style="color:var(--text-muted); font-size:12px;">{{ $user->id }}</td>
                    <td>
                        <div style="display:flex; align-items:center; gap:10px;">
                            <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,#6366f1,#a855f7);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:13px;flex-shrink:0;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <span style="font-weight:600;">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td style="color:var(--text-muted);">{{ $user->email }}</td>
                    <td>
                        @foreach($user->roles as $role)
                            <span class="badge {{ $role->name === 'Admin' ? 'badge-purple' : 'badge-blue' }}">
                                {{ $role->name }}
                            </span>
                        @endforeach
                    </td>
                    <td>
                        @if($user->email_verified_at)
                            <span class="badge badge-green">✔ Verified</span>
                        @else
                            <span class="badge badge-yellow">⏳ Pending</span>
                        @endif
                    </td>
                    <td style="color:var(--text-muted); font-size:13px;">{{ $user->created_at->format('M d, Y') }}</td>
                    <td>
                        <div style="display:flex; gap:8px;">
                            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline btn-sm">👁 View</a>
                            @if(auth()->id() !== $user->id)
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                  onsubmit="return confirm('Delete {{ addslashes($user->name) }}? This cannot be undone.');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">🗑</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center; padding:40px; color:var(--text-muted);">No users found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
    <div class="pagination-wrap">
        {{ $users->links() }}
    </div>
    @endif
</div>

@endsection
