<?php

namespace App\Jobs;

use App\Models\Product;
use Exception;
use Google\Auth\Credentials\ServiceAccountCredentials;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AnalyzeProduct implements ShouldQueue
{
    use Queueable;

    protected Product $product;
    protected array $images;
    /**
     * Create a new job instance.
     */
    public function __construct(Product $product, array $images)
    {
        $this->product = $product;
        $this->images = $images;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $token = $this->getToken();
        $response = $this->callVertexAPI($token, $this->images);
        $formattedJson = $this->formatJsonResponse($response);
        $keywordData = $this->analyzeKeywordData($formattedJson['keywords']);
        $this->updateProduct($formattedJson, $keywordData);
    }

    private function getToken()
    {
        return Cache::remember('Bearer', 55, function () {
            $serviceAccountJsonPath = env('GOOGLE_APPLICATION_CREDENTIALS');

            $scopes = ['https://www.googleapis.com/auth/cloud-platform'];

            $credentials = new ServiceAccountCredentials(
                $scopes,
                $serviceAccountJsonPath
            );

            $accessToken = $credentials->fetchAuthToken();

            return $accessToken['access_token'];
        });
    }
    
    private function callVertexAPI(string $token, array $images)
    {
        try {
            $parts = [];
            foreach ($images as $imagePath) {
                $imageUri = 'https://keywordgenerator2.s3.us-east-2.amazonaws.com/' . $imagePath;
                $parts[] = [
                    'fileData' => [
                        'mimeType' => 'image/jpeg',
                        'fileUri'  => $imageUri,
                    ],
                ];
            }

            $parts[] = [
                'text' => 'Analyze the above product images and generate an SEO optimized title, description, and list of 10 keywords for the product. Please also add another set title, description, and keywords that are optimized for whatnot, one for eBay, and one for Poshmark. Output this in a minified JSON format without extra whitespace, using the following structure: {"title": "SEO Optimized Title", "description": "SEO Optimized Description", "keywords": ["keyword1", "keyword2", ...], "whatnot": {"title": "Whatnot Optimized Title", "description": "Whatnot Optimized Description", "keywords": ["keyword1", "keyword2", ...]}, "ebay": {"title": "eBay Optimized Title", "description": "eBay Optimized Description", "keywords": ["keyword1", "keyword2", ...]}, "poshmark": {"title": "Poshmark Optimized Title", "description": "Poshmark Optimized Description", "keywords": ["keyword1", "keyword2", ...]}}',
            ];

            // Send a single request
            return Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type'  => 'application/json',
            ])->post('https://aiplatform.googleapis.com/v1/projects/keywordgenerator-460202/locations/global/publishers/google/models/gemini-2.5-flash-preview-05-20:generateContent', [
                'contents' => [
                    'role'  => 'user',
                    'parts' => $parts,
                ],
            ]);
        } catch (Exception $e) {
            Log::error("Error calling Vertex API: " . $e->getMessage());
            throw $e;
        }
    }

    private function formatJsonResponse($response)
    {
        $data = $response->json();
        $extractedText = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';

        $extractedText = preg_replace('/^```json\s*/', '', $extractedText);
        $extractedText = preg_replace('/```$/', '', $extractedText);
        $extractedText = str_replace("\n", "", $extractedText);
        
        return json_decode($extractedText, true);
    }

    private function updateProduct(array $formattedJson, string $keywordData): void
    {
        $this->product->platform_data = json_encode($formattedJson);
        // $this->product->keyword_data = json_encode($keywordData);
        $keywordData = json_decode($keywordData, true);
        $this->product->keyword_data = json_encode($keywordData['data']);
        $this->product->status = 'processed';
        $this->product->save();
    }


    private function analyzeKeywordData(array $text): string
    {
        $kw = [];
        foreach ($text as $line){
            $kw[] = trim(mb_convert_encoding($line, 'UTF-8'));
        }

        $keywordDataRespones = Http::withToken(env('KEYWORDS_EVERYWHERE'))
            ->acceptJson()
            ->asForm()
            ->post('https://api.keywordseverywhere.com/v1/get_keyword_data', [
                'kw' => $kw,
                'country' => 'us',
                'currency' => 'usd',
                'dataSource' => 'gkp'
            ]);

        return $keywordDataRespones->body() ?? '';
    }

    public function failed(Exception $exception)
    {
        Log::error("Job failed: " . $exception->getMessage());

        $this->product->status = 'failed';
        $this->product->save();

        $this->release(60);
    }

    public function tries(): int
    {
        return 5;
    }

}
