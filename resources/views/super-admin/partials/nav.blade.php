<nav class="sa-subnav mb-4">
    <div style="display:flex;align-items:center;gap:.5rem;flex-wrap:wrap;">
        <a href="{{ route('superadmin.dashboard') }}" class="sa-nav-link {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}">
            <span class="material-symbols-outlined">dashboard</span> Dashboard
        </a>
        <a href="{{ route('superadmin.users.index') }}" class="sa-nav-link {{ request()->routeIs('superadmin.users*') ? 'active' : '' }}">
            <span class="material-symbols-outlined">group</span> Usuarios
        </a>
        <a href="{{ route('superadmin.subscriptions.index') }}" class="sa-nav-link {{ request()->routeIs('superadmin.subscriptions*') ? 'active' : '' }}">
            <span class="material-symbols-outlined">workspace_premium</span> Suscripciones
        </a>
        <a href="{{ route('superadmin.payments.index') }}" class="sa-nav-link {{ request()->routeIs('superadmin.payments*') ? 'active' : '' }}">
            <span class="material-symbols-outlined">payments</span> Pagos
            @php $pending = \App\Models\PaymentOrder::where('status','pendiente')->count(); @endphp
            @if($pending > 0)<span class="sa-badge">{{ $pending }}</span>@endif
        </a>
        <a href="{{ route('superadmin.tenants.index') }}" class="sa-nav-link {{ request()->routeIs('superadmin.tenants*') ? 'active' : '' }}">
            <span class="material-symbols-outlined">business</span> Tenants
        </a>
        <a href="{{ route('superadmin.audit.index') }}" class="sa-nav-link {{ request()->routeIs('superadmin.audit*') ? 'active' : '' }}">
            <span class="material-symbols-outlined">history</span> Auditoría
        </a>
        <a href="{{ route('superadmin.settings.index') }}" class="sa-nav-link {{ request()->routeIs('superadmin.settings*') ? 'active' : '' }}">
            <span class="material-symbols-outlined">settings</span> Configuración
        </a>
    </div>
</nav>
<style>
    .sa-subnav { background: linear-gradient(135deg,#1a2648,#2d4278); border-radius: 1rem; padding: .65rem 1rem; }
    .sa-nav-link {
        display: inline-flex; align-items: center; gap: .3rem;
        color: rgba(255,255,255,.65); font-size: .82rem; font-weight: 600;
        text-decoration: none; padding: .42rem .85rem; border-radius: .6rem;
        transition: all .15s; white-space: nowrap;
    }
    .sa-nav-link .material-symbols-outlined { font-size: .95rem; }
    .sa-nav-link:hover { color: #fff; background: rgba(255,255,255,.12); }
    .sa-nav-link.active { color: #fff; background: linear-gradient(95deg,#3b82f6,#6d5efc); }
    .sa-badge { background: #ef4444; color: #fff; font-size: .65rem; font-weight: 800; border-radius: 20px; padding: .1rem .45rem; margin-left: .2rem; }
</style>
