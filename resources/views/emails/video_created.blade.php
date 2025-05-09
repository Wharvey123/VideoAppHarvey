<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Nou Vídeo Creat</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f7; color: #51545e; margin: 0; padding: 0;">
<div style="width: 100%; background-color: #f4f4f7; padding: 20px 0;">
    <div style="max-width: 600px; margin: auto; background-color: #ffffff; border: 1px solid #eaeaec; border-radius: 4px; overflow: hidden;">
        <div style="padding: 30px;">
            <h1 style="margin-top: 0; color: #333333;">Nou Vídeo Creat</h1>
            @isset($user)
                <p>Hola {{ $user->name }},</p>
            @endisset
            <p>S'ha publicat un nou vídeo amb el títol:</p>
            <p style="font-weight: bold; color: #333333;">{{ $video->title }}</p>

            <p>Si vols veure’n el detall o revisar-lo, fes clic al botó següent:</p>

            <p style="text-align: center;">
                <a href="{{ url('/video/' . $video->id) }}" style="display: inline-block; background-color: #3869d4; color: #ffffff !important; text-decoration: none; padding: 12px 24px; border-radius: 4px;">
                    Veure Vídeo
                </a>
            </p>

            <p>Gràcies per formar part de la nostra comunitat!</p>
        </div>
        <div style="padding: 20px; text-align: center; font-size: 12px; color: #888888;">
            <p>Si tens cap dubte, contacta amb nosaltres a <a href="mailto:support@exemple.com" style="color: #3869d4; text-decoration: none;">support@exemple.com</a>.</p>
        </div>
    </div>
</div>
</body>
</html>
