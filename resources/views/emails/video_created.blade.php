<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Nou Vídeo Creat</title>
    <style>
        /* Estils bàsics per a email */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f7;
            color: #51545e;
            margin: 0;
            padding: 0;
        }
        .email-wrapper {
            width: 100%;
            background-color: #f4f4f7;
            padding: 20px 0;
        }
        .email-content {
            max-width: 600px;
            margin: auto;
            background-color: #ffffff;
            border: 1px solid #eaeaec;
            border-radius: 4px;
            overflow: hidden;
        }
        .email-body {
            padding: 30px;
        }
        .email-body h1 {
            margin-top: 0;
            color: #333333;
        }
        .email-body p {
            line-height: 1.5;
            margin: 15px 0;
        }
        .button {
            display: inline-block;
            background-color: #3869d4;
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 4px;
        }
        .email-footer a {
            color: #3869d4;
            text-decoration: none;
        }
    </style>
</head>
<body>
<div class="email-wrapper">
    <div class="email-content">
        <div class="email-body">
            <h1>Nou Vídeo Creat</h1>
            <p>S'ha publicat un nou vídeo amb el títol:</p>
            <p style="font-weight: bold; color: #333333;">{{ $video->title }}</p>

            <p>Si vols veure’n el detall o revisar-lo, fes clic al botó següent:</p>

            <p style="text-align: center;">
                <a href="{{ url('/video/' . $video->id) }}" class="button">
                    Veure Vídeo
                </a>
            </p>

            <p>Gràcies per formar part de la nostra comunitat!</p>
        </div>
    </div>
</div>
</body>
</html>
