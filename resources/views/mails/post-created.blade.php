<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px; }
        .container { background: white; padding: 30px; border-radius: 8px; max-width: 600px; margin: auto; }
        .header { background: #2d3748; color: white; padding: 20px; border-radius: 8px 8px 0 0; }
        .btn { display: inline-block; padding: 10px 20px; background: #2d3748; color: white; text-decoration: none; border-radius: 5px; margin-top: 15px; }
        .footer { text-align: center; color: #999; font-size: 12px; margin-top: 20px; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h2>✅ Yangi post yaratildi!</h2>
    </div>

    <div style="padding: 20px;">
        <p>Salom, <strong>{{ $post->user->name }}</strong>!</p>
        <p>Siz muvaffaqiyatli yangi post yaratdingiz.</p>

        <hr>

        <p><strong>Sarlavha:</strong> {{ $post->title }}</p>
        <p><strong>Kategoriya:</strong> {{ $post->category->name ?? 'Yo\'q' }}</p>
        <p><strong>Narxi:</strong> ${{ $post->price }}</p>
        <p><strong>Ish soati:</strong> {{ $post->duration }} hrs</p>
        <p><strong>Qisqacha:</strong> {{ $post->short_content }}</p>

        <a href="{{ url('/posts/' . $post->id) }}" class="btn">
            Postni ko'rish
        </a>
    </div>

    <div class="footer">
        <p>Clean Work © {{ date('Y') }}</p>
    </div>
</div>
</body>
</html>
