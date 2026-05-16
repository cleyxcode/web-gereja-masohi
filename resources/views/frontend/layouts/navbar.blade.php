<nav x-data="{ mobileOpen: false, scrolled: false }"
     x-init="window.addEventListener('scroll', () => scrolled = window.scrollY > 10)"
     :class="scrolled ? 'bg-white/95 backdrop-blur-md shadow-lg shadow-black/5' : 'bg-white'"
     class="sticky top-0 z-50 w-full border-b border-gray-100/80 transition-all duration-300">

    <div class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-[68px]">

            {{-- ===== LOGO ===== --}}
            <a href="{{ route('home') }}" class="flex items-center gap-3 group shrink-0">
                <div class="relative flex items-center justify-center w-9 h-9">
                    <div class="absolute inset-0 bg-primary rounded-xl rotate-6 opacity-20 group-hover:rotate-12 transition-transform duration-300"></div>
                    <div class="relative bg-primary/10 rounded-xl w-full h-full flex items-center justify-center overflow-hidden">
                        <img src="{{ asset('images/logoupdate.png') }}" alt="Logo" class="w-7 h-7 object-contain">
                    </div>
                </div>
                <div class="flex flex-col leading-none">
                    <span class="text-[15px] font-black tracking-tight text-gray-900">Jemaat Masohi</span>
                    <span class="text-[10px] text-gray-400 font-medium tracking-wider uppercase">Sistem Informasi</span>
                </div>
            </a>

            {{-- ===== DESKTOP MENU ===== --}}
            <nav class="hidden lg:flex items-center gap-1">
                @php
                    // Menu untuk tamu (belum login): Beranda, Jadwal, Berita, Galeri
                    $guestNavItems = [
                        ['route' => 'home',         'label' => 'Beranda', 'icon' => 'home',           'match' => 'home'],
                        ['route' => 'jadwal.index', 'label' => 'Jadwal',  'icon' => 'calendar_month', 'match' => 'jadwal.*'],
                        ['route' => 'berita.index', 'label' => 'Berita',  'icon' => 'newspaper',      'match' => 'berita.*'],
                        ['route' => 'galeri.index', 'label' => 'Galeri',  'icon' => 'photo_library',  'match' => 'galeri.*'],
                    ];

                    // Menu setelah login: Pendaftaran, Keuangan, Kotak Saran
                    $authNavItems = [
                        ['route' => 'pendaftaran.index', 'label' => 'Pendaftaran', 'icon' => 'app_registration',  'match' => 'pendaftaran.*'],
                        ['route' => 'keuangan.index',    'label' => 'Keuangan',    'icon' => 'account_balance',   'match' => 'keuangan.*'],
                        ['route' => 'saran.create',      'label' => 'Kotak Saran', 'icon' => 'mail',              'match' => 'saran.*'],
                    ];
                @endphp

                @guest
                    {{-- Menu Tamu: Beranda, Jadwal, Berita, Galeri --}}
                    @foreach($guestNavItems as $item)
                        @php $active = request()->routeIs($item['match']); @endphp
                        <a href="{{ route($item['route']) }}"
                           class="relative flex items-center gap-1.5 px-3 py-2 rounded-lg text-[13.5px] font-medium transition-all duration-200 group
                                  {{ $active ? 'text-primary bg-primary/8' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                            <span class="material-symbols-outlined text-[18px] transition-transform duration-200 group-hover:scale-110"
                                  style="font-variation-settings:'FILL' {{ $active ? '1' : '0' }}">
                                {{ $item['icon'] }}
                            </span>
                            {{ $item['label'] }}
                            @if($active)
                                <span class="absolute bottom-0 left-1/2 -translate-x-1/2 w-4 h-0.5 bg-primary rounded-full"></span>
                            @endif
                        </a>
                    @endforeach
                @endguest

                @auth
                    {{-- Menu Setelah Login: Berita, Galeri, Pendaftaran, Keuangan, Kotak Saran --}}
                    @foreach($authNavItems as $item)
                        @php $active = request()->routeIs($item['match']); @endphp
                        <a href="{{ route($item['route']) }}"
                           class="relative flex items-center gap-1.5 px-3 py-2 rounded-lg text-[13.5px] font-medium transition-all duration-200 group
                                  {{ $active ? 'text-primary bg-primary/8' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                            <span class="material-symbols-outlined text-[18px] transition-transform duration-200 group-hover:scale-110"
                                  style="font-variation-settings:'FILL' {{ $active ? '1' : '0' }}">
                                {{ $item['icon'] }}
                            </span>
                            {{ $item['label'] }}
                            @if($active)
                                <span class="absolute bottom-0 left-1/2 -translate-x-1/2 w-4 h-0.5 bg-primary rounded-full"></span>
                            @endif
                        </a>
                    @endforeach
                @endauth

            </nav>

            {{-- ===== RIGHT ACTIONS ===== --}}
            <div class="hidden md:flex items-center gap-2">

                @auth
                    {{-- Notification Bell --}}
                    <div x-data="notificationManager" class="relative">
                        <button @click="open = !open" @click.away="open = false"
                                class="relative flex items-center justify-center w-9 h-9 rounded-xl text-gray-500 hover:text-gray-900 hover:bg-gray-50 transition-colors">
                            <span class="material-symbols-outlined text-[22px]">notifications</span>
                            <template x-if="unreadCount > 0">
                                <span class="absolute top-1.5 right-1.5 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white"></span>
                            </template>
                        </button>

                        {{-- Dropdown Notifications --}}
                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                             x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                             style="display: none;"
                             class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-xl shadow-black/5 border border-gray-100 overflow-hidden z-50">
                            <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                                <h3 class="text-sm font-bold text-gray-800">Notifikasi</h3>
                                @if(Auth::user()->unreadNotifications->count() > 0)
                                    <form action="{{ route('notifications.markAllRead') }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit" class="text-[11px] font-semibold text-primary hover:text-primary/80">Tandai semua dibaca</button>
                                    </form>
                                @endif
                            </div>
                            <div class="max-h-[300px] overflow-y-auto">
                                @forelse(Auth::user()->notifications()->take(10)->get() as $notification)
                                    <a href="{{ route('notifications.read', $notification->id) }}"
                                       class="block px-4 py-3 border-b border-gray-50 hover:bg-gray-50 transition-colors {{ is_null($notification->read_at) ? 'bg-primary/5' : '' }}">
                                        <p class="text-[13px] font-semibold text-gray-800 mb-0.5">{{ $notification->data['judul'] ?? 'Notifikasi' }}</p>
                                        <p class="text-[12px] text-gray-500 leading-tight mb-1">{{ $notification->data['pesan'] ?? '' }}</p>
                                        <p class="text-[10px] text-gray-400 font-medium">{{ $notification->created_at->diffForHumans() }}</p>
                                    </a>
                                @empty
                                    <div class="px-4 py-6 text-center">
                                        <span class="material-symbols-outlined text-[32px] text-gray-300 mb-2">notifications_off</span>
                                        <p class="text-xs text-gray-500 font-medium">Belum ada notifikasi</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                    {{-- Profile Button --}}
                    <a href="{{ route('profile') }}"
                       class="flex items-center gap-2.5 pl-2 pr-4 py-2 rounded-xl text-sm font-semibold text-gray-700
                              hover:bg-gray-50 border border-transparent hover:border-gray-200
                              transition-all duration-200 group">
                        @if(Auth::user()->avatar)
                            <img
                                src="{{ asset('storage/' . Auth::user()->avatar) }}"
                                alt="{{ Auth::user()->name }}"
                                class="w-7 h-7 rounded-lg object-cover shrink-0 ring-2 ring-white shadow-sm"/>
                        @else
                            <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-primary to-blue-400 flex items-center justify-center text-white text-xs font-black shrink-0 shadow-sm">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                        @endif
                        <div class="flex flex-col leading-none">
                            <span class="text-[12px] text-gray-400 font-normal">Halo,</span>
                            <span class="text-[13px] text-gray-800 font-semibold">{{ Str::limit(Auth::user()->name, 14) }}</span>
                        </div>
                        <span class="material-symbols-outlined text-[16px] text-gray-400 group-hover:text-gray-600 ml-1">expand_more</span>
                    </a>

                    {{-- Divider --}}
                    <div class="w-px h-6 bg-gray-200"></div>

                    {{-- Logout --}}
                    <button onclick="handleLogout()"
                            class="flex items-center gap-1.5 px-3 py-2 rounded-xl text-[13px] font-semibold
                                   text-red-500 hover:text-red-600 hover:bg-red-50
                                   border border-transparent hover:border-red-100
                                   transition-all duration-200 group">
                        <span class="material-symbols-outlined text-[18px] group-hover:translate-x-0.5 transition-transform">logout</span>
                        Keluar
                    </button>

                @else
                    {{-- Tombol Login & Register untuk tamu --}}
                    <a href="{{ route('login') }}"
                       class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-[13px] font-semibold
                              text-gray-600 hover:text-gray-900 hover:bg-gray-50
                              border border-gray-200 hover:border-gray-300
                              transition-all duration-200">
                        <span class="material-symbols-outlined text-[18px]">login</span>
                        Masuk
                    </a>
                    <a href="{{ route('register') }}"
                       class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-[13px] font-semibold
                              text-white bg-primary hover:bg-primary/90
                              transition-all duration-200 shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">person_add</span>
                        Daftar
                    </a>
                @endauth

            </div>

            {{-- ===== MOBILE BUTTON ===== --}}
            <button @click="mobileOpen = !mobileOpen"
                    class="lg:hidden flex items-center justify-center w-9 h-9 rounded-lg text-gray-600 hover:bg-gray-100 transition-colors">
                <span class="material-symbols-outlined" x-text="mobileOpen ? 'close' : 'menu'">menu</span>
            </button>
        </div>
    </div>

    {{-- ===== MOBILE MENU ===== --}}
    <div x-show="mobileOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="lg:hidden border-t border-gray-100 bg-white/98 backdrop-blur-md shadow-xl shadow-black/5">

        {{-- Mobile Nav Items --}}
        <div class="px-4 pt-3 pb-2 grid grid-cols-3 gap-2">

            @guest
                {{-- Menu Tamu (mobile): Beranda, Jadwal, Berita, Galeri --}}
                @foreach($guestNavItems as $item)
                    @php $active = request()->routeIs($item['match']); @endphp
                    <a href="{{ route($item['route']) }}"
                       class="flex flex-col items-center gap-1.5 px-2 py-3 rounded-xl text-center transition-all
                              {{ $active ? 'bg-primary/10 text-primary' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-800' }}">
                        <span class="material-symbols-outlined text-[22px]"
                              style="font-variation-settings:'FILL' {{ $active ? '1' : '0' }}">
                            {{ $item['icon'] }}
                        </span>
                        <span class="text-[11px] font-semibold leading-tight">{{ $item['label'] }}</span>
                    </a>
                @endforeach
            @endguest

            @auth
                {{-- Menu Setelah Login (mobile): Berita, Galeri, Pendaftaran, Keuangan, Kotak Saran --}}
                @foreach($authNavItems as $item)
                    @php $active = request()->routeIs($item['match']); @endphp
                    <a href="{{ route($item['route']) }}"
                       class="flex flex-col items-center gap-1.5 px-2 py-3 rounded-xl text-center transition-all
                              {{ $active ? 'bg-primary/10 text-primary' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-800' }}">
                        <span class="material-symbols-outlined text-[22px]"
                              style="font-variation-settings:'FILL' {{ $active ? '1' : '0' }}">
                            {{ $item['icon'] }}
                        </span>
                        <span class="text-[11px] font-semibold leading-tight">{{ $item['label'] }}</span>
                    </a>
                @endforeach
            @endauth

        </div>

        {{-- Mobile Profile & Logout --}}
        <div class="px-4 pb-4 pt-2 border-t border-gray-100 flex items-center justify-between gap-3">

            @auth
                <a href="{{ route('profile') }}"
                   class="flex items-center gap-3 flex-1 px-4 py-3 rounded-xl bg-gray-50 hover:bg-gray-100 transition-colors">
                    @if(Auth::user()->avatar)
                        <img
                            src="{{ asset('storage/' . Auth::user()->avatar) }}"
                            alt="{{ Auth::user()->name }}"
                            class="w-8 h-8 rounded-lg object-cover ring-2 ring-white shadow-sm"/>
                    @else
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-primary to-blue-400 flex items-center justify-center text-white text-sm font-black shadow-sm">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <p class="text-[11px] text-gray-400">Login sebagai</p>
                        <p class="text-sm font-bold text-gray-800">{{ Auth::user()->name }}</p>
                    </div>
                </a>
                <button onclick="handleLogout()"
                        class="flex items-center gap-2 px-4 py-3 rounded-xl bg-red-50 text-red-500 hover:bg-red-100 font-semibold text-sm transition-colors shrink-0">
                    <span class="material-symbols-outlined text-[20px]">logout</span>
                    Keluar
                </button>

            @else
                {{-- Tombol Login & Register mobile --}}
                <a href="{{ route('login') }}"
                   class="flex items-center justify-center gap-2 flex-1 px-4 py-3 rounded-xl bg-gray-50 hover:bg-gray-100 text-gray-700 font-semibold text-sm transition-colors">
                    <span class="material-symbols-outlined text-[20px]">login</span>
                    Masuk
                </a>
                <a href="{{ route('register') }}"
                   class="flex items-center justify-center gap-2 flex-1 px-4 py-3 rounded-xl bg-primary text-white hover:bg-primary/90 font-semibold text-sm transition-colors">
                    <span class="material-symbols-outlined text-[20px]">person_add</span>
                    Daftar
                </a>
            @endauth

        </div>
    </div>
</nav>

@push('scripts')
<script>
async function handleLogout() {
    if (!confirm('Apakah Anda yakin ingin keluar?')) return;
    try {
        const response = await fetch('{{ route("logout") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        const data = await response.json();
        if (response.ok) {
            showToast('Sampai jumpa! 👋', 'success');
            setTimeout(() => { window.location.href = data.redirect || '{{ route("login") }}'; }, 1000);
        }
    } catch (e) {
        showToast('Terjadi kesalahan', 'error');
    }
}

@auth
// Real-time Notification Polling & Browser Push
document.addEventListener('alpine:init', () => {
    Alpine.data('notificationManager', () => ({
        open: false,
        unreadCount: {{ Auth::user()->unreadNotifications->count() }},
        notifications: [],
        lastCheck: Date.now(),
        
        init() {
            // Minta izin Push Notification Desktop
            if ('Notification' in window && Notification.permission !== 'granted' && Notification.permission !== 'denied') {
                Notification.requestPermission();
            }

            // Polling setiap 10 detik
            setInterval(() => {
                this.fetchNotifications();
            }, 10000);
        },

        async fetchNotifications() {
            try {
                const res = await fetch('{{ route("notifications.fetch") }}');
                const data = await res.json();
                
                // Jika ada notif baru, tampilkan Push Notification di OS Desktop
                if (data.count > this.unreadCount) {
                    const newNotif = data.latest.find(n => n.read_at === null);
                    if (newNotif && 'Notification' in window && Notification.permission === 'granted') {
                        new Notification(newNotif.data.judul || "Gereja Bethesda", {
                            body: newNotif.data.pesan || "Anda memiliki notifikasi baru.",
                            icon: "{{ asset('images/logoupdate.png') }}"
                        });
                    }
                }
                
                this.unreadCount = data.count;
            } catch (err) {
                console.error("Gagal mengambil notifikasi", err);
            }
        }
    }));
});
@endauth
</script>
@endpush