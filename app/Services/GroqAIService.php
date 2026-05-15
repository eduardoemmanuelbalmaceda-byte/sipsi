<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GroqAIService
{
    private string $apiKey;
    private string $apiUrl = 'https://api.groq.com/openai/v1/chat/completions';
    private string $model = 'llama-3.3-70b-versatile'; // Modelo gratuito y rápido

    public function __construct()
    {
        $this->apiKey = env('GROQ_API_KEY', '');
    }

    /**
     * Interpreta una consulta del usuario usando IA
     */
    public function interpretar(string $consulta, array $contexto = []): string
    {
        if (empty($this->apiKey)) {
            return "⚠️ La IA no está configurada. Agregá tu API key de Groq en el archivo .env";
        }

        try {
            $systemPrompt = $this->construirPromptSistema($contexto);
            
            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                ])
                ->post($this->apiUrl, [
                    'model' => $this->model,
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => $systemPrompt
                        ],
                        [
                            'role' => 'user',
                            'content' => $consulta
                        ]
                    ],
                    'temperature' => 0.7,
                    'max_tokens' => 500,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['choices'][0]['message']['content'] ?? 'No pude generar una respuesta.';
            }

            Log::error('Error en Groq API', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return "⚠️ Error al conectar con la IA. Intentá de nuevo.";

        } catch (\Exception $e) {
            Log::error('Excepción en GroqAIService', [
                'message' => $e->getMessage()
            ]);
            return "⚠️ Ocurrió un error al procesar tu consulta con IA.";
        }
    }

    /**
     * Construye el prompt del sistema con contexto del SIPSI
     */
    private function construirPromptSistema(array $contexto): string
    {
        $prompt = "Sos un asistente virtual del Sistema de Información Psiquiátrica Judicial (SIPSI) de Argentina.\n\n";
        $prompt .= "Tu función es ayudar al personal del sistema a consultar información sobre:\n";
        $prompt .= "- Oficios judiciales (documentos recibidos de juzgados)\n";
        $prompt .= "- Pacientes (personas evaluadas por orden judicial)\n";
        $prompt .= "- Turnos (citas médicas con profesionales)\n";
        $prompt .= "- Informes (reportes enviados a los juzgados)\n";
        $prompt .= "- Profesionales (psiquiatras y médicos)\n";
        $prompt .= "- Juzgados (instituciones judiciales)\n\n";

        // Agregar contexto estadístico si está disponible
        if (!empty($contexto)) {
            $prompt .= "CONTEXTO ACTUAL DEL SISTEMA:\n";
            foreach ($contexto as $key => $value) {
                $prompt .= "- {$key}: {$value}\n";
            }
            $prompt .= "\n";
        }

        $prompt .= "INSTRUCCIONES:\n";
        $prompt .= "1. Respondé en español argentino, de forma clara y profesional\n";
        $prompt .= "2. Si te preguntan sobre datos específicos que no tenés, sugerí que consulten la sección correspondiente del sistema\n";
        $prompt .= "3. Podés usar emojis para hacer las respuestas más amigables\n";
        $prompt .= "4. Mantené las respuestas concisas (máximo 3-4 párrafos)\n";
        $prompt .= "5. Si te preguntan algo fuera del ámbito del SIPSI, recordá amablemente que solo podés ayudar con temas del sistema\n";
        $prompt .= "6. Usá formato markdown para resaltar información importante (*negrita*)\n";

        return $prompt;
    }

    /**
     * Verifica si la API key está configurada
     */
    public function estaConfigurado(): bool
    {
        return !empty($this->apiKey);
    }
}
