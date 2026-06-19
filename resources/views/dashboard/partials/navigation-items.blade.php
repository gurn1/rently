<div class="flex flex-col">
    <span class="uppercase text-[#5C6267] pl-4">Main</span>

    <a href="{{ route($rolePrefix . '.dashboard') }}"
    class="navigation-item {{ request()->routeIs( $rolePrefix .'.dashboard') ? 'navigation-item-active' : '' }}">
        <span class="material-symbols-outlined">dashboard</span> Dashboard
    </a>

    @role('tenant')
        <a href="{{ route('tenant.leases.index') }}"
        class="navigation-item {{ request()->routeIs('tenant.leases.*') ? 'navigation-item-active' : '' }}">
            <span class="material-symbols-outlined">contract</span> My Lease
        </a>
        <a href="{{ route('tenant.payments.index') }}"
        class="navigation-item {{ request()->routeIs('tenant.payments.*') ? 'navigation-item-active' : '' }}">
            <span class="material-symbols-outlined">payments</span> Payments
        </a>
        <a href="{{ route('tenant.documents.index') }}"
        class="navigation-item {{ request()->routeIs('tenant.documents.*') ? 'navigation-item-active' : '' }}">
            <span class="material-symbols-outlined">docs</span> Documents
        </a>
        <a href="{{ route('tenant.work-orders.index') }}"
        class="navigation-item {{ request()->routeIs('tenant.work-orders.*') ? 'navigation-item-active' : '' }}">
            <span class="material-symbols-outlined">construction</span> Work Orders
        </a>
        <a href="{{ route('tenant.messages.index') }}"
        class="navigation-item {{ request()->routeIs('tenant.messages.*') ? 'navigation-item-active' : '' }}">
            <span class="material-symbols-outlined">chat_bubble</span> Messages
        </a>
    @endrole

    @role('property_manager')
        <a href="{{ route('manager.properties.index') }}"
        class="navigation-item {{ request()->routeIs('manager.properties.*') ? 'navigation-item-active' : '' }}">
            <span class="material-symbols-outlined">house</span> Properties
        </a>
        <a href="{{ route('manager.users.index') }}"
        class="navigation-item {{ request()->routeIs('manager.users.*') ? 'navigation-item-active' : '' }}">
            <span class="material-symbols-outlined">group</span> Tenants
        <a href="{{ route('manager.leases.index') }}"
        class="navigation-item {{ request()->routeIs('manager.leases.*') ? 'navigation-item-active' : '' }}">
            <span class="material-symbols-outlined">contract</span> Leases
        </a>
        <a href="{{ route('manager.payments.index') }}"
            class="navigation-item {{ request()->routeIs('manager.payments.*') ? 'navigation-item-active' : '' }}">
            <span class="material-symbols-outlined">payments</span> Payments
        </a>
        <a href="{{ route('manager.documents.index') }}"
        class="navigation-item {{ request()->routeIs('manager.documents.*') ? 'navigation-item-active' : '' }}">
            <span class="material-symbols-outlined">docs</span> Documents
        </a>
        <a href="{{ route('manager.work-orders.index') }}"
        class="navigation-item {{ request()->routeIs('manager.work-orders.*') ? 'navigation-item-active' : '' }}">
            <span class="material-symbols-outlined">construction</span> Work Orders
        </a>
        <a href="{{ route('manager.messages.index') }}"
        class="navigation-item {{ request()->routeIs('manager.messages.*') ? 'navigation-item-active' : '' }}">
            <span class="material-symbols-outlined">chat_bubble</span> Messages
        </a>
    @endrole

    @role('admin')
        <a href="{{ route('admin.properties.index') }}"
        class="navigation-item {{ request()->routeIs('admin.properties.*') ? 'navigation-item-active' : '' }}">
            <span class="material-symbols-outlined">house</span> Properties
        </a>
        <a href="{{ route('admin.users.index') }}"
        class="navigation-item {{ request()->routeIs('admin.users.*') ? 'navigation-item-active' : '' }}">
            <span class="material-symbols-outlined">group</span> Users
        </a>
        <a href="{{ route('admin.leases.index') }}"
        class="navigation-item {{ request()->routeIs('admin.leases.*') ? 'navigation-item-active' : '' }}">
            <span class="material-symbols-outlined">contract</span> Leases
        </a>
        <a href="{{ route('admin.payments.index') }}"
        class="navigation-item {{ request()->routeIs('admin.payments.*') ? 'navigation-item-active' : '' }}">
            <span class="material-symbols-outlined">payments</span> Payments
        </a>
        <a href="{{ route('admin.documents.index') }}"
        class="navigation-item {{ request()->routeIs('admin.documents.*') ? 'navigation-item-active' : '' }}">
            <span class="material-symbols-outlined">docs</span> Documents
        </a>
        <a href="{{ route('admin.work-orders.index') }}"
        class="navigation-item {{ request()->routeIs('admin.work-orders.*') ? 'navigation-item-active' : '' }}">
            <span class="material-symbols-outlined">construction</span> Work Orders
        </a>
        <a href="{{ route('admin.settings.index') }}"
        class="navigation-item {{ request()->routeIs('admin.settings.*') ? 'navigation-item-active' : '' }}">
            <span class="material-symbols-outlined">settings</span> Settings
        </a>
    @endrole
</div>

<div class="flex flex-col mt-12">
    <span class="uppercase text-[#5C6267] pl-4">You</span>
    <a href="{{ route($rolePrefix . '.profile.edit') }}" 
    class="navigation-item {{ request()->routeIs($rolePrefix . '.profile.*') ? 'navigation-item-active' : '' }}">
        <span class="material-symbols-outlined">account_circle</span> Profile
    </a>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="navigation-item w-full">
            <span class="material-symbols-outlined">logout</span> Logout
        </button>
    </form>
</div>