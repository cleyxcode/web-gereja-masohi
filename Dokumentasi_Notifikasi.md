# Arsitektur & Dokumentasi Teknis Fitur Notifikasi
## Website Gereja Bethesda Masohi — Edisi Ultimate (Lengkap & Detail)

Dokumen ini disusun untuk memberikan pemahaman mendalam tentang seluruh sistem notifikasi, alur kerja kode, serta struktur folder yang digunakan dalam aplikasi. Dokumen ini mencakup penjelasan logika, potongan kode asli, dan panduan teknis bagi pemula.

---

## 1. Peta Folder Lengkap (Architecture Map)
Memahami struktur folder adalah kunci utama pengembang Laravel. Berikut adalah lokasi file-file yang saling berhubungan:

### Folder Backend (Logika)
*   **`app/Notifications/`**: Jantung dari sistem notifikasi. Berisi kelas yang menentukan pesan apa yang dikirim dan lewat jalur mana (Mail atau Database).
*   **`app/Mail/`**: Kelas khusus untuk merakit email dengan desain HTML (Mailable).
*   **`app/Jobs/`**: Antrean tugas latar belakang. Sangat penting untuk fitur "Email Massal" ke ratusan jemaat agar server tidak *timeout*.
*   **`app/Http/Controllers/Frontend/`**: Mengatur interaksi pengguna di halaman depan (misal: saat jemaat mengisi formulir saran atau pendaftaran).

### Folder Frontend (Tampilan)
*   **`resources/views/emails/`**: Berisi file `.blade.php` yang merupakan desain visual email yang diterima jemaat di inbox mereka.

### Folder Admin (Filament)
*   **`app/Filament/Resources/`**: Folder pusat kendali admin. Di sinilah logika "Trigger" berada (misal: tombol Setujui, tombol Kirim Berita).

---

## 2. Bedah Kode: Model User & Database

Sistem notifikasi Laravel membutuhkan dua hal dasar: Trait `Notifiable` dan Tabel `notifications`.

### A. Model User (`app/Models/User.php`)
Agar sistem tahu bahwa user bisa menerima notifikasi, kita harus menambahkan Trait `Notifiable`.
```php
namespace App\Models;

use Illuminate\Notifications\Notifiable; // <-- Import Trait
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable; // <-- Gunakan Trait di sini
    
    // ... kode lainnya
}
```

### B. Migrasi Tabel (`database/migrations/..._create_notifications_table.php`)
Laravel menyimpan riwayat notifikasi di database agar muncul di ikon lonceng Admin.
```php
Schema::create('notifications', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->string('type');      // Tipe notifikasi (misal: PendaftaranMasuk)
    $table->morphs('notifiable'); // User yang menerima
    $table->text('data');        // Data JSON (isi pesan)
    $table->timestamp('read_at')->nullable(); // Status dibaca
    $table->timestamps();
});
```

---

## 3. Fitur Utama: Pendaftaran & Persetujuan (ACC)

Ini adalah alur yang paling sering ditanyakan: Bagaimana pendaftaran jemaat diproses hingga di-ACC oleh admin?

### Langkah 1: Jemaat Mendaftar
File: `app/Http/Controllers/Frontend/PendaftaranController.php`
```php
public function store(Request $request) {
    // ... simpan data pendaftaran
    
    // Kirim notifikasi ke Admin
    $admin = User::where('role', 'admin')->first();
    if ($admin) {
        $admin->notify(new PendaftaranMasukNotification($pendaftaran));
    }
}
```

### Langkah 2: Notifikasi Masuk ke Admin
File: `app/Notifications/PendaftaranMasukNotification.php`
Di sini kita mengatur agar admin menerima di dua jalur:
```php
public function via(object $notifiable): array {
    return ['database', 'mail']; // Masuk ke Lonceng Web & Email Admin
}

public function toDatabase(object $notifiable): array {
    return [
        'title' => 'Pendaftaran Baru',
        'body' => 'Ada jemaat baru mendaftar atas nama ' . $this->pendaftaran->nama,
        'url' => '/admin/pendaftarans'
    ];
}
```

### Langkah 3: Admin Melakukan ACC
File: `app/Filament/Resources/PendaftaranResource.php`
```php
Tables\Actions\Action::make('approve')
    ->action(function (Pendaftaran $record) {
        // 1. Ubah status pendaftaran
        $record->update(['status' => 'disetujui']);
        
        // 2. Aktifkan akun user (Is Approved)
        if ($record->user) {
            $record->user->update(['is_approved' => true]);
            
            // 3. Kirim konfirmasi ke jemaat
            $record->user->notify(new AccountApprovedNotification());
        }
    })
```

---

## 4. Fitur Lanjutan: Email Massal (Jadwal & Berita)

Fitur ini menggunakan sistem **Queue (Antrean)** agar Admin tidak perlu menunggu saat mengirim email ke banyak orang.

### Alur Kerja Job:
File: `app/Jobs/SendJadwalEmailJob.php`
```php
public function handle(): void {
    // Ambil SEMUA jemaat yang sudah di-ACC
    $users = User::where('role', 'jemaat')->where('is_approved', true)->get();
    
    foreach ($users as $user) {
        // Kirim email satu-per-satu via server SMTP
        Mail::to($user->email)->send(new JadwalNotification($this->jadwal));
    }
}
```

---

## 5. Panduan Menjalankan Sistem (Wajib bagi Pemula)

Sistem notifikasi yang canggih ini tidak akan berjalan jika tidak dikonfigurasi dengan benar.

### A. Konfigurasi SMTP (Gmail)
Buka file `.env` dan pastikan bagian ini terisi (Gunakan App Password Gmail):
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=emailanda@gmail.com
MAIL_PASSWORD=abcd-efgh-ijkl-mnop
MAIL_ENCRYPTION=tls
```

### B. Menjalankan Queue Worker
Email tidak dikirim langsung oleh web, melainkan oleh sebuah proses latar belakang. Anda **HARUS** menjalankan perintah ini di Terminal:
```bash
php artisan queue:work
```
*Tips: Jika Anda mengubah kode di file Job, Anda harus mematikan dan menjalankan ulang perintah ini.*

---

## 6. Ringkasan Hubungan Folder & Fitur

| Fitur | File Pemicu (Trigger) | File Logika (Logic) | Output Akhir |
| :--- | :--- | :--- | :--- |
| **Pendaftaran** | `PendaftaranController` | `PendaftaranMasukNotification` | Notifikasi Lonceng & Email Admin |
| **ACC Admin** | `PendaftaranResource` | `AccountApprovedNotification` | Email Konfirmasi di Jemaat |
| **Jadwal Ibadah** | `CreateJadwalIbadah` | `SendJadwalEmailJob` | Email Massal ke seluruh Jemaat |
| **Saran/Kritik** | `SaranResource` | `SaranAdminNotification` | Email ke Admin / Balasan ke Jemaat |

---

**Penutup**: Sistem ini dibangun dengan prinsip *Clean Code* di mana setiap tugas (mengirim pesan, mendesain email, menjalankan antrean) dipisahkan ke dalam folder masing-masing agar mudah dikelola dan dikembangkan di masa depan.
