<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
</head>

<body style="margin:0;padding:0;background:#f4f6f9;font-family:Arial, Helvetica, sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f6f9;padding:30px 0;">
        <tr>
            <td align="center">

                <table width="600" cellpadding="0" cellspacing="0" 
                       style="background:#ffffff;border-radius:10px;overflow:hidden;">

                    <!-- HEADER -->
                    <tr>
                        <td style="background:#0d6efd;color:#ffffff;padding:20px;text-align:center;">
                            <h2 style="margin:0;">visitMerauke.com</h2>
                        </td>
                    </tr>

                    <!-- CONTENT -->
                    <tr>
                        <td style="padding:30px;">

                            <p style="margin-top:0;">Halo <strong>{{ $user->name }}</strong>,</p>

                            <p>
                                Kami menerima permintaan untuk mereset password akun Anda.
                                Klik tombol di bawah ini untuk melanjutkan proses reset password.
                            </p>

                            <!-- BUTTON -->
                            <div style="text-align:center;margin:30px 0;">
                                <a href="{{ $url }}" 
                                   style="
                                   background:#0d6efd;
                                   color:#ffffff;
                                   padding:14px 28px;
                                   text-decoration:none;
                                   border-radius:6px;
                                   display:inline-block;
                                   font-weight:bold;">
                                    Reset Password
                                </a>
                            </div>

                            <p>
                                Link ini hanya berlaku selama <strong>5 menit</strong>.
                            </p>

                            <p>
                                Jika Anda tidak merasa melakukan permintaan ini,
                                silakan abaikan email ini.
                            </p>

                            <!-- LINK CADANGAN -->
                            <p style="font-size:12px;color:#666;margin-top:30px;">
                                Jika tombol tidak berfungsi, salin link berikut:
                                <br>
                                <a href="{{ $url }}">{{ $url }}</a>
                            </p>

                        </td>
                    </tr>

                    <!-- FOOTER -->
                    <tr>
                        <td style="background:#f8f9fa;padding:20px;text-align:center;font-size:12px;color:#888;">
                            © {{ date('Y') }} visitMerauke.com <br>
                            Merauke, Papua Selatan
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>