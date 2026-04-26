<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$apiKey = env('GEMINI_API_KEY');
$response = Illuminate\Support\Facades\Http::get("https://generativelanguage.googleapis.com/v1beta/models?key={$apiKey}");
$data = json_decode($response->body(), true);
if (isset($data['models'])) {
    foreach ($data['models'] as $m) {
        if (isset($m['supportedGenerationMethods']) && in_array('generateContent', $m['supportedGenerationMethods'])) {
            echo $m['name'] . "\n";
        }
    }
}
