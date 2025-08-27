<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SidebarPermissionService
{
    public function getMenuItems(): array
    {
        $user = Auth::guard('web')->user();
        
        if (!$user) {
            return [];
        }

        $menuItems = [
            [
                'title' => 'Management',
                'icon' => 'fa-solid fa-bars-progress',
                'permission' => 'view_users',
                'active' => function() {
                    return request()->routeIs('users.*') || request()->routeIs('admins.*') || request()->routeIs('trainers.*') || request()->routeIs('services.*') || request()->routeIs('subscriptions.*') || request()->routeIs('invitations.*') || request()->routeIs('staff.*') || request()->routeIs('admin.contacts.*') ;
                },
                'subItems' => [
                    [
                        'title' => 'User Management',
                        'route' => 'users.index',
                        'permission' => 'view_users',
                        'active' => function() {
                            return request()->routeIs('users.*');
                        }
                    ],
                    [
                        'title' => 'Admin Management',
                        'route' => 'admins.index',
                        'permission' => 'view_admins',
                        'active' => function() {
                            return request()->routeIs('admins.*');
                        }
                    ],
                    [
                        'title' => 'Trainers Management',
                        'route' => 'trainers.index',
                        'permission' => 'view_trainers',
                        'active' => function() {
                            return request()->routeIs('trainers.*');
                        }
                    ],
                    [
                        'title' => 'Staff Management',
                        'route' => 'staff.index',
                        'permission' => 'view_staff',
                        'active' => function() {
                            return request()->routeIs('staff.*');
                        }
                    ],
                    [
                        'title' => 'Service Management',
                        'route' => 'services.index',
                        'permission' => 'view_services',
                        'active' => function() {
                            return request()->routeIs('services.*');
                        }
                    ],
                    [
                        'title' => 'Subscriptions Management',
                        'route' => 'subscriptions.index',
                        'permission' => 'view_subscriptions',
                        'active' => function() {
                            return request()->routeIs('subscriptions.*');
                        }
                    ],
                    [
                        'title' => 'Invitations Management',
                        'route' => 'invitations.index',
                        'permission' => 'view_invitations',
                        'active' => function() {
                            return request()->routeIs('invitations.*');
                        }
                    ],
                    [
                        'title' => 'Contact Messages',
                        'route' => 'admin.contacts.index',
                        'permission' => 'view_contacts',
                        'active' => function() {
                            return request()->routeIs('admin.contacts.*');
                        }
                    ],
                ]
            ],
            [
                'title' => 'Score Management',
                'icon' => 'fa-solid fa-star',
                'permission' => 'view_scores',
                'active' => function() {
                    return request()->routeIs('admin.score-dashboard') || request()->routeIs('admin.resources') || request()->routeIs('review-requests.*');
                },
                'subItems' => [
                    [
                        'title' => 'Score Dashboard',
                        'route' => 'admin.score-dashboard',
                        'permission' => 'view_scores',
                        'active' => function() {
                            return request()->routeIs('admin.score-dashboard');
                        }
                    ],
                    [
                        'title' => 'Resources & Documents',
                        'route' => 'admin.resources',
                        'permission' => 'view_resources',
                        'active' => function() {
                            return request()->routeIs('admin.resources');
                        }
                    ],
                    [
                        'title' => 'Review Requests',
                        'route' => 'review-requests.index',
                        'permission' => 'view_reviews_requests',
                        'active' => function() {
                            return request()->routeIs('review-requests.*');
                        }
                    ],
                ]
            ],
            [
                'title' => 'Memberships',
                'icon' => 'fa-solid fa-user-group',
                'permission' => 'view_memberships',
                'active' => function() {
                    return request()->routeIs('membership.*');
                },
                'route' => 'membership.index'
            ],
            [
                'title' => 'Features',
                'icon' => 'fa-solid fa-star-of-life',
                'permission' => 'view_features',
                'active' => function() {
                    return request()->routeIs('features.*');
                },
                'route' => 'features.index'
            ],
            [
                'title' => 'Classes',
                'icon' => 'fa-solid fa-calendar-days',
                'permission' => 'view_classes',
                'active' => function() {
                    return request()->routeIs('classes.*');
                },
                'route' => 'classes.index'
            ],
            [
                'title' => 'Financials',
                'icon' => 'fa-solid fa-money-bill',
                'permission' => 'view_financials',
                'active' => function() {
                    return request()->routeIs('payments.index') || request()->routeIs('offers.*') || request()->routeIs('admin.cash-payments.*');
                },
                'subItems' => [
                    [
                        'title' => 'Cash Payments',
                        'route' => 'admin.cash-payments.index',
                        'permission' => 'view_payments',
                        'active' => function() {
                            return request()->routeIs('admin.cash-payments.*');
                        }
                    ],
                    [
                        'title' => 'Payments',
                        'route' => 'payments.index',
                        'permission' => 'view_payments',
                        'active' => function() {
                            return request()->routeIs('payments.index');
                        }
                    ],
                    [
                        'title' => 'Offers',
                        'route' => 'offers.index',
                        'permission' => 'view_offers',
                        'active' => function() {
                            return request()->routeIs('offers.*');
                        }
                    ]
                ]
            ],
            [
                'title' => 'Gallery',
                'icon' => 'fa-solid fa-images',
                'permission' => 'view_gallery',
                'active' => function() {
                    return request()->routeIs('galleries.*');
                },
                'route' => 'galleries.index'
            ],
            [
                'title' => 'Site',
                'icon' => 'fa-solid fa-chart-simple',
                'permission' => 'view_site_settings',
                'active' => function() {
                    return request()->routeIs('site-settings.edit') || request()->routeIs('branches.*') || request()->routeIs('admin.deactivation.*');
                },
                'subItems' => [
                    [
                        'title' => 'Site Settings',
                        'route' => 'site-settings.edit',
                        'permission' => 'view_site_settings',
                        'active' => function() {
                            return request()->routeIs('site-settings.edit');
                        }
                    ],
                    [
                        'title' => 'Branches Management',
                        'route' => 'branches.index',
                        'permission' => 'view_branches',
                        'active' => function() {
                            return request()->routeIs('branches.*');
                        }
                    ],
                    [
                        'title' => 'Gym Deactivation',
                        'route' => 'admin.deactivation.index',
                        'permission' => 'manage_site_settings',
                        'active' => function() {
                            return request()->routeIs('admin.deactivation.*');
                        },
                        'show' => function() {
                            return Auth::user() && Auth::user()->hasRole('admin');
                        }
                    ],
                ]
            ],
        ];

        return $this->filterMenuItemsByPermissions($menuItems, $user);
    }

    private function filterMenuItemsByPermissions(array $menuItems, User $user): array
    {
        $filteredItems = [];

        foreach ($menuItems as $item) {
            // Check if user has permission for this menu item
            if ($this->hasPermission($user, $item['permission'] ?? null)) {
                $filteredItem = $item;

                // Filter sub-items if they exist
                if (isset($item['subItems'])) {
                    $filteredSubItems = [];
                    foreach ($item['subItems'] as $subItem) {
                        if ($this->hasPermission($user, $subItem['permission'] ?? null)) {
                            // Check if there's a show condition
                            if (isset($subItem['show']) && !$subItem['show']()) {
                                continue;
                            }
                            $filteredSubItems[] = $subItem;
                        }
                    }
                    
                    // Only show parent item if it has visible sub-items
                    if (!empty($filteredSubItems)) {
                        $filteredItem['subItems'] = $filteredSubItems;
                        $filteredItems[] = $filteredItem;
                    }
                } else {
                    $filteredItems[] = $filteredItem;
                }
            }
        }

        return $filteredItems;
    }

    private function hasPermission(User $user, ?string $permission): bool
    {
        // If no permission required, allow access
        if ($permission === null) {
            return true;
        }

        // Admin has all permissions
        if ($user->hasRole('admin')) {
            return true;
        }

        // Check specific permission
        return $user->hasPermissionTo($permission);
    }
}
