<x-mail::message>
# {{ $isUpdate ? 'Update Jadwal Ibadah' : 'Jadwal Ibadah Baru' }}

Syalom, ada jadwal ibadah terbaru untuk Jemaat GPM Masohi.

**Tanggal:** {{ $jadwal->tanggal->isoFormat('dddd, D MMMM YYYY') }}  
**Waktu:** {{ \Carbon\Carbon::parse($jadwal->waktu)->format('H:i') }} WIB  
**Tempat:** {{ $jadwal->tempat }}  
**Petugas Ibadah:** {{ $jadwal->petugas_ibadah }}

<x-mail::button :url="route('jadwal.index')">
Lihat Semua Jadwal
</x-mail::button>

Tuhan Yesus Memberkati,<br>
Gereja GPM Masohi
</x-mail::message>
