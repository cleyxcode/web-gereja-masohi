@extends('frontend.layouts.app')

@section('title', 'Daftar Akun')

@section('content')
<div class="bg-gradient-mesh min-h-screen flex flex-col items-center justify-center p-4 py-10">
    <!-- Main Container -->
    <main class="w-full max-w-2xl">
        <!-- Register Card -->
        <div class="bg-white rounded-xl shadow-card w-full overflow-hidden border border-slate-100 transition-all duration-300">
            <!-- Header Section with Logo -->
            <div class="pt-10 pb-6 px-8 text-center flex flex-col items-center">
                <div class="w-20 h-20 rounded-2xl flex items-center justify-center mb-6 shadow-sm overflow-hidden">
                    <img src="{{ asset('images/logoupdate.png') }}" alt="Logo Gereja" class="w-full h-full object-contain">
                </div>
                <h1 class="text-2xl font-bold text-slate-900 mb-2 tracking-tight">Daftar Akun Jemaat</h1>
                <p class="text-slate-500 text-sm">Lengkapi data diri Anda. Akun akan aktif setelah disetujui admin.</p>
            </div>

            <!-- Form Section -->
            <form id="registerForm" class="px-8 pb-10 flex flex-col gap-5">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Nama Lengkap -->
                    <div class="flex flex-col gap-1.5 md:col-span-2">
                        <label class="text-sm font-medium text-slate-700" for="name">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <span class="material-symbols-outlined text-[20px]">person</span>
                            </div>
                            <input 
                                class="block w-full rounded-lg border border-slate-200 bg-slate-50 pl-11 pr-4 py-3 text-sm placeholder:text-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all duration-200" 
                                id="name" 
                                name="name" 
                                placeholder="Masukkan nama lengkap" 
                                required 
                                type="text"
                            />
                        </div>
                        <span class="text-xs text-red-500 hidden" id="name-error"></span>
                    </div>

                    <!-- Jenis Kelamin -->
                    <div class="flex flex-col gap-1.5">
                        <label class="text-sm font-medium text-slate-700" for="jenis_kelamin">
                            Jenis Kelamin <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <span class="material-symbols-outlined text-[20px]">wc</span>
                            </div>
                            <select 
                                class="block w-full rounded-lg border border-slate-200 bg-slate-50 pl-11 pr-4 py-3 text-sm text-slate-700 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all duration-200 appearance-none"
                                id="jenis_kelamin"
                                name="jenis_kelamin"
                                required
                            >
                                <option value="" disabled selected>Pilih jenis kelamin</option>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-slate-400">
                                <span class="material-symbols-outlined text-[18px]">expand_more</span>
                            </div>
                        </div>
                        <span class="text-xs text-red-500 hidden" id="jenis_kelamin-error"></span>
                    </div>

                    <!-- Sektor -->
                    <div class="flex flex-col gap-1.5">
                        <label class="text-sm font-medium text-slate-700" for="sektor">
                            Sektor <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <span class="material-symbols-outlined text-[20px]">location_city</span>
                            </div>
                            <input 
                                class="block w-full rounded-lg border border-slate-200 bg-slate-50 pl-11 pr-4 py-3 text-sm placeholder:text-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all duration-200" 
                                id="sektor" 
                                name="sektor" 
                                placeholder="Contoh: Sektor 1, Sektor Timur" 
                                required 
                                type="text"
                            />
                        </div>
                        <span class="text-xs text-red-500 hidden" id="sektor-error"></span>
                    </div>

                    <!-- Unit -->
                    <div class="flex flex-col gap-1.5 md:col-span-2">
                        <label class="text-sm font-medium text-slate-700" for="unit">
                            Unit <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <span class="material-symbols-outlined text-[20px]">groups</span>
                            </div>
                            <input 
                                class="block w-full rounded-lg border border-slate-200 bg-slate-50 pl-11 pr-4 py-3 text-sm placeholder:text-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all duration-200" 
                                id="unit" 
                                name="unit" 
                                placeholder="Contoh: Pemuda, Ibu-Ibu, Bapak-Bapak" 
                                required 
                                type="text"
                            />
                        </div>
                        <span class="text-xs text-red-500 hidden" id="unit-error"></span>
                    </div>

                    <!-- Email -->
                    <div class="flex flex-col gap-1.5 md:col-span-2">
                        <label class="text-sm font-medium text-slate-700" for="email">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <span class="material-symbols-outlined text-[20px]">mail</span>
                            </div>
                            <input 
                                class="block w-full rounded-lg border border-slate-200 bg-slate-50 pl-11 pr-4 py-3 text-sm placeholder:text-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all duration-200" 
                                id="email" 
                                name="email" 
                                placeholder="nama@gmail.com" 
                                required 
                                type="email"
                            />
                        </div>
                        <span class="text-xs text-red-500 hidden" id="email-error"></span>
                    </div>

                    <!-- Password -->
                    <div class="flex flex-col gap-1.5">
                        <label class="text-sm font-medium text-slate-700" for="password">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <span class="material-symbols-outlined text-[20px]">lock</span>
                            </div>
                            <input 
                                class="block w-full rounded-lg border border-slate-200 bg-slate-50 pl-11 pr-10 py-3 text-sm placeholder:text-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all duration-200" 
                                id="password" 
                                name="password" 
                                placeholder="Minimal 8 karakter" 
                                required 
                                type="password"
                            />
                            <button 
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer" 
                                type="button"
                                data-toggle-password
                            >
                                <span class="material-symbols-outlined text-[20px]">visibility</span>
                            </button>
                        </div>
                        <span class="text-xs text-red-500 hidden" id="password-error"></span>
                    </div>

                    <!-- Konfirmasi Password -->
                    <div class="flex flex-col gap-1.5">
                        <label class="text-sm font-medium text-slate-700" for="password_confirmation">
                            Konfirmasi Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <span class="material-symbols-outlined text-[20px]">lock</span>
                            </div>
                            <input 
                                class="block w-full rounded-lg border border-slate-200 bg-slate-50 pl-11 pr-10 py-3 text-sm placeholder:text-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all duration-200" 
                                id="password_confirmation" 
                                name="password_confirmation" 
                                placeholder="Ulangi password" 
                                required 
                                type="password"
                            />
                            <button 
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer" 
                                type="button"
                                data-toggle-password
                            >
                                <span class="material-symbols-outlined text-[20px]">visibility</span>
                            </button>
                        </div>
                        <span class="text-xs text-red-500 hidden" id="password_confirmation-error"></span>
                    </div>
                </div>

                <!-- Info Note -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg px-4 py-3 flex items-start gap-3">
                    <span class="material-symbols-outlined text-blue-500 text-[20px] mt-0.5 flex-shrink-0">info</span>
                    <p class="text-xs text-blue-700 leading-relaxed">
                        Setelah mendaftar, akun Anda akan <strong>menunggu persetujuan admin</strong>. 
                        Email konfirmasi akan dikirim ke Gmail Anda jika pendaftaran disetujui.
                    </p>
                </div>

                <!-- Submit Button -->
                <button 
                    id="submitBtn"
                    class="w-full bg-primary hover:bg-primary-hover text-white font-semibold py-3 px-4 rounded-lg shadow-soft hover:shadow-lg hover:shadow-primary/30 active:transform active:scale-[0.99] transition-all duration-200 flex items-center justify-center gap-2 mt-2" 
                    type="submit"
                >
                    <span>Kirim Pendaftaran</span>
                    <span class="material-symbols-outlined text-[20px]">send</span>
                </button>
            </form>

            <!-- Footer / Login Link -->
            <div class="bg-slate-50 py-4 text-center border-t border-slate-100">
                <p class="text-sm text-slate-600">
                    Sudah punya akun? 
                    <a class="text-primary font-medium hover:text-primary-hover hover:underline transition-colors ml-1" href="{{ route('login') }}">
                        Masuk disini
                    </a>
                </p>
            </div>
        </div>

        <!-- Additional Links -->
        <div class="mt-8 flex justify-center gap-6 text-xs text-slate-500">
            <a class="hover:text-slate-700 transition-colors" href="#">Bantuan</a>
            <a class="hover:text-slate-700 transition-colors" href="#">Privasi</a>
            <a class="hover:text-slate-700 transition-colors" href="#">Ketentuan</a>
        </div>
    </main>

    <!-- Background Decoration -->
    <div class="fixed bottom-0 left-0 w-full h-1/2 pointer-events-none -z-10 opacity-30 bg-cover bg-bottom"
        style="background-image: url('{{ asset('images/gereja.jpeg') }}');
               mask-image: linear-gradient(to top, black, transparent);">
    </div>
</div>

<!-- Success Modal (pending approval) -->
<div id="successModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full p-8 text-center">
        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-5">
            <span class="material-symbols-outlined text-green-500 text-[48px]">mark_email_read</span>
        </div>
        <h2 class="text-xl font-bold text-slate-900 mb-3">Pendaftaran Terkirim!</h2>
        <p class="text-slate-500 text-sm leading-relaxed mb-6">
            Data Anda telah diterima. Admin akan meninjau pendaftaran Anda. 
            Setelah disetujui, kami akan mengirimkan <strong>email konfirmasi</strong> ke Gmail Anda.
        </p>
        <div class="bg-amber-50 border border-amber-200 rounded-lg px-4 py-3 mb-6">
            <p class="text-xs text-amber-700">
                ⏳ Proses persetujuan biasanya memakan waktu 1–2 hari kerja.
            </p>
        </div>
        <a href="{{ route('login') }}" class="block w-full bg-primary text-white font-semibold py-3 rounded-lg hover:bg-primary-hover transition-colors">
            Kembali ke Halaman Login
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('registerForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const submitBtn = document.getElementById('submitBtn');
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);
    
    // Clear previous errors
    document.querySelectorAll('[id$="-error"]').forEach(el => {
        el.classList.add('hidden');
        el.textContent = '';
    });
    
    // Client-side validation
    let isValid = true;

    if (!data.name || data.name.trim() === '') {
        showFieldError('name', 'Nama lengkap wajib diisi');
        isValid = false;
    }

    if (!data.jenis_kelamin) {
        showFieldError('jenis_kelamin', 'Jenis kelamin wajib dipilih');
        isValid = false;
    }

    if (!data.sektor || data.sektor.trim() === '') {
        showFieldError('sektor', 'Sektor wajib diisi');
        isValid = false;
    }

    if (!data.unit || data.unit.trim() === '') {
        showFieldError('unit', 'Unit wajib diisi');
        isValid = false;
    }
    
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(data.email)) {
        showFieldError('email', 'Format email tidak valid');
        isValid = false;
    }
    
    if (!data.password || data.password.length < 8) {
        showFieldError('password', 'Password minimal 8 karakter');
        isValid = false;
    }
    
    if (data.password !== data.password_confirmation) {
        showFieldError('password_confirmation', 'Konfirmasi password tidak cocok');
        isValid = false;
    }
    
    if (!isValid) return;
    
    // Set loading state
    setButtonLoading(submitBtn, true);
    
    try {
        const response = await fetch('{{ route("register.post") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (response.ok && result.success) {
            // Tampilkan modal sukses (pending approval)
            document.getElementById('successModal').classList.remove('hidden');
        } else {
            if (result.errors) {
                Object.keys(result.errors).forEach(key => {
                    showFieldError(key, result.errors[key][0]);
                });
            }
            showToast(result.message || 'Pendaftaran gagal. Periksa kembali data Anda.', 'error');
            setButtonLoading(submitBtn, false);
        }
    } catch (error) {
        showToast('Terjadi kesalahan. Silakan coba lagi.', 'error');
        setButtonLoading(submitBtn, false);
    }
});

function showFieldError(field, message) {
    const el = document.getElementById(`${field}-error`);
    if (el) {
        el.textContent = message;
        el.classList.remove('hidden');
    }
}

function setButtonLoading(btn, loading) {
    if (loading) {
        btn.disabled = true;
        btn.innerHTML = `
            <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>Mengirim...</span>
        `;
    } else {
        btn.disabled = false;
        btn.innerHTML = `<span>Kirim Pendaftaran</span><span class="material-symbols-outlined text-[20px]">send</span>`;
    }
}

// Toggle password visibility
document.querySelectorAll('[data-toggle-password]').forEach(btn => {
    btn.addEventListener('click', function() {
        const input = this.closest('.relative').querySelector('input');
        const icon = this.querySelector('.material-symbols-outlined');
        if (input.type === 'password') {
            input.type = 'text';
            icon.textContent = 'visibility_off';
        } else {
            input.type = 'password';
            icon.textContent = 'visibility';
        }
    });
});
</script>
@endpush