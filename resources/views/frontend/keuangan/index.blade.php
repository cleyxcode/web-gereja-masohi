@extends('frontend.layouts.app')

@section('title', 'Laporan Keuangan')

@section('content')
<main class="flex-1 px-4 md:px-10 py-8 max-w-[1100px] mx-auto w-full">

    {{-- ═══ PAGE HEADER ═══════════════════════════════════════════════════ --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-slate-900">Laporan Keuangan</h1>
            <p class="text-slate-500 mt-1">Laporan keuangan resmi Gereja Bethesda Masohi.</p>
        </div>
        <button onclick="window.print()"
            class="flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 rounded-lg text-sm font-medium hover:bg-slate-50 transition-colors shadow-sm text-slate-700 print:hidden">
            <span class="material-symbols-outlined text-[20px]">print</span>
            <span>Cetak</span>
        </button>
    </div>

    {{-- ═══ STATISTIK RINGKASAN ═══════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8 print:hidden">
        <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm">
            <div class="flex items-center gap-3 mb-3">
                <div class="p-2 bg-green-50 rounded-lg text-green-600">
                    <span class="material-symbols-outlined text-xl">arrow_upward</span>
                </div>
                <span class="text-sm font-medium text-slate-500">Total Penerimaan</span>
            </div>
            <p class="text-2xl font-bold text-slate-900">Rp {{ number_format($totalPenerimaan, 0, ',', '.') }}</p>
        </div>

        <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm">
            <div class="flex items-center gap-3 mb-3">
                <div class="p-2 bg-red-50 rounded-lg text-red-600">
                    <span class="material-symbols-outlined text-xl">arrow_downward</span>
                </div>
                <span class="text-sm font-medium text-slate-500">Total Belanja</span>
            </div>
            <p class="text-2xl font-bold text-slate-900">Rp {{ number_format($totalBelanja, 0, ',', '.') }}</p>
        </div>

        <div class="bg-white rounded-xl p-5 border border-primary/20 shadow-sm ring-1 ring-primary/10">
            <div class="flex items-center gap-3 mb-3">
                <div class="p-2 bg-primary/10 rounded-lg text-primary">
                    <span class="material-symbols-outlined text-xl">account_balance</span>
                </div>
                <span class="text-sm font-medium text-slate-500">Total Saldo Akhir</span>
            </div>
            <p class="text-2xl font-bold text-slate-900">Rp {{ number_format($totalSaldoAkhir, 0, ',', '.') }}</p>
            <p class="text-xs text-slate-400 mt-1 italic">Terbilang: {{ $terbilangTotal }}</p>
        </div>
    </div>

    {{-- ═══ FILTER ═════════════════════════════════════════════════════════ --}}
    <form method="GET" action="{{ route('keuangan.index') }}"
        class="flex flex-col sm:flex-row gap-3 mb-6 print:hidden">
        <div class="relative">
            <select name="kategori"
                class="appearance-none bg-white border border-slate-200 text-slate-700 py-2 pl-3 pr-10 rounded-lg text-sm font-medium focus:outline-none focus:ring-2 focus:ring-primary/50">
                <option value="">Semua Kategori</option>
                <option value="murni" {{ request('kategori') == 'murni' ? 'selected' : '' }}>Saldo Murni</option>
                <option value="ukp" {{ request('kategori') == 'ukp' ? 'selected' : '' }}>Saldo UKP</option>
                <option value="khusus" {{ request('kategori') == 'khusus' ? 'selected' : '' }}>Dana Khusus</option>
            </select>
        </div>
        <div class="relative flex-1">
            <span class="absolute inset-y-0 left-3 flex items-center text-slate-400 pointer-events-none">
                <span class="material-symbols-outlined text-[18px]">search</span>
            </span>
            <input name="search" value="{{ request('search') }}" type="text" placeholder="Cari laporan..."
                class="w-full bg-white border border-slate-200 text-slate-900 text-sm rounded-lg focus:ring-primary focus:border-primary pl-9 pr-4 py-2"/>
        </div>
        <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg text-sm font-medium hover:bg-primary-dark transition-colors">
            Terapkan
        </button>
        @if(request()->hasAny(['kategori', 'search']))
        <a href="{{ route('keuangan.index') }}"
            class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg text-sm font-medium hover:bg-slate-200 transition-colors">
            Reset
        </a>
        @endif
    </form>

    {{-- ═══ DAFTAR LAPORAN ═════════════════════════════════════════════════ --}}
    @if($laporanList->count() > 0)
        @foreach($laporanList as $index => $laporan)
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm mb-6 overflow-hidden">

            {{-- Header Laporan --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-slate-50">
                <div class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center text-sm font-bold">
                        {{ $laporan->urutan }}
                    </span>
                    <div>
                        <h2 class="font-bold text-slate-800 text-base">{{ $laporan->judul }}</h2>
                        <p class="text-xs text-slate-400">
                            @php
                                $kategoriLabel = match($laporan->kategori) {
                                    'murni' => 'Saldo Murni',
                                    'ukp'   => 'Saldo UKP',
                                    default => 'Dana Khusus',
                                };
                            @endphp
                            {{ $kategoriLabel }}
                        </p>
                    </div>
                </div>
                @if($laporan->file_laporan_url)
                <a href="{{ $laporan->file_laporan_url }}" target="_blank"
                    class="flex items-center gap-1.5 px-3 py-1.5 bg-primary/10 text-primary rounded-lg text-xs font-medium hover:bg-primary/20 transition-colors print:hidden">
                    <span class="material-symbols-outlined text-[16px]">download</span>
                    File Laporan
                </a>
                @endif
            </div>

            {{-- Tabel Data Keuangan (format seperti gambar) --}}
            <div class="px-6 py-5">
                <table class="w-full text-sm">
                    <tbody>
                        {{-- Baris 1: Saldo Awal --}}
                        <tr class="border-b border-slate-100">
                            <td class="py-2.5 text-slate-700 w-10 font-medium">
                                {{ ($laporanList->currentPage() - 1) * $laporanList->perPage() + $index + 1 }}.
                            </td>
                            <td class="py-2.5 text-slate-700">
                                {{ $laporan->judul }} Per {{ $laporan->periode_awal->translatedFormat('d F Y') }}
                            </td>
                            <td class="py-2.5 text-right font-semibold text-slate-800 whitespace-nowrap">
                                Rp.&nbsp;{{ number_format($laporan->saldo_awal, 0, ',', '.') }}.
                            </td>
                        </tr>

                        {{-- Baris 2: Penerimaan --}}
                        <tr class="border-b border-slate-100">
                            <td></td>
                            <td class="py-2.5 text-slate-600 pl-4">
                                Penerimaan tanggal
                                {{ $laporan->periode_awal->translatedFormat('d') }}
                                s/d
                                {{ $laporan->periode_akhir->translatedFormat('d F Y') }}
                            </td>
                            <td class="py-2.5 text-right text-slate-700 whitespace-nowrap">
                                Rp.&nbsp;{{ number_format($laporan->total_penerimaan, 0, ',', '.') }}.
                            </td>
                        </tr>

                        {{-- Baris 3: Jumlah Penerimaan (computed) --}}
                        <tr class="border-b border-slate-200 bg-slate-50">
                            <td></td>
                            <td class="py-2.5 text-slate-700 font-medium pl-4">
                                Jumlah Penerimaan . . . . . . . . . . . . .
                            </td>
                            <td class="py-2.5 text-right font-bold text-slate-900 whitespace-nowrap">
                                Rp.&nbsp;{{ number_format($laporan->jumlah_penerimaan, 0, ',', '.') }}.
                            </td>
                        </tr>

                        {{-- Baris 4: Belanja --}}
                        <tr class="border-b border-slate-100">
                            <td></td>
                            <td class="py-2.5 text-slate-600 pl-4">
                                Belanja Tanggal
                                {{ $laporan->periode_awal->translatedFormat('d') }}
                                s/d Tanggal
                                {{ $laporan->periode_akhir->translatedFormat('d F Y') }}
                            </td>
                            <td class="py-2.5 text-right text-red-600 whitespace-nowrap">
                                @if($laporan->total_belanja == 0)
                                    Rp.&nbsp;0.-
                                @else
                                    Rp.&nbsp;{{ number_format($laporan->total_belanja, 0, ',', '.') }}.
                                @endif
                            </td>
                        </tr>

                        {{-- Baris Custom Tambahan --}}
                        @if(!empty($laporan->custom_fields))
                            @foreach($laporan->custom_fields as $cf)
                            <tr class="border-b border-slate-100">
                                <td></td>
                                <td class="py-2.5 pl-4 text-slate-600">
                                    {{ $cf['label'] ?? 'Data Tambahan' }}
                                </td>
                                <td class="py-2.5 text-right whitespace-nowrap {{ ($cf['tipe'] ?? 'tambah') === 'tambah' ? 'text-green-700' : 'text-red-600' }}">
                                    {{ ($cf['tipe'] ?? 'tambah') === 'tambah' ? '+' : '-' }}
                                    Rp.&nbsp;{{ number_format((float)($cf['jumlah'] ?? 0), 0, ',', '.') }}.
                                </td>
                            </tr>
                            @endforeach
                        @endif

                        {{-- Baris 5: Saldo Akhir (garis bawah dobel) --}}
                        <tr>
                            <td></td>
                            <td class="pt-3 pb-2 text-slate-800 font-semibold pl-4 border-t-2 border-slate-800">
                                {{ $laporan->judul }} S/d tanggal {{ $laporan->periode_akhir->translatedFormat('d F Y') }}
                            </td>
                            <td class="pt-3 pb-2 text-right font-bold text-slate-900 text-base whitespace-nowrap border-t-2 border-slate-800">
                                Rp.&nbsp;{{ number_format($laporan->saldo_akhir, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tbody>
                </table>

                {{-- Kotak Terbilang --}}
                <div class="mt-5 border-2 border-amber-400 rounded-xl p-4 bg-amber-50">
                    <p class="font-semibold text-slate-700 text-sm">
                        Terbilang &nbsp;:&nbsp;
                        <span class="font-normal italic">{{ $laporan->terbilang }}</span>
                    </p>
                </div>

                {{-- Keterangan Tambahan (jika ada) --}}
                @if($laporan->keterangan)
                <div class="mt-4 p-3 bg-slate-50 rounded-lg border border-slate-200 text-sm text-slate-600">
                    <span class="font-medium text-slate-700">Keterangan:</span>
                    {{ $laporan->keterangan }}
                </div>
                @endif
            </div>

        </div>
        @endforeach

        {{-- Pagination --}}
        <div class="mt-4 print:hidden">
            {{ $laporanList->withQueryString()->links() }}
        </div>

    @else
        <div class="bg-white border border-slate-200 rounded-xl p-16 text-center shadow-sm">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 mb-4">
                <span class="material-symbols-outlined text-4xl text-slate-400">receipt_long</span>
            </div>
            <h3 class="text-lg font-semibold text-slate-900 mb-2">Belum Ada Laporan Keuangan</h3>
            <p class="text-sm text-slate-500">Admin belum menambahkan laporan keuangan.</p>
        </div>
    @endif

</main>
@endsection

@push('styles')
<style>
@media print {
    body { background: white !important; }
    .print\:hidden { display: none !important; }
    nav, footer, header { display: none !important; }
}
</style>
@endpush