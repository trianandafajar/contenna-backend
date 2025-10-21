<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar"
    data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="250px"
    data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
    <div id="kt_app_sidebar_wrapper" class="app-sidebar-wrapper hover-scroll-y my-5 my-lg-2" data-kt-scroll="true"
        data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-height="auto"
        data-kt-scroll-dependencies="#kt_app_header" data-kt-scroll-wrappers="#kt_app_sidebar_wrapper"
        data-kt-scroll-offset="5px">
        <div id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false"
            class="app-sidebar-menu-primary menu menu-column menu-rounded menu-sub-indention menu-state-bullet-primary px-6 mb-5">
            @auth
                <div class="menu-item">
                    <a class="menu-link {{ request()->is(['page/dashboard', 'page/dashboard/*']) ? 'active ' : '' }}"
                        href="{{ route('dashboard') }}">
                        <span class="menu-icon">
                            <i class="fas fa-home fs-2"></i>
                        </span>
                        <span class="menu-title">{{ __('sidebar.dashboard') }}</span>
                    </a>
                </div>
            @endauth

            @can(['blog-view'])
                <div class="menu-item">
                    <a class="menu-link {{ request()->is(['page/blog', 'page/blog/*']) ? 'active ' : '' }}"
                        href="{{ route('blog.index') }}">
                        <span class="menu-icon">
                            <i class="fas fa-book fs-2"></i>
                        </span>
                        <span class="menu-title">Blog</span>
                    </a>
                </div>
            @endcan
                <div class="menu-item">
                    <a class="menu-link {{ request()->is(['page/bookmark', 'page/bookmark/*']) ? 'active ' : '' }}"
                        href="{{ route('bookmark.index') }}">
                        <span class="menu-icon">
                            <i class="fas fa-bookmark fs-2"></i>
                        </span>
                        <span class="menu-title">Bookmark</span>
                    </a>
                </div>

                @canany(['users-view', 'roles-view', 'permission-view'])
                    <div class="menu-item menu-labels">
                        <div class="menu-content d-flex flex-stack fw-bold text-gray-600 text-uppercase fs-7">
                            <span class="menu-heading ps-1">Manage Control</span>
                        </div>
                    </div>
                @endcanany

                @can(['manage-blog-view'])
                <div class="menu-item">
                    <a class="menu-link {{ request()->is(['page/manage-blog', 'page/manage-blog/*']) ? 'active ' : '' }}"
                        href="{{ route('manage-blog.index') }}">
                        <span class="menu-icon">
                            <i class="fas fa-book fs-2"></i>
                        </span>
                        <span class="menu-title">Manage Blog</span>
                    </a>
                </div>
                @endcan

                @canany(['category-view'])
                    <div data-kt-menu-trigger="click"
                        class="menu-item menu-accordion {{ request()->is(['master/categories', 'master/categories/*', 'master/tags', 'master/tags/*']) ? 'here show' : '' }}">
                        @can('category-view')
                            <span class="menu-link">
                                <span class="menu-icon">
                                    <i
                                        class="ki-duotone ki-book-square fs-1 {{ request()->is(['master/categories', 'master/categories/*', 'master/tags', 'master/tags/*']) ? 'text-primary' : '' }}">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>
                                </span>
                                <span
                                    class="menu-title {{ request()->is(['master/categories', 'master/categories/*', 'master/tags', 'master/tags/*']) ? 'text-primary' : '' }}">Master</span>
                                <span class="menu-arrow"></span>
                            </span>
                        @endcan
                        <div class="menu-sub menu-sub-accordion">
                            @can('category-view')
                                <div class="menu-item">
                                    <a class="menu-link {{ request()->is(['master/categories', 'master/categories/*']) ? 'active ' : '' }}"
                                        href="{{ route('master.categories.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Categories</span>
                                    </a>
                                </div>
                            @endcan
                            @can('tag-view')
                                <div class="menu-item">
                                    <a class="menu-link {{ request()->is(['master/tags', 'master/tags/*']) ? 'active ' : '' }}"
                                        href="{{ route('master.tags.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Tag</span>
                                    </a>
                                </div>
                            @endcan
                        </div>
                    </div>
                @endcanany

                {{-- users, roles, permission --}}
                @canany(['users-view', 'roles-view', 'permission-view'])
                    <div data-kt-menu-trigger="click"
                        class="menu-item menu-accordion {{ request()->is(['resources/users', 'resources/users/*', 'resources/roles', 'resources/roles/*', 'resources/permissions', 'resources/permissions/*', 'resources/user-deleted/*', 'resources/user-deleted']) ? 'here show' : '' }}">
                        <span class="menu-link">
                            <span class="menu-icon">
                                <span class="menu-bullet">
                                    <i
                                        class="ki-duotone ki-people fs-1  {{ request()->is(['resources/users', 'resources/users/*', 'resources/roles', 'resources/roles/*', 'resources/permissions', 'resources/permissions/*']) ? 'text-primary' : '' }}">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                        <span class="path5"></span>
                                    </i>
                                </span>
                            </span>
                            <span
                                class="menu-title {{ request()->is(['resources/users', 'resources/users/*', 'resources/roles', 'resources/roles/*', 'resources/permissions', 'resources/permissions/*', 'resources/user-deleted', 'resources/user-deleted/*']) ? 'text-primary' : '' }}">Members</span>
                            <span class="menu-arrow"></span>
                        </span>

                        <div class="menu-sub menu-sub-accordion">
                            @can('users-view')
                                <div class="menu-item">
                                    <a class="menu-link {{ request()->is(['resources/users', 'resources/users/*', 'resources/user-deleted', 'resources/user-deleted/*']) ? 'active ' : '' }}"
                                        href="{{ route('resources.users.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Users</span>
                                    </a>
                                </div>
                            @endcan
                            @can('roles-list')
                                <div class="menu-item">
                                    <a class="menu-link {{ request()->is(['resources/roles', 'resources/roles/*']) ? 'active ' : '' }}"
                                        href="{{ route('resources.roles.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Roles</span>
                                    </a>
                                </div>
                            @endcan
                            @can('permission-view')
                                <div class="menu-item">
                                    <a class="menu-link {{ request()->is(['resources/permissions', 'resources/permissions/*']) ? 'active ' : '' }}"
                                        href="{{ route('resources.permissions.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Permission</span>
                                    </a>
                                </div>
                            @endcan
                        </div>
                    </div>
                @endcanany

                {{-- position --}}
                @canany(['position-view'])
                    <div data-kt-menu-trigger="click"
                        class="menu-item menu-accordion {{ request()->is(['master/category', 'master/category/*', 'position', 'position/*', 'master/business-type', 'master/business-type/*']) ? 'here show' : '' }}">
                        <span class="menu-link">
                            <span class="menu-icon">
                                <i
                                    class="ki-duotone ki-abstract-26 fs-1 {{ request()->is(['page/position', 'page/position/*', 'master/business-type', 'master/business-type/*']) ? 'text-primary' : '' }}">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </span>
                            <span
                                class="menu-title {{ request()->is(['page/position', 'page/position/*', 'master/business-type', 'master/business-type/*']) ? 'text-primary' : '' }}">Masters</span>
                            <span class="menu-arrow"></span>
                        </span>

                        <div class="menu-sub menu-sub-accordion">
                            <div class="menu-item">
                                <a class="menu-link {{ request()->is(['page/category', 'page/category/*']) ? 'active ' : '' }}"
                                    href="{{ route('master.position.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Category</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @endcanany

                {{-- setting-general, setting-smtp, config-view --}}
                @canany(['setting-general', 'setting-smtp', 'config-view'])
                    <div data-kt-menu-trigger="click"
                        class="menu-item menu-accordion {{ request()->is(['resources/setting/general', 'resources/setting/smtp', 'resources/setting/drive', 'resources/setting/config', 'resources/setting/config/*', 'resources/setting/register']) ? 'here show' : '' }}">
                        <span class="menu-link ">
                            <span class="menu-icon">
                                <i
                                    class="ki-duotone ki-setting-2 fs-1 {{ request()->is(['resources/setting/general', 'resources/setting/smtp', 'resources/setting/drive', 'resources/setting/config', 'resources/setting/config/*', 'resources/setting/register']) ? 'text-primary' : '' }}">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </span>
                            <span
                                class="menu-title {{ request()->is(['resources/setting/general', 'resources/setting/smtp', 'resources/setting/drive', 'resources/setting/config', 'resources/setting/config/*', 'resources/setting/register']) ? 'text-primary' : '' }}">Settings</span>
                            <span class="menu-arrow"></span>
                        </span>

                        <div class="menu-sub menu-sub-accordion">

                            @can('setting-register')
                                <div class="menu-item">
                                    <a class="menu-link {{ request()->is(['resources/setting/register']) ? 'active ' : '' }}"
                                        href="{{ route('resources.setting.register.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Register Settings</span>
                                    </a>
                                </div>
                            @endcan
                            @can('setting-general')
                                <div class="menu-item">
                                    <a class="menu-link {{ request()->is(['resources/setting/general']) ? 'active ' : '' }}"
                                        href="{{ route('resources.setting.general.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">General Settings</span>
                                    </a>
                                </div>
                            @endcan
                            @can('setting-smtp')
                                <div class="menu-item">
                                    <a class="menu-link {{ request()->is(['resources/setting/smtp']) ? 'active ' : '' }}"
                                        href="{{ route('resources.setting.smtp.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">SMTP Settings</span>
                                    </a>
                                </div>
                            @endcan
                            @can('config-view')
                                <div class="menu-item">
                                    <a class="menu-link {{ request()->is(['resources/setting/config', 'resources/setting/config/*']) ? 'active ' : '' }}"
                                        href="{{ route('resources.setting.config.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Config Settings</span>
                                    </a>
                                </div>
                            @endcan

                        </div>
                    </div>
                @endcanany

                {{-- feedback, activity, version --}}
                @canany(['feedback-view', 'activity-view', 'version-view'])
                    <div data-kt-menu-trigger="click"
                        class="menu-item menu-accordion {{ request()->is(['page/feedback', 'page/usd-to-idr', 'page/usd-to-idr/*', 'page/activity-log', 'page/log-version', 'page/log-version/*']) ? 'here show' : '' }}">
                        <span class="menu-link">
                            <span class="menu-icon">
                                <i
                                    class="ki-duotone ki-more-2 fs-1 {{ request()->is(['page/feedback', 'page/usd-to-idr', 'page/usd-to-idr/*', 'page/activity-log', 'page/log-version', 'page/log-version/*']) ? 'page/text-primary' : '' }}">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                </i>
                            </span>
                            <span
                                class="menu-title {{ request()->is(['page/feedback', 'page/feedback/*', 'page/usd-to-idr', 'page/usd-to-idr/*', 'page/activity-log', 'page/log-version', 'page/log-version/*']) ? 'text-primary' : '' }}">Other</span>
                            <span class="menu-arrow"></span>
                        </span>

                        <div class="menu-sub menu-sub-accordion">
                            @can('users-view')
                                <div class="menu-item">
                                    <a class="menu-link {{ request()->is(['page/feedback', 'page/feedback/*']) ? 'active ' : '' }}"
                                        href="{{ route('feedback.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Feedback</span>
                                    </a>
                                </div>
                            @endcan
                            @can('roles-list')
                                <div class="menu-item">
                                    <a class="menu-link {{ request()->is(['page/activity-log', 'page/activity-log/*']) ? 'active ' : '' }}"
                                        href="{{ route('activity-log.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Activity Logs</span>
                                    </a>
                                </div>
                            @endcan
                            {{-- @can('permission-view') --}}
                            <div class="menu-item">
                                <a class="menu-link {{ request()->is(['page/log-version', 'page/log-version/*']) ? 'active ' : '' }}"
                                    href="{{ route('log-version.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Version Logs</span>
                                </a>
                            </div>
                            {{-- @endcan --}}

                        </div>
                    </div>
                @endcanany
        </div>
    </div>
</div>
