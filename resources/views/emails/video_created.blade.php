<div style="font-family: sans-serif;">
    <h1>Nou Vídeo Creat</h1>
    <p>S'ha creat un nou vídeo: <strong>{{ $video->title }}</strong></p>
    <p><a href="{{ url('/video/' . $video->id) }}">Veure vídeo</a></p>
</div>
