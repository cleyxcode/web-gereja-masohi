<x-mail::message>
# Saran Baru Diterima

Syalom Admin,

Ada saran/masukan baru yang dikirimkan oleh jemaat atau pengunjung.

**Pengirim:** {{ $saran->user ? $saran->user->name : 'Anonim' }}  
**Tanggal:** {{ $saran->created_at->isoFormat('dddd, D MMMM YYYY H:mm') }}

**Isi Saran:**
> {{ $saran->isi_saran }}

<x-mail::button :url="url('/admin/sarans')">
Buka Panel Admin
</x-mail::button>

Tuhan Yesus Memberkati,<br>
Sistem {{ config('app.name') }}
</x-mail::message>
