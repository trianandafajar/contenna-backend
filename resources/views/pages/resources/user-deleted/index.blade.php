<x-app-layout>
    @section('title', 'Manage Removed User')
    @if ($errors->any())
        <div id="is_invalid__"></div>
    @endif
    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar pb-2" style="background-color: #f6f6f6;}">
        <!--begin::Toolbar container-->
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
            <!--begin::Toolbar wrapper-->
            <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                <!--begin::Page title-->
                <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                    <!--begin::Title-->
                    <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">
                        {{ __('Manage removed User') }}
                    </h1>
                    <!--end::Title-->
                </div>
                <!--end::Page title-->
            </div>
            <!--end::Toolbar wrapper-->
        </div>
        <!--end::Toolbar container-->
    </div>
    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid" style="background-color: #f6f6f6;}">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-fluid"
            style="padding-left: 0px!important; padding-right: 0px!important">
            <!--begin::Card-->
            <div class="card">
                <!--begin::Card header-->
                <div class="card-header border-0 pt-6">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <!--begin::Search-->
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                            <form action="{{ route('resources.user-deleted.index') }}" method="GET">
                                <input type="text" id="user-search" name="search" data-kt-user-table-filter="search"
                                    class="form-control form-control-solid w-250px ps-13" placeholder="Search user"
                                    value="{{ request('search') }}" />
                            </form>
                        </div>
                        <!--end::Search-->
                    </div>
                    <!--begin::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar">
                        <!--begin::Toolbar-->
                        <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                            <a href="{{ route('resources.users.index') }}" class="btn btn-primary ms-3">Kembali</a>
                        </div>
                        <!--end::Toolbar-->
                    </div>
                    <!--end::Card toolbar-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body py-4">
                    <!--begin::Table-->
                    <div class="table-responsive">
                        <table id="users-table" class="table align-middle table-row-dashed fs-6 gy-5"
                            id="kt_table_users">
                            <thead>
                                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">

                                    <th class="min-w-125px">Name</th>
                                    <th class="min-w-125px">Role</th>
                                    <th class="min-w-125px">Deleted At</th>
                                    @canany(['users-edit', 'users-show', 'users-delete'])
                                        <th class="text-end min-w-100px">Actions</th>
                                    @endcanany
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-semibold">
                                {{-- @dd($users->toArray()) --}}
                                @foreach ($users as $user)
                                    <tr>
                                        <td class="d-flex align-items-center">
                                            <!--begin:: Avatar -->
                                            @if ($user->avatar)
                                                <div class="avatar__ rounded-circle me-3"
                                                    style="width: 50px; height: 50px; overflow: hidden">
                                                    <a href="{{ asset('media/avatars/' . $user->avatar) }}"
                                                        target="_blank">
                                                        <img src="{{ asset('media/avatars/' . $user->avatar) }}"
                                                            alt="User Profile"
                                                            style="width: 100%; height:100%; object-fit: cover;">
                                                    </a>
                                                </div>
                                            @else
                                                <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                                    <a href="#">
                                                        <div class="symbol-label fs-3 bg-danger text-light-danger">
                                                            {{ substr($user->name, 0, 1) }}</div>
                                                    </a>
                                                </div>
                                            @endif
                                            <!--end::Avatar-->
                                            <!--begin::User details-->
                                            <div class="d-flex flex-column">
                                                <a href="{{ route('resources.users.show', ['user' => $user->id]) }}"
                                                    class="text-gray-800 text-hover-primary mb-1">{{ $user->name }}</a>
                                                <span>{{ $user->email }}</span>
                                            </div>
                                            <!--begin::User details-->
                                        </td>
                                        <td>
                                            @if ($user->roles->count() > 0)
                                                <div class="mt-1">
                                                    @foreach ($user->roles as $role)
                                                        <span
                                                            class="badge badge-light-success fw-bold">{{ $role->name }}</span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($user->deleted_at)->format('d F Y') }}
                                        </td>

                                        @canany(['users-edit', 'users-show', 'users-delete'])
                                            <td class="text-end">
                                                <a href="#"
                                                    class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm"
                                                    data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                                    <i class="ki-outline ki-down fs-5 ms-1"></i></a>
                                                <!--begin::Menu-->
                                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                                    data-kt-menu="true">
                                                    <!--begin::Menu item-->
                                                    @can('users-edit')
                                                        <div class="menu-item px-3">
                                                            <a href="{{ route('resources.user-deleted.recovery', ['id' => $user->id]) }}"
                                                                class="menu-link px-3">
                                                                Recovery
                                                            </a>
                                                        </div>
                                                    @endcan
                                                    @can('users-show')
                                                        <div class="menu-item px-3">
                                                            <a href="{{ route('resources.user-deleted.show',  $user->id) }}"
                                                                class="menu-link px-3">Show</a>
                                                        </div>
                                                    @endcan
                                                    <x-delete-confirmation :id="$user->id" :route="route('resources.user-deleted.destroy', $user->id)"
                                                        :isConfirm=true />
                                                </div>
                                                <!--end::Menu-->
                                            </td>
                                        @endcanany
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!--end::Table-->
                    <div class="pagination float-end">
                        {{ $users->appends(request()->input())->links('vendor.pagination.bootstrap-4') }}
                    </div>

                </div>
                <!--end::Card body-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::Content container-->
    </div>
    <!--end::Content-->
</x-app-layout>
