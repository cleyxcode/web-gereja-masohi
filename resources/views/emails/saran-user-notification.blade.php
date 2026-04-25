<x-mail::message>
# Halo, {{ $saran->user->name }}

Terima kasih telah memberikan saran dan masukan untuk Gereja Bethesda Masohi.

Admin telah melihat saran Anda:
> {{ $saran->isi_saran }}

**Tanggapan Admin:**
@if($saran->balasan)
{{ $saran->balasan }}
@elseif($saran->status === 'ditindaklanjuti')
Saran Anda telah kami tindaklanjuti. Terima kasih atas partisipasinya.
@else
Terima kasih atas masukannya, akan kami tindaklanjuti.
@endif

<x-mail::button :url="url('/saran')">
Lihat Riwayat Saran Saya
</x-mail::button>

Tuhan Yesus Memberkati,<br>
Majelis Gereja Bethesda Masohi
</x-mail::message>
