<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px; }
        .container { background: white; padding: 30px; border-radius: 8px; max-width: 600px; margin: auto; }
        .header { background: #2d3748; color: white; padding: 20px; border-radius: 8px 8px 0 0; }
        .footer { text-align: center; color: #999; font-size: 12px; margin-top: 20px; }
        .label { color: #666; font-size: 12px; text-transform: uppercase; margin-bottom: 4px; }
        .value { margin-bottom: 16px; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h2>{{ __('Yangi kontakt xabari') }}</h2>
    </div>

    <div style="padding: 20px;">
        <div class="label">{{ __('Ism') }}</div>
        <div class="value">{{ $contactMessage->name }}</div>

        <div class="label">{{ __('Email') }}</div>
        <div class="value">{{ $contactMessage->email }}</div>

        <div class="label">{{ __('Xizmat turi') }}</div>
        <div class="value">{{ $contactMessage->service_type }}</div>

        <div class="label">{{ __('Xabar') }}</div>
        <div class="value">{!! nl2br(e($contactMessage->message)) !!}</div>

        <p style="color: #999; font-size: 12px;">
            {{ __('Yuborilgan vaqt:') }} {{ $contactMessage->created_at->format('d.m.Y H:i') }}
        </p>
    </div>

    <div class="footer">
        <p>Tozalash Web Sayt © {{ date('Y') }}</p>
    </div>
</div>
</body>
</html>
