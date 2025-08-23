<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="225px" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">

    <div class="app-sidebar-logo px-6 justify-content-center" id="kt_app_sidebar_logo">

        <a href="{{ route('admin.dashboard') }}">
            <img alt="Logo" src="{{ $site->getFirstMediaUrl('gym_logo') }}" class="h-25px app-sidebar-logo-default" />
            <img alt="Logo" src="{{ $site->getFirstMediaUrl('gym_logo') }}" class="h-20px app-sidebar-logo-minimize" />
            <span class="text-white fs-2 fw-bold">{{ $site->gym_name }}</span>
        </a>

        <div id="kt_app_sidebar_toggle"
            class="app-sidebar-toggle btn btn-icon btn-shadow btn-sm btn-color-muted btn-active-color-primary h-30px w-30px position-absolute top-50 start-100 translate-middle rotate"
            data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body"
            data-kt-toggle-name="app-sidebar-minimize">
            <i class="ki-duotone ki-black-left-line fs-3 rotate-180">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
        </div>

    </div>

    <div class="app-sidebar-menu overflow-hidden flex-column-fluid">

        <div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper">

            <div id="kt_app_sidebar_menu_scroll" class="scroll-y my-5 mx-3" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer" data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px" data-kt-scroll-save-state="true">

                <div class="menu menu-column menu-rounded menu-sub-indention fw-semibold fs-6" id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false">

                    <div class="menu-item">

                        <a class="menu-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                            <span class="menu-icon">
                                <i class="ki-duotone ki-element-11 fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                </i>
                            </span>
                            <span class="menu-title">Dashboard</span>
                        </a>
                    </div>

                    @php
                        $menuItems = 
                        [
                            [
                                'title' => 'Management',
                                'icon' => 'fa-solid fa-bars-progress',
                                'active' => function() {
                                    return request()->routeIs('users.*') || request()->routeIs('admins.*') || request()->routeIs('trainers')  || request()->routeIs('services.*') ||  request()->routeIs('subscriptions.*');
                                },
                                'subItems' => 
                                [
                                    [
                                        'title' => 'User Management',
                                        'route' => 'users.index',
                                        'active' => function() {
                                            return request()->routeIs('users.*');
                                        }
                                    ],
                                    [
                                        'title' => 'Admin Management',
                                        'route' => 'admins.index',
                                        'active' => function() {
                                            return request()->routeIs('admins.*');
                                        }
                                    ],
                                    [
                                        'title' => 'Trainers Management',
                                        'route' => 'trainers',
                                        'active' => function() {
                                            return request()->routeIs('trainers');
                                        }
                                    ],
                                    [
                                        'title' => 'Service Management',
                                        'route' => 'services.index',
                                        'active' => function() {
                                            return request()->routeIs('services.*');
                                        }
                                    ],
                                    [
                                        'title' => 'Subscriptions Management',
                                        'route' => 'subscriptions.index',
                                        'active' => function() {
                                            return request()->routeIs('subscriptions.*');
                                        }
                                    ],
                                ]
                            ],
                            [
                                'title' => 'Score Management',
                                'icon' => 'fa-solid fa-star',
                                'active' => function() {
                                    return request()->routeIs('admin.score-dashboard') || request()->routeIs('admin.resources') || request()->routeIs('review-requests.*');
                                },
                                'subItems' => [
                                    [
                                        'title' => 'Score Dashboard',
                                        'route' => 'admin.score-dashboard',
                                        'active' => function() {
                                            return request()->routeIs('admin.score-dashboard');
                                        }
                                    ],
                                    [
                                        'title' => 'Resources & Documents',
                                        'route' => 'admin.resources',
                                        'active' => function() {
                                            return request()->routeIs('admin.resources');
                                        },
                                        'count' => $documentCount ?? 0
                                    ],
                                                                         [
                                         'title' => 'Review Requests',
                                         'route' => 'review-requests.index',
                                         'active' => function() {
                                             return request()->routeIs('review-requests.*');
                                         }
                                     ],
                                ]
                            ],
                            [
                                'title' => 'Memberships',
                                'icon' => 'fa-solid fa-user-group',
                                'active' => function() {
                                    return request()->routeIs('membership.*');
                                },
                                'route' => 'membership.index'
                            ],
                            [
                                'title' => 'Features',
                                'icon' => 'fa-solid fa-star-of-life',
                                'active' => function() {
                                    return request()->routeIs('features.*');
                                },
                                'route' => 'features.index'
                            ],
                            [
                                'title' => 'Classes',
                                'icon' => 'fa-solid fa-calendar-days',
                                'active' => function() {
                                    return request()->routeIs('classes.*');
                                },
                                'route' => 'classes.index'
                            ],
                            [
                                'title' => 'Financials',
                                'icon' => 'fa-solid fa-money-bill',
                                'active' => function() {
                                    return request()->routeIs('payments.index') || request()->routeIs('offers.*');
                                },
                                'subItems' =>
                                [
                                    [
                                        'title' => 'Payments',
                                        'route' => 'payments.index',
                                        'active' => function() {
                                            return request()->routeIs('payments.index');
                                        }
                                    ],
                                    [
                                        'title' => 'Offers',
                                        'route' => 'offers.index',
                                        'active' => function() {
                                            return request()->routeIs('offers.*');
                                        },
                                    ]
                                ]
                            ],
                            [
                                'title' => 'Gallery',
                                'icon' => 'fa-solid fa-images',
                                'active' => function() {
                                    return request()->routeIs('galleries.*');
                                },
                                'route' => 'galleries.index'
                            ],
                            [
                                'title' => 'Site',
                                'icon' => 'fa-solid fa-chart-simple',
                                'active' => function() {
                                    return request()->routeIs('site-settings.edit') || request()->routeIs('branches.*') || request()->routeIs('admin.deactivation.*');
                                },
                                'subItems' => [
                                    [
                                        'title' => 'Site Settings',
                                        'route' => 'site-settings.edit',
                                        'active' => function() {
                                            return request()->routeIs('site-settings.edit');
                                        }
                                    ],
                                    [
                                        'title' => 'Branches Management',
                                        'route' => 'branches.index',
                                        'active' => function() {
                                            return request()->routeIs('branches.*');
                                        }
                                    ],
                                    [
                                        'title' => 'Gym Deactivation',
                                        'route' => 'admin.deactivation.index',
                                        'active' => function() {
                                            return request()->routeIs('admin.deactivation.*');
                                        },
                                        'show' => function() {
                                            return auth()->user() && auth()->user()->isAdmin();
                                        }
                                    ],
                                ]
                            ],
                            
                        ];
                    @endphp

                    @foreach ($menuItems as $item)
                        @if (isset($item['subItems']))
                            <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ $item['active']() ? 'here show' : '' }}">
                                <span class="menu-link">
                                    <span class="menu-icon">
                                        <i class="{{ $item['icon'] }}"></i>
                                    </span>
                                    <span class="menu-title">{{ $item['title'] }}</span>
                                    <span class="menu-arrow"></span>
                                </span>

                                <div class="menu-sub menu-sub-accordion">
                                    @foreach ($item['subItems'] as $subItem)
                                        @if (!isset($subItem['show']) || $subItem['show']())
                                            <div class="menu-item">
                                                <a class="menu-link {{ $subItem['active']() ? 'active' : '' }}" href="{{ isset($subItem['type']) ? '#' : (is_array($subItem['route']) ? route($subItem['route'][0], $subItem['route'][1]) : route($subItem['route'])) }}">
                                                    @if (isset($subItem['type']) && $subItem['type'] == 'button')
                                                        <span class="menu-bullet">
                                                            <span class="bullet bullet-dot"></span>
                                                        </span>

                                                        <span
                                                            class="menu-title"
                                                            @isset($subItem['id'])
                                                                id="{{ $subItem['id'] }}"
                                                            @endisset
                                                        >
                                                            <span class="indicator-label">
                                                                {{ $subItem['title'] }}
                                                            </span>
                                                            <span class="indicator-progress">
                                                                {{ $subItem['title'] }} <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                            </span>
                                                        </span>
                                                    @else

                                                        <span class="menu-bullet">
                                                            <span class="bullet bullet-dot"></span>
                                                        </span>
                                                        <span
                                                            class="menu-title"
                                                            @isset($subItem['id'])
                                                                id="{{ $subItem['id'] }}"
                                                            @endisset
                                                        >{{ $subItem['title'] }}</span>
                                                        @if(isset($subItem['count']) && $subItem['count'] > 0)
                                                            <span class="badge badge-light-primary ms-2">{{ $subItem['count'] }}</span>
                                                        @endif
                                                    @endif
                                                </a>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="menu-item">
                                <a class="menu-link {{ $item['active']() ? 'active' : '' }}" href="{{ route($item['route']) }}">
                                    <span class="menu-icon">
                                        <i class="{{ $item['icon'] }}"></i>
                                    </span>
                                    <span class="menu-title">{{ $item['title'] }}</span>
                                </a>
                            </div>
                        @endif
                    @endforeach

                </div>
            </div>
        </div>
    </div>
</div>