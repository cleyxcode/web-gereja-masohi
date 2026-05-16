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
                    {{-- ===== NOTIFICATION BELL ===== --}}
                    <div x-data="notificationManager" class="relative" @click.away="open = false">

                        {{-- Bell Button --}}
                        <button @click="open = !open"
                                class="relative flex items-center justify-center w-10 h-10 rounded-xl transition-all duration-200"
                                :class="open ? 'bg-primary/10 text-primary shadow-sm' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-800'">
                            <span class="material-symbols-outlined text-[22px]"
                                  :style="open ? 'font-variation-settings: FILL 1' : 'font-variation-settings: FILL 0'">
                                notifications
                            </span>
                            {{-- Number badge --}}
                            <template x-if="unreadCount > 0">
                                <span x-text="unreadCount > 9 ? '9+' : unreadCount"
                                      class="absolute -top-1 -right-1 min-w-[18px] h-[18px] px-1 bg-red-500 text-white text-[10px] font-black rounded-full flex items-center justify-center border-2 border-white leading-none">
                                </span>
                            </template>
                        </button>

                        {{-- Dropdown Panel --}}
                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                             x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                             style="display:none;"
                             class="absolute right-0 mt-3 w-[360px] bg-white rounded-2xl shadow-2xl shadow-black/10 border border-gray-100 z-50 overflow-hidden">

                            {{-- Header --}}
                            <div class="flex items-center justify-between px-4 py-3.5 border-b border-gray-100 bg-gradient-to-r from-slate-50 to-white">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[18px] text-primary" style="font-variation-settings:'FILL' 1">notifications_active</span>
                                    <h3 class="text-sm font-bold text-gray-900">Notifikasi</h3>
                                    <template x-if="unreadCount > 0">
                                        <span x-text="unreadCount + ' baru'"
                                              class="px-2 py-0.5 bg-red-50 text-red-600 text-[10px] font-bold rounded-full border border-red-100">
                                        </span>
                                    </template>
                                </div>
                                @if(Auth::user()->unreadNotifications->count() > 0)
                                    <form action="{{ route('notifications.markAllRead') }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit"
                                                class="flex items-center gap-1 text-[11px] font-semibold text-primary hover:text-primary/70 transition-colors group">
                                            <span class="material-symbols-outlined text-[13px]">done_all</span>
                                            Tandai semua dibaca
                                        </button>
                                    </form>
                                @endif
                            </div>

                            {{-- Notification List --}}
                            <div class="max-h-[380px] overflow-y-auto divide-y divide-gray-50">
                                @forelse(Auth::user()->notifications()->take(15)->get() as $notif)
                                    @php
                                        $isUnread = is_null($notif->read_at);
                                        $judul    = $notif->data['judul'] ?? 'Notifikasi';
                                        $pesan    = $notif->data['pesan'] ?? '';
                                        $isJadwal = isset($notif->data['jadwal_id']);
                                        $icon     = $isJadwal ? 'calendar_month' : 'newspaper';
                                        $iconBg   = $isJadwal ? 'bg-blue-50 text-blue-500' : 'bg-emerald-50 text-emerald-500';
                                    @endphp
                                    <a href="{{ route('notifications.read', $notif->id) }}"
                                       class="flex items-start gap-3 px-4 py-3.5 hover:bg-gray-50/80 transition-all duration-150 group relative {{ $isUnread ? 'bg-primary/[0.03]' : 'bg-white' }}">

                                        {{-- Unread left bar --}}
                                        @if($isUnread)
                                            <span class="absolute left-0 top-0 bottom-0 w-[3px] bg-primary rounded-r-full"></span>
                                        @endif

                                        {{-- Icon --}}
                                        <div class="flex-shrink-0 w-9 h-9 rounded-xl {{ $iconBg }} flex items-center justify-center mt-0.5 group-hover:scale-105 transition-transform duration-200">
                                            <span class="material-symbols-outlined text-[17px]" style="font-variation-settings:'FILL' 1">{{ $icon }}</span>
                                        </div>

                                        {{-- Content --}}
                                        <div class="flex-1 min-w-0">
                                            <p class="text-[12.5px] font-semibold leading-snug mb-0.5 line-clamp-2 {{ $isUnread ? 'text-gray-900' : 'text-gray-600' }}">
                                                {{ $judul }}
                                            </p>
                                            <p class="text-[11.5px] text-gray-500 leading-snug mb-1 line-clamp-2">{{ $pesan }}</p>
                                            <div class="flex items-center gap-1.5">
                                                <span class="material-symbols-outlined text-[11px] text-gray-300">schedule</span>
                                                <span class="text-[10.5px] text-gray-400 font-medium">{{ $notif->created_at->diffForHumans() }}</span>
                                                @if($isUnread)
                                                    <span class="ml-auto w-1.5 h-1.5 rounded-full bg-primary/70 flex-shrink-0"></span>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- Arrow --}}
                                        <span class="material-symbols-outlined text-[16px] text-gray-300 group-hover:text-primary group-hover:translate-x-0.5 transition-all duration-200 mt-2 flex-shrink-0">chevron_right</span>
                                    </a>
                                @empty
                                    <div class="flex flex-col items-center justify-center py-10 px-4 text-center">
                                        <div class="w-16 h-16 rounded-2xl bg-gray-50 border border-gray-100 flex items-center justify-center mb-3">
                                            <span class="material-symbols-outlined text-[30px] text-gray-300">notifications_off</span>
                                        </div>
                                        <p class="text-[13px] font-semibold text-gray-600 mb-1">Belum ada notifikasi</p>
                                        <p class="text-[11px] text-gray-400 leading-relaxed">Notifikasi berita & jadwal ibadah baru<br>akan muncul di sini.</p>
                                    </div>
                                @endforelse
                            </div>

                            {{-- Footer --}}
                            @if(Auth::user()->notifications()->count() > 0)
                                <div class="px-4 py-2.5 border-t border-gray-100 bg-gray-50/50 text-center">
                                    <span class="text-[11px] text-gray-400">Menampilkan 15 notifikasi terbaru</span>
                                </div>
                            @endif
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
// Helper to convert base64 to Uint8Array for VAPID keys
function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - base64String.length % 4) % 4);
    const base64 = (base64String + padding).replace(/\-/g, '+').replace(/_/g, '/');
    const rawData = window.atob(base64);
    const outputArray = new Uint8Array(rawData.length);
    for (let i = 0; i < rawData.length; ++i) {
        outputArray[i] = rawData.charCodeAt(i);
    }
    return outputArray;
}

// Background Push Notification via Service Worker
async function initServiceWorker() {
    if (!('serviceWorker' in navigator) || !('PushManager' in window)) return;
    
    try {
        const registration = await navigator.serviceWorker.register('/sw.js');
        
        // Cek izin notifikasi
        const permission = await Notification.requestPermission();
        if (permission !== 'granted') return;

        // Subscribe to push
        const vapidPublicKey = "{{ env('VAPID_PUBLIC_KEY') }}";
        const convertedVapidKey = urlBase64ToUint8Array(vapidPublicKey);

        let subscription = await registration.pushManager.getSubscription();
        if (!subscription) {
            subscription = await registration.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: convertedVapidKey
            });
        }

        // Kirim subscription ke backend
        await fetch('{{ route("push.subscribe") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(subscription)
        });

    } catch (e) {
        console.error('Service Worker / Push failed:', e);
    }
}

document.addEventListener('alpine:init', () => {
    Alpine.data('notificationManager', () => ({
        open: false,
        unreadCount: {{ Auth::user()->unreadNotifications->count() }},
        notifications: [],
        lastCheck: Date.now(),
        
        init() {
            // Minta izin Push Notification Desktop & Register Service Worker
            initServiceWorker();

            // Polling setiap 10 detik (untuk update UI navbar saja, tidak untuk popup desktop)
            setInterval(() => {
                this.fetchNotifications();
            }, 10000);
        },

        async fetchNotifications() {
            try {
                const res = await fetch('{{ route("notifications.fetch") }}');
                const data = await res.json();
                this.unreadCount = data.count;
                // Note: Popup desktop sekarang diurus 100% oleh Service Worker di belakang layar.
            } catch (err) {
                console.error("Gagal mengambil notifikasi", err);
            }
        }
    }));
});
@endauth
</script>
@endpush