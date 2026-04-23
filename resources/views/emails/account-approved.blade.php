<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akun Disetujui — Gereja Bethesda</title>
</head>
<body style="margin:0;padding:0;background:#f1f5f9;font-family:'Segoe UI',Arial,sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="background:#f1f5f9;padding:40px 16px;">
    <tr>
        <td align="center">
            <table width="100%" cellpadding="0" cellspacing="0" style="max-width:560px;">

                {{-- Card --}}
                <tr>
                    <td style="background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.08);">

                        {{-- Header --}}
                        <table width="100%" cellpadding="0" cellspacing="0">
                            <tr>
                                <td style="background:linear-gradient(135deg,#1d4ed8 0%,#3b82f6 100%);padding:36px 32px;text-align:center;">
                                    <div style="width:72px;height:72px;background:rgba(255,255,255,0.15);border-radius:16px;display:inline-flex;align-items:center;justify-content:center;margin-bottom:16px;">
                                        <span style="font-size:36px;">⛪</span>
                                    </div>
                                    <h1 style="color:#ffffff;font-size:22px;font-weight:700;margin:0 0 6px;letter-spacing:-0.3px;">Gereja Bethesda</h1>
                                    <p style="color:rgba(255,255,255,0.8);font-size:14px;margin:0;">Sistem Informasi Jemaat</p>
                                </td>
                            </tr>
                        </table>

                        {{-- Success Badge --}}
                        <table width="100%" cellpadding="0" cellspacing="0">
                            <tr>
                                <td align="center" style="padding:32px 32px 0;">
                                    <div style="display:inline-block;background:#dcfce7;border:1px solid #86efac;border-radius:50px;padding:8px 20px;">
                                        <span style="color:#16a34a;font-size:14px;font-weight:600;">✅ &nbsp; Pendaftaran Disetujui</span>
                                    </div>
                                </td>
                            </tr>
                        </table>

                        {{-- Body --}}
                        <table width="100%" cellpadding="0" cellspacing="0">
                            <tr>
                                <td style="padding:24px 32px 36px;">
                                    <p style="font-size:18px;font-weight:700;color:#1e293b;margin:0 0 12px;">Selamat, {{ $user->name }}! 🎉</p>
                                    <p style="font-size:14px;color:#64748b;line-height:1.8;margin:0 0 24px;">
                                        Pendaftaran akun Anda di <strong>Sistem Informasi Gereja Bethesda</strong> telah
                                        <strong style="color:#16a34a;">disetujui oleh Admin</strong>. Anda sekarang dapat masuk
                                        ke sistem menggunakan email dan password yang Anda daftarkan.
                                    </p>

                                    {{-- Info Box --}}
                                    <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:28px;">
                                        <tr>
                                            <td style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:20px;">
                                                <p style="font-size:13px;font-weight:600;color:#475569;margin:0 0 12px;">📋 &nbsp; Detail Akun Anda:</p>
                                                <table width="100%" cellpadding="0" cellspacing="0">
                                                    <tr>
                                                        <td style="font-size:13px;color:#64748b;padding:4px 0;width:130px;">Nama Lengkap</td>
                                                        <td style="font-size:13px;color:#1e293b;font-weight:600;padding:4px 0;">{{ $user->name }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="font-size:13px;color:#64748b;padding:4px 0;">Email</td>
                                                        <td style="font-size:13px;color:#1e293b;font-weight:600;padding:4px 0;">{{ $user->email }}</td>
                                                    </tr>
                                                    @if($user->sektor)
                                                    <tr>
                                                        <td style="font-size:13px;color:#64748b;padding:4px 0;">Sektor</td>
                                                        <td style="font-size:13px;color:#1e293b;font-weight:600;padding:4px 0;">{{ $user->sektor }}</td>
                                                    </tr>
                                                    @endif
                                                    @if($user->unit)
                                                    <tr>
                                                        <td style="font-size:13px;color:#64748b;padding:4px 0;">Unit</td>
                                                        <td style="font-size:13px;color:#1e293b;font-weight:600;padding:4px 0;">{{ $user->unit }}</td>
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
                                                <a href="{{ url('/login') }}"
                                                    style="display:inline-block;background:linear-gradient(135deg,#1d4ed8 0%,#3b82f6 100%);color:#ffffff;text-decoration:none;font-weight:600;font-size:15px;padding:14px 40px;border-radius:10px;">
                                                    🔑 &nbsp; Masuk ke Sistem Sekarang
                                                </a>
                                            </td>
                                        </tr>
                                    </table>

                                    {{-- Divider --}}
                                    <hr style="border:none;border-top:1px solid #e2e8f0;margin:0 0 20px;">

                                    <p style="font-size:12px;color:#94a3b8;line-height:1.6;margin:0;">
                                        Jika Anda tidak merasa mendaftar di sistem ini, abaikan email ini atau
                                        hubungi admin gereja segera.
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
