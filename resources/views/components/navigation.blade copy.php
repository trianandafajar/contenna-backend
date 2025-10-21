<nav class="bg-white shadow-md">
    <div class="container flex items-center justify-between px-4 py-5 mx-auto">
        <div class="flex items-center space-x-4">
            <a class="w-auto h-10" href="{{ route('home') }}">
                @if (config('setting.general.app_logo') !== 'logo.png')
                    <img alt="Logo" src="{{ asset('media/logo/' . config('setting.general.app_logo')) }}"
                        class="h-12" />
                @else
                    <img alt="Professional Dashboard Logo" class="w-auto h-full"
                        src="{{ asset('assets/media/logos/logo.png') }}" width="240" />
                @endif
            </a>
        </div>

        <!-- Menu Tengah: Home & About -->
        <div class="hidden md:flex items-center space-x-8 justify-end flex-1">
            <a href="{{ route('home') }}"
                class="relative text-gray-700 hover:text-blue-600 transition duration-300 font-medium text-lg group">
                Home
                <span
                    class="absolute left-0 bottom-0 w-0 h-[2px] bg-blue-600 transition-all duration-300 group-hover:w-full"></span>
            </a>
            <a href="{{ route('about') }}"
                class="relative text-gray-700 hover:text-blue-600 transition duration-300 font-medium text-lg group">
                About
                <span
                    class="absolute left-0 bottom-0 w-0 h-[2px] bg-blue-600 transition-all duration-300 group-hover:w-full"></span>
            </a>
        </div>


        <div class="flex items-center space-x-4 ml-7">
            <!-- Jika User Belum Login -->
            @guest
                <a href="{{ route('login') }}"
                    class="px-6 py-2 text-white bg-blue-500 rounded-md hover:bg-blue-600 transition">
                    Login
                </a>
            @endguest

            <!-- Jika User Sudah Login -->
            @auth
                <div class="app-navbar relative ml-5 hidden md:block" id="kt_app_header_navbar">
                    <div class="app-navbar-item ms-2 ms-lg-6 me-lg-8 ms-n2 me-6 relative">
                        <!-- Avatar -->
                        <div id="avatar-container"
                            class="rounded-full w-10 h-10 flex items-center justify-center overflow-hidden bg-gray-200">
                            @if (Auth::user()->avatar)
                                <img src="{{ URL::asset('media/avatars/' . Auth::user()->avatar) }}" alt="Photo Profile"
                                    class="w-full h-full object-cover" />
                            @else
                                <div
                                    class="w-10 h-10 flex items-center justify-center bg-red-500 text-white font-bold text-xl ">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>

                    <!-- Dropdown Profile -->
                    <div id="profile-card"
                        class="absolute top-full right-0 mt-2 w-64 bg-white rounded-lg shadow-lg border p-4 z-50 transition-all duration-300 hidden">
                        <div class="flex items-center space-x-3">
                            <div class="flex flex-col">
                                <div class="font-bold text-lg flex items-center">
                                    {{ Auth::user()->name }}
                                </div>
                            </div>
                        </div>

                            <hr class="my-2">

                            <div class="space-y-2">
                                <a href="{{ route('account.profile.overview') }}"
                                    class="block text-gray-700 hover:text-blue-500 text-sm">
                                    {{ __('My Profile') }}
                                </a>
                                <a href="{{ route('account.profile.edit') }}"
                                    class="block text-gray-700 hover:text-blue-500 text-sm">
                                    {{ __('Account Settings') }}
                                </a>
                                <a href="{{ route('account.profile.password') }}"
                                    class="block text-gray-700 hover:text-blue-500 text-sm">
                                    {{ __('Change Password') }}
                                </a>
                                <a href="{{ route('dashboard') }}" class="block text-gray-700 hover:text-blue-500 text-sm">
                                    {{ __('Dashboard') }}
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <a href="#" onclick="event.preventDefault(); this.closest('form').submit();"
                                        class="block text-red-500 hover:text-red-700 text-sm">
                                        {{ __('Log Out') }}
                                    </a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endauth
        </div>

        <!-- Mobile Menu Button -->
        <div class="md:hidden">
            <button id="menu-toggle" class="text-gray-700 focus:outline-none">
                <i class="fas fa-bars text-2xl"></i>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden md:hidden bg-white shadow-md p-5 absolute w-full" style="z-index: 100;top:4rem">
        <a href="{{ route('home') }}" class="block text-gray-700 hover:text-blue-600 py-2">Home</a>
        <a href="{{ route('about') }}" class="block text-gray-700 hover:text-blue-600 py-2">About</a>

        @auth
            <hr class="my-2">
            <a href="{{ route('account.profile.overview') }}" class="block text-gray-700 hover:text-blue-500 py-2">My
                Profile</a>
            <a href="{{ route('account.profile.edit') }}" class="block text-gray-700 hover:text-blue-500 py-2">Account
                Settings</a>
            <a href="{{ route('account.profile.password') }}" class="block text-gray-700 hover:text-blue-500 py-2">Change
                Password</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <a href="#" onclick="event.preventDefault(); this.closest('form').submit();"
                    class="block text-red-500 hover:text-red-700 py-2">Log Out</a>
            </form>
        @endauth
    </div>
    </div>
</nav>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const menuButton = document.getElementById("menu-toggle");
        const mobileMenu = document.getElementById("mobile-menu");
        const profileToggle = document.getElementById("profile-toggle");
        const profileMenu = document.getElementById("profile-menu");

        // Toggle Mobile Menu
        menuButton.addEventListener("click", function(event) {
            event.stopPropagation(); // Mencegah event bubbling
            mobileMenu.classList.toggle("hidden");
        });

        // Menutup menu jika klik di luar menu
        document.addEventListener("click", function(event) {
            if (!menuButton.contains(event.target) && !mobileMenu.contains(event.target)) {
                mobileMenu.classList.add("hidden");
            }
        });

        // Toggle Profile Dropdown
        if (profileToggle) {
            profileToggle.addEventListener("click", function(event) {
                event.stopPropagation(); // Mencegah event bubbling
                profileMenu.classList.toggle("hidden");
            });

            // Menutup profile menu jika klik di luar dropdown
            document.addEventListener("click", function(event) {
                if (!profileToggle.contains(event.target) && !profileMenu.contains(event.target)) {
                    profileMenu.classList.add("hidden");
                }
            });
        }
    });
</script>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        const avatarContainer = document.getElementById("avatar-container");
        const profileCard = document.getElementById("profile-card");

        let timeout;

        if (avatarContainer) {
            avatarContainer.addEventListener("mouseover", function() {
                clearTimeout(timeout);
                profileCard.classList.remove("hidden");
            });

            avatarContainer.addEventListener("mouseleave", function() {
                timeout = setTimeout(() => {
                    profileCard.classList.add("hidden");
                }, 300);
            });

            profileCard.addEventListener("mouseover", function() {
                clearTimeout(timeout);
            });

            profileCard.addEventListener("mouseleave", function() {
                timeout = setTimeout(() => {
                    profileCard.classList.add("hidden");
                }, 300);
            });
        }
    });
</script>
