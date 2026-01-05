<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Verifikasi Email</title>
</head>
<body style="margin:0;padding:0;background:#ffffff;font-family:Arial,sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center" style="padding:40px 0">
                <table width="600" cellpadding="0" cellspacing="0" style="border-radius:10px;border:1px solid #eee;padding:30px">

                    <!-- Logo -->
                    <tr>
                        <td align="center">
                            <img src="{{ config('app.url') }}/images/event%20run1.png"
                                 alt="Ramadan Vaganza 2026"
                                 style="height:180px;margin-bottom:20px">
                        </td>
                    </tr>

                    <!-- Title -->
                    <tr>
                        <td align="center">
                            <h2 style="margin:0 0 10px;color:#111">
                                Verifikasi Email Kamu
                            </h2>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="color:#555;font-size:15px;line-height:1.7">
                            <p>Halo <strong>{{ $name }}</strong> 👋</p>

                            <p>
                                Terima kasih telah mendaftarkan akun di <strong>Ramadan Vaganza 2026</strong>.
                                Untuk melanjutkan, silakan verifikasi alamat email kamu dengan menekan tombol di bawah ini.
                            </p>

                            <p style="text-align:center;margin:30px 0">
                                <a href="{{ $url }}"
                                   style="
                                   background:#F58B1D;
                                   color:#fff;
                                   text-decoration:none;
                                   padding:14px 30px;
                                   border-radius:6px;
                                   font-weight:bold;
                                   display:inline-block;">
                                    Verifikasi Email
                                </a>
                            </p>

                            <p>
                                Jika kamu tidak merasa mendaftar, abaikan email ini.
                            </p>

                            <p style="margin-top:30px">
                                Salam hangat,<br>
                                <strong>Ramadan Vaganza 2026</strong>
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
