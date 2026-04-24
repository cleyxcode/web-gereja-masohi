<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use App\Models\User;
use App\Notifications\PendaftaranMasukNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PendaftaranController extends Controller
{
    public function index(Request $request)
    {
        $query = Pendaftaran::with('user')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('jenis', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%");
            });
        }

        $pendaftaranList = $query->paginate(10);

        return view('frontend.pendaftaran.index', compact('pendaftaranList'));
    }

    public function store(Request $request)
    {
        $rules = [
            'jenis'          => 'required|in:baptis,sidi,nikah',
            'catatan'        => 'nullable|string|max:500',
        ];

        $messages = [
            'jenis.required' => 'Jenis layanan wajib dipilih',
            'jenis.in'       => 'Jenis layanan tidak valid',
        ];

        if ($request->jenis === 'sidi') {
            $rules['nama'] = 'required|string|max:255';
            $rules['tempat_lahir'] = 'required|string|max:255';
            $rules['tanggal_lahir'] = 'required|date';
            $rules['status_wasmi'] = 'required|in:sudah,belum';
            $rules['tahun_lulus_wasmi'] = 'nullable|string|max:4';
            $rules['file_sertifikat_wasmi'] = 'required|file|mimes:jpg,jpeg,png,webp,pdf|max:2048';
            $rules['file_akta_kelahiran'] = 'required|file|mimes:jpg,jpeg,png,webp,pdf|max:2048';
            $rules['foto'] = 'required|image|mimes:jpg,jpeg,png,webp|max:2048';
            
            $messages['nama.required'] = 'Nama wajib diisi';
        } 
        elseif ($request->jenis === 'baptis') {
            $rules['nama'] = 'required|string|max:255'; // Nama Anak
            $rules['tempat_lahir'] = 'required|string|max:255';
            $rules['tanggal_lahir'] = 'required|date';
            $rules['nama_ayah'] = 'required|string|max:255';
            $rules['nama_ibu'] = 'required|string|max:255';
            $rules['tanggal_nikah_ortu'] = 'required|date';
            $rules['nama_saksi_1'] = 'required|string|max:255';
            $rules['nama_saksi_2'] = 'nullable|string|max:255';
            $rules['asal_jemaat_sektor'] = 'required|string|max:255';
            $rules['baptis_di_gereja'] = 'required|string|max:255';
            $rules['tanggal_daftar'] = 'required|date|after_or_equal:' . now()->addDays(14)->format('Y-m-d');
            
            $messages['nama.required'] = 'Nama anak wajib diisi';
        }
        elseif ($request->jenis === 'nikah') {
            $rules['nama'] = 'required|string|max:255'; // Use as generic title/name
            $rules['tanggal_daftar'] = 'required|date|after_or_equal:' . now()->addDays(14)->format('Y-m-d');
            
            // Suami
            $rules['nama_suami'] = 'required|string|max:255';
            $rules['tempat_lahir_suami'] = 'required|string|max:255';
            $rules['tanggal_lahir_suami'] = 'required|date';
            $rules['alamat_suami'] = 'required|string';
            $rules['pekerjaan_suami'] = 'required|string|max:255';
            $rules['agama_suami'] = 'required|string|max:255';
            
            // Ortu Suami
            $rules['nama_ayah_suami'] = 'required|string|max:255';
            $rules['pekerjaan_ayah_suami'] = 'required|string|max:255';
            $rules['alamat_ayah_suami'] = 'required|string';
            $rules['nama_ibu_suami'] = 'required|string|max:255';
            $rules['pekerjaan_ibu_suami'] = 'required|string|max:255';
            $rules['alamat_ibu_suami'] = 'required|string';

            // Istri
            $rules['nama_istri'] = 'required|string|max:255';
            $rules['tempat_lahir_istri'] = 'required|string|max:255';
            $rules['tanggal_lahir_istri'] = 'required|date';
            $rules['alamat_istri'] = 'required|string';
            $rules['pekerjaan_istri'] = 'required|string|max:255';
            $rules['agama_istri'] = 'required|string|max:255';
            
            // Ortu Istri
            $rules['nama_ayah_istri'] = 'required|string|max:255';
            $rules['pekerjaan_ayah_istri'] = 'required|string|max:255';
            $rules['alamat_ayah_istri'] = 'required|string';
            $rules['nama_ibu_istri'] = 'required|string|max:255';
            $rules['pekerjaan_ibu_istri'] = 'required|string|max:255';
            $rules['alamat_ibu_istri'] = 'required|string';

            // Uploads
            $rules['file_surat_pernyataan_ortu'] = 'required|file|mimes:jpg,jpeg,png,webp,pdf|max:2048';
            $rules['file_surat_keterangan_lurah'] = 'required|file|mimes:jpg,jpeg,png,webp,pdf|max:2048';
            $rules['file_surat_pernyataan_mempelai'] = 'required|file|mimes:jpg,jpeg,png,webp,pdf|max:2048';
            $rules['file_ktp'] = 'required|file|mimes:jpg,jpeg,png,webp,pdf|max:2048';
            $rules['foto'] = 'required|image|mimes:jpg,jpeg,png,webp|max:2048'; // Pas foto gandeng
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Terdapat kesalahan pengisian, periksa form.',
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            $data = $request->except(['_token', 'foto', 'file_sertifikat_wasmi', 'file_akta_kelahiran', 'file_surat_pernyataan_ortu', 'file_surat_keterangan_lurah', 'file_surat_pernyataan_mempelai', 'file_ktp']);
            $data['user_id'] = Auth::id();
            $data['status'] = 'pending';
            
            if ($request->jenis === 'sidi') {
                $data['tanggal_daftar'] = null; // No execution date needed
            }

            // Handle file uploads dynamically
            $fileFields = ['foto', 'file_sertifikat_wasmi', 'file_akta_kelahiran', 'file_surat_pernyataan_ortu', 'file_surat_keterangan_lurah', 'file_surat_pernyataan_mempelai', 'file_ktp'];
            
            foreach ($fileFields as $field) {
                if ($request->hasFile($field)) {
                    $folder = $field === 'foto' ? 'pendaftaran/foto' : 'pendaftaran/dokumen';
                    $data[$field] = $request->file($field)->store($folder, 'public');
                }
            }

            $pendaftaran = Pendaftaran::create($data);

            // Kirim notifikasi ke semua admin (in-app Filament + email)
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new PendaftaranMasukNotification($pendaftaran->load('user')));
            }

            return response()->json([
                'success' => true,
                'message' => 'Pendaftaran berhasil diajukan. Mohon tunggu konfirmasi dari majelis.',
                'data'    => $pendaftaran
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengajukan pendaftaran'
            ], 500);
        }
    }

    public function destroy($id)
    {
        $pendaftaran = Pendaftaran::where('user_id', Auth::id())->findOrFail($id);

        if ($pendaftaran->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya pendaftaran dengan status pending yang dapat dibatalkan'
            ], 403);
        }

        if ($pendaftaran->foto) {
            Storage::disk('public')->delete($pendaftaran->foto);
        }

        $pendaftaran->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pendaftaran berhasil dibatalkan'
        ]);
    }
}