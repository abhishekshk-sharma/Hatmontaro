<?php

namespace App\Services;

use GuzzleHttp\Client;

class EmbeddingService
{
    protected $client;
    protected $apiKey;
    protected $model;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = env('OPENAI_API_KEY');
        $this->model = env('OPENAI_EMBEDDING_MODEL', 'text-embedding-3-small');
        $this->baseUrl = env('OPENAI_API_BASE', 'https://api.openai.com');
        $this->client = new Client(['base_uri' => $this->baseUrl]);
    }

    /**
     * Get embedding for given text using OpenAI embeddings endpoint.
     * Returns numeric array of floats.
     */
    public function embedText(string $text): array
    {
        if (empty($this->apiKey)) {
            throw new \RuntimeException('OPENAI_API_KEY not configured');
        }

        $endpoint = '/v1/embeddings';
        $payload = [
            'model' => $this->model,
            'input' => $text,
        ];

        $resp = $this->client->post($endpoint, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ],
            'json' => $payload,
            'timeout' => 15,
        ]);

        $data = json_decode((string)$resp->getBody(), true);
        if (empty($data['data'][0]['embedding'])) {
            throw new \RuntimeException('Empty embedding from provider');
        }
        return $data['data'][0]['embedding'];
    }

    public static function cosineSimilarity(array $a, array $b): float
    {
        $dot = 0.0; $na = 0.0; $nb = 0.0;
        $len = min(count($a), count($b));
        for ($i = 0; $i < $len; $i++) {
            $dot += $a[$i] * $b[$i];
            $na += $a[$i] * $a[$i];
            $nb += $b[$i] * $b[$i];
        }
        if ($na == 0 || $nb == 0) return 0.0;
        return $dot / (sqrt($na) * sqrt($nb));
    }
}
