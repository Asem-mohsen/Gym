<div class="app-navbar flex-shrink-0">					

    <div class="app-navbar-item ms-1 ms-md-4" id="kt_header_user_menu_toggle">

        <div class="cursor-pointer symbol symbol-35px" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
            <div style=" width: 38px; height: 38px; background-color: #e8e8e8; border-radius: 5px; text-align: center; display: flex; align-items: center; justify-content: center; ">
                <span style="color: #d51f28; font-size: 15px;">{{ auth()->user()->initials }}</span>
            </div>

        </div>

        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px" data-kt-menu="true">

            <div class="menu-item px-3">
                <div class="menu-content d-flex align-items-center px-3">

                    <div class="symbol symbol-50px me-5">
                        <div style=" width: 38px; height: 38px; background-color: #e8e8e8; border-radius: 5px; text-align: center; display: flex; align-items: center; justify-content: center; ">
                            <span style="color: #d51f28; font-size: 15px;">{{ auth()->user()->initials }}</span>
                        </div>
                    </div>

                    <div class="d-flex flex-column">
                        <div class="fw-bold d-flex align-items-center fs-5">{{ auth()->user()->name }}
                            <span class="badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2">Admin</span>
                        </div>
                        <a href="#" class="fw-semibold text-muted text-hover-primary fs-7">{{ auth()->user()->email }}</a>
                    </div>
                </div>
            </div>

            <div class="separator my-2"></div>

            <div class="menu-item px-5">
                <a href="" class="menu-link px-5">Account Details</a>
            </div>

            <div class="menu-item px-5">
                <a href="" class="menu-link px-5">Security settings</a>
            </div>

            <div class="menu-item px-5">
                <form method="POST" action="{{ route('auth.logout.current') }}">
                    @csrf
                    <a href="{{ route('auth.logout.current') }}" class="menu-link px-5"  onclick="event.preventDefault(); this.closest('form').submit();">
                        Sign Out
                    </a>
                </form>
            </div>
        </div>
    </div>

</div>