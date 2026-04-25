<x-mail::message>
# {{ $isUpdate ? 'Update Berita' : 'Berita Baru' }}

**{{ $berita->judul }}**

{{ \Illuminate\Support\Str::limit(strip_tags($berita->isi), 150) }}

<x-mail::button :url="route('berita.show', $berita->id)">
Lihat Selengkapnya
</x-mail::button>

Tuhan Yesus Memberkati,<br>
Gereja GPM Masohi
</x-mail::message>
