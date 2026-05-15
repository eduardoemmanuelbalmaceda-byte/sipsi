<?php
/**
 * Script de prueba para verificar la conexión con Groq API
 * 
 * Uso: php test_groq_api.php
 */

require __DIR__.'/vendor/autoload.php';

use Illuminate\Support\Facades\Http;

// Cargar variables de entorno
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$apiKey = $_ENV['GROQ_API_KEY'] ?? '';

if (empty($apiKey)) {
    echo "❌ ERROR: No se encontró GROQ_API_KEY en el archivo .env\n";
    echo "\n";
    echo "Para configurar:\n";
    echo "1. Obtené tu API key en: https://console.groq.com/keys\n";
    echo "2. Agregala al archivo .env:\n";
    echo "   GROQ_API_KEY=tu_clave_aqui\n";
    exit(1);
}

echo "🔍 Probando conexión con Groq API...\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

try {
    $response = Http::timeout(30)
        ->withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])
        ->post('https://api.groq.com/openai/v1/chat/completions', [
            'model' => 'llama-3.3-70b-versatile',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'Sos un asistente amigable. Respondé en español argentino.'
                ],
                [
                    'role' => 'user',
                    'content' => '¿Qué es el SIPSI?'
                ]
            ],
            'temperature' => 0.7,
            'max_tokens' => 200,
        ]);

    if ($response->successful()) {
        $data = $response->json();
        $respuesta = $data['choices'][0]['message']['content'] ?? 'Sin respuesta';
        
        echo "✅ ¡Conexión exitosa!\n\n";
        echo "📝 Respuesta de la IA:\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo wordwrap($respuesta, 70) . "\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        
        echo "📊 Información de uso:\n";
        echo "   • Modelo: " . ($data['model'] ?? 'N/A') . "\n";
        echo "   • Tokens usados: " . ($data['usage']['total_tokens'] ?? 'N/A') . "\n";
        echo "   • Tiempo: " . ($data['usage']['total_time'] ?? 'N/A') . "s\n\n";
        
        echo "🎉 El chatbot con IA está listo para usar!\n";
        echo "   Andá a: http://localhost:8000/chatbot/page\n";
        
    } else {
        echo "❌ Error en la respuesta de Groq:\n";
        echo "   Status: " . $response->status() . "\n";
        echo "   Body: " . $response->body() . "\n";
        exit(1);
    }

} catch (Exception $e) {
    echo "❌ Excepción al conectar con Groq:\n";
    echo "   " . $e->getMessage() . "\n";
    exit(1);
}
