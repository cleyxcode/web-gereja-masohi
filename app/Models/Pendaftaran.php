<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pendaftaran extends Model
{
    protected $table = 'pendaftaran';

    protected $fillable = [
        'user_id',
        'nama',
        'tempat_lahir',
        'tanggal_lahir',
        'status_wasmi',
        'tahun_lulus_wasmi',
        'file_sertifikat_wasmi',
        'file_akta_kelahiran',
        'nama_ayah',
        'nama_ibu',
        'tanggal_nikah_ortu',
        'nama_saksi_1',
        'nama_saksi_2',
        'asal_jemaat_sektor',
        'baptis_di_gereja',
        'nama_suami',
        'tempat_lahir_suami',
        'tanggal_lahir_suami',
        'alamat_suami',
        'pekerjaan_suami',
        'agama_suami',
        'nama_ayah_suami',
        'pekerjaan_ayah_suami',
        'alamat_ayah_suami',
        'nama_ibu_suami',
        'pekerjaan_ibu_suami',
        'alamat_ibu_suami',
        'nama_istri',
        'tempat_lahir_istri',
        'tanggal_lahir_istri',
        'alamat_istri',
        'pekerjaan_istri',
        'agama_istri',
        'nama_ayah_istri',
        'pekerjaan_ayah_istri',
        'alamat_ayah_istri',
        'nama_ibu_istri',
        'pekerjaan_ibu_istri',
        'alamat_ibu_istri',
        'file_surat_pernyataan_ortu',
        'file_surat_keterangan_lurah',
        'file_surat_pernyataan_mempelai',
        'file_ktp',
        'jenis',
        'tanggal_daftar',
        'foto',
        'catatan',
        'status',
    ];

    protected $casts = [
        'tanggal_daftar'       => 'date',
        'tanggal_lahir'        => 'date',
        'tanggal_nikah_ortu'   => 'date',
        'tanggal_lahir_suami'  => 'date',
        'tanggal_lahir_istri'  => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}