<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Baru — Gereja Bethesda</title>
</head>
<body style="margin:0;padding:0;background:#f1f5f9;font-family:'Segoe UI',Arial,sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="background:#f1f5f9;padding:40px 16px;">
    <tr>
        <td align="center">
            <table width="100%" cellpadding="0" cellspacing="0" style="max-width:580px;">

                {{-- Card --}}
                <tr>
                    <td style="background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.08);">

                        {{-- Header --}}
                        <table width="100%" cellpadding="0" cellspacing="0">
                            <tr>
                                @php
                                    $headerColor = match($pendaftaran->jenis) {
                                        'baptis' => 'linear-gradient(135deg,#0369a1 0%,#0ea5e9 100%)',
                                        'sidi'   => 'linear-gradient(135deg,#7c3aed 0%,#a78bfa 100%)',
                                        'nikah'  => 'linear-gradient(135deg,#be185d 0%,#f472b6 100%)',
                                        default  => 'linear-gradient(135deg,#1d4ed8 0%,#3b82f6 100%)',
                                    };
                                    $icon = match($pendaftaran->jenis) {
                                        'baptis' => '💧',
                                        'sidi'   => '🙏',
                                        'nikah'  => '💍',
                                        default  => '📋',
                                    };
                                @endphp
                                <td style="background:{{ $headerColor }};padding:36px 32px;text-align:center;">
                                    <div style="width:72px;height:72px;background:rgba(255,255,255,0.15);border-radius:16px;display:inline-block;line-height:72px;margin-bottom:16px;">
                                        <span style="font-size:36px;">⛪</span>
                                    </div>
                                    <h1 style="color:#ffffff;font-size:22px;font-weight:700;margin:0 0 6px;letter-spacing:-0.3px;">Gereja Bethesda</h1>
                                    <p style="color:rgba(255,255,255,0.85);font-size:14px;margin:0;">Notifikasi Pendaftaran Layanan Gereja</p>
                                </td>
                            </tr>
                        </table>

                        {{-- Alert Badge --}}
                        <table width="100%" cellpadding="0" cellspacing="0">
                            <tr>
                                <td align="center" style="padding:28px 32px 0;">
                                    @php
                                        $badgeStyle = match($pendaftaran->jenis) {
                                            'baptis' => 'background:#e0f2fe;border:1px solid #7dd3fc;color:#0369a1;',
                                            'sidi'   => 'background:#ede9fe;border:1px solid #c4b5fd;color:#7c3aed;',
                                            'nikah'  => 'background:#fce7f3;border:1px solid #f9a8d4;color:#be185d;',
                                            default  => 'background:#dbeafe;border:1px solid #93c5fd;color:#1d4ed8;',
                                        };
                                    @endphp
                                    <div style="display:inline-block;{{ $badgeStyle }}border-radius:50px;padding:8px 22px;">
                                        <span style="font-size:14px;font-weight:600;">{{ $icon }} &nbsp; Pendaftaran {{ $jenis }} Baru Masuk</span>
                                    </div>
                                </td>
                            </tr>
                        </table>

                        {{-- Body --}}
                        <table width="100%" cellpadding="0" cellspacing="0">
                            <tr>
                                <td style="padding:24px 32px 36px;">

                                    <p style="font-size:16px;font-weight:700;color:#1e293b;margin:0 0 8px;">Halo, Admin Gereja Bethesda 👋</p>
                                    <p style="font-size:14px;color:#64748b;line-height:1.8;margin:0 0 24px;">
                                        Ada pendaftaran <strong>{{ $jenis }}</strong> baru yang masuk dari jemaat dan memerlukan persetujuan Anda.
                                        Berikut adalah detail pendaftarannya:
                                    </p>

                                    {{-- Info Box --}}
                                    <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:28px;">
                                        <tr>
                                            <td style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:20px;">
                                                <p style="font-size:13px;font-weight:600;color:#475569;margin:0 0 14px;">📋 &nbsp; Detail Pendaftaran</p>
                                                <table width="100%" cellpadding="0" cellspacing="0">
                                                    <tr>
                                                        <td style="font-size:13px;color:#94a3b8;padding:5px 0;width:150px;vertical-align:top;">Jenis Layanan</td>
                                                        <td style="font-size:13px;color:#1e293b;font-weight:600;padding:5px 0;">{{ $icon }} {{ $jenis }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="font-size:13px;color:#94a3b8;padding:5px 0;vertical-align:top;">
                                                            @if($pendaftaran->jenis === 'baptis') Nama Anak
                                                            @elseif($pendaftaran->jenis === 'nikah') Mempelai
                                                            @else Nama Pendaftar
                                                            @endif
                                                        </td>
                                                        <td style="font-size:13px;color:#1e293b;font-weight:600;padding:5px 0;">{{ $nama }}</td>
                                                    </tr>
                                                    @if($pendaftaran->user)
                                                    <tr>
                                                        <td style="font-size:13px;color:#94a3b8;padding:5px 0;vertical-align:top;">Akun Jemaat</td>
                                                        <td style="font-size:13px;color:#1e293b;font-weight:600;padding:5px 0;">
                                                            {{ $pendaftaran->user->name }}<br>
                                                            <span style="font-weight:400;color:#64748b;">{{ $pendaftaran->user->email }}</span>
                                                        </td>
                                                    </tr>
                                                    @endif
                                                    @if($pendaftaran->tanggal_daftar)
                                                    <tr>
                                                        <td style="font-size:13px;color:#94a3b8;padding:5px 0;vertical-align:top;">Tgl Pelaksanaan</td>
                                                        <td style="font-size:13px;color:#1e293b;font-weight:600;padding:5px 0;">
                                                            {{ \Carbon\Carbon::parse($pendaftaran->tanggal_daftar)->translatedFormat('d F Y') }}
                                                        </td>
                                                    </tr>
                                                    @endif
                                                    <tr>
                                                        <td style="font-size:13px;color:#94a3b8;padding:5px 0;vertical-align:top;">Status</td>
                                                        <td style="padding:5px 0;">
                                                            <span style="background:#fef9c3;border:1px solid #fde047;color:#a16207;font-size:12px;font-weight:600;padding:3px 12px;border-radius:20px;">⏳ Pending — Menunggu Persetujuan</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="font-size:13px;color:#94a3b8;padding:5px 0;vertical-align:top;">Waktu Daftar</td>
                                                        <td style="font-size:13px;color:#1e293b;font-weight:600;padding:5px 0;">
                                                            {{ \Carbon\Carbon::parse($pendaftaran->created_at)->translatedFormat('d F Y, H:i') }} WIB
                                                        </td>
                                                    </tr>
                                                    @if($pendaftaran->catatan)
                                                    <tr>
                                                        <td style="font-size:13px;color:#94a3b8;padding:5px 0;vertical-align:top;">Catatan</td>
                                                        <td style="font-size:13px;color:#64748b;font-style:italic;padding:5px 0;">{{ $pendaftaran->catatan }}</td>
                                                    </tr>
                                                    @endif
                                                </table>
                                            </td>
                                        </tr>
                                    </table>

                                    {{-- CTA Button --}}
                                    <table width="100%" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td align="center" style="padding-bottom:24px;">
                                                <a href="{{ route('filament.admin.resources.pendaftarans.index') }}"
                                                    style="display:inline-block;background:linear-gradient(135deg,#1d4ed8 0%,#3b82f6 100%);color:#ffffff;text-decoration:none;font-weight:600;font-size:15px;padding:14px 44px;border-radius:10px;">
                                                    🔍 &nbsp; Tinjau Pendaftaran Sekarang
                                                </a>
                                            </td>
                                        </tr>
                                    </table>

                                    {{-- Divider --}}
                                    <hr style="border:none;border-top:1px solid #e2e8f0;margin:0 0 20px;">

                                    <p style="font-size:12px;color:#94a3b8;line-height:1.6;margin:0;">
                                        Email ini dikirim secara otomatis oleh sistem saat ada pendaftaran baru masuk.
                                        Mohon segera tindak lanjuti untuk memberikan kepastian kepada jemaat.
                                    </p>
                                </td>
                            </tr>
                        </table>

                        {{-- Footer --}}
                        <table width="100%" cellpadding="0" cellspacing="0">
                            <tr>
                                <td style="background:#f8fafc;padding:20px 32px;text-align:center;border-top:1px solid #e2e8f0;">
                                    <p style="font-size:12px;color:#94a3b8;margin:0;">
                                        Email ini dikirim oleh <strong>Gereja Bethesda</strong><br>
                                        &copy; {{ date('Y') }} Sistem Informasi Gereja Bethesda. Seluruh hak dilindungi.
                                    </p>
                                </td>
                            </tr>
                        </table>

                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

</body>
</html>
