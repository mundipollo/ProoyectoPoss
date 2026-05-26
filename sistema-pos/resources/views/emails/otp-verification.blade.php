<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Código de verificación — Poss Atelier</title>
</head>
<body style="margin:0;padding:0;background:#f4f4f5;font-family:'Segoe UI',Arial,sans-serif">
    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f5;padding:40px 16px">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0" style="max-width:520px;background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,.08)">

                    {{-- Header negro --}}
                    <tr>
                        <td style="background:#111827;padding:32px 40px;text-align:center">
                            <p style="margin:0;font-size:22px;font-weight:700;color:#fff;letter-spacing:.06em">POSS ATELIER</p>
                            <p style="margin:6px 0 0;font-size:12px;color:#9ca3af;letter-spacing:.12em;text-transform:uppercase">Verificación de cuenta</p>
                        </td>
                    </tr>

                    {{-- Cuerpo --}}
                    <tr>
                        <td style="padding:40px 40px 32px">
                            <p style="margin:0 0 8px;font-size:16px;color:#111827;font-weight:600">
                                Hola, {{ $userName }} 👋
                            </p>
                            <p style="margin:0 0 28px;font-size:14px;color:#6b7280;line-height:1.6">
                                Recibimos una solicitud para crear tu cuenta en <strong>Poss Atelier</strong>.
                                Usa el siguiente código para completar tu registro:
                            </p>

                            {{-- Código OTP --}}
                            <div style="text-align:center;margin:0 0 28px">
                                <div style="display:inline-block;background:#f9fafb;border:2px dashed #e5e7eb;border-radius:14px;padding:20px 40px">
                                    <p style="margin:0 0 4px;font-size:11px;color:#9ca3af;letter-spacing:.12em;text-transform:uppercase">Tu código es</p>
                                    <p style="margin:0;font-size:42px;font-weight:800;color:#111827;letter-spacing:.25em;font-family:monospace">{{ $otp }}</p>
                                </div>
                            </div>

                            {{-- Aviso de expiración --}}
                            <div style="background:#fefce8;border:1px solid #fde68a;border-radius:10px;padding:12px 16px;margin-bottom:24px">
                                <p style="margin:0;font-size:13px;color:#92400e">
                                    ⏱️ <strong>Este código expira en 10 minutos.</strong>
                                    Si no solicitaste una cuenta, puedes ignorar este mensaje.
                                </p>
                            </div>

                            <p style="margin:0;font-size:13px;color:#9ca3af">
                                Por seguridad, nunca compartas este código con nadie.
                            </p>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="background:#f9fafb;padding:20px 40px;border-top:1px solid #f3f4f6;text-align:center">
                            <p style="margin:0;font-size:11px;color:#9ca3af">
                                © {{ date('Y') }} Poss Atelier · Sistema de gestión textil
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
