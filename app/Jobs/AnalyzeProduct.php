<?php

namespace App\Jobs;

use App\Models\Product;
use Exception;
use Google\Auth\Credentials\ServiceAccountCredentials;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Http\Client\Response;
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
        $response = $this->callVertexUnified($token, $this->images);
        // $csvData = $this->callVertexCsvData($token, $seed);
        $formattedJson = $this->formatJsonResponse($response);
        Log::info("Formatted JSON Response: " . json_encode($formattedJson, JSON_PRETTY_PRINT));
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
    
    private function callVertexCsvData(string $token, int $seed): Response
    {
        try {
            $parts = [];

            // $parts[] = [
            //     'fileData' => [
            //         'mimeType' => 'application/pdf',
            //         'fileUri'  => 'https://keywordgenerator2.s3.us-east-2.amazonaws.com/product_images/Whatnot+%E2%80%94+CSV+Template+-+Template.pdf',
            //     ],
            //     'fileData' => [
            //         'mimeType' => 'application/pdf',
            //         'fileUri'  => 'https://keywordgenerator2.s3.us-east-2.amazonaws.com/product_images/Whatnot+%E2%80%94+CSV+Template+-+Values.pdf',
            //     ],
            // ];

            $parts[] = [
                // 'text' => 'using the template above, generate an entry for the previously uploaded product images, the values file will give options for the fields, and the template file will give the structure. Please output this in a minified CSV format without extra whitespace'
                'text' => 'sorry please remind me what the previous product images were.'
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type'  => 'application/json',
            ])->post('https://aiplatform.googleapis.com/v1/projects/keywordgenerator-460202/locations/global/publishers/google/models/gemini-2.5-flash-preview-05-20:generateContent', [
                'contents' => [
                    'role'  => 'user',
                    'parts' => $parts,
                ],
                'generationConfig' => [
                    "temperature" => 1,
                    "maxOutputTokens" => 65535,
                    "topP" => 1,
                    "seed" => $seed,
                    "thinkingConfig" => [
                        "thinkingBudget" => 0
                    ]
                ]
            ]);

            return $response;
        }
        catch (Exception $e) {
            Log::error("Error preparing CSV data: " . $e->getMessage());
            throw $e;
        }
    }

    private function callVertexUnified(string $token, array $images): Response
    {
        try {
            $parts = [];

            // Add product image files
            foreach ($images as $imagePath) {
                $imageUri = 'https://keywordgenerator2.s3.us-east-2.amazonaws.com/' . $imagePath;
                $parts[] = [
                    'fileData' => [
                        'mimeType' => 'image/jpeg',
                        'fileUri'  => $imageUri,
                    ],
                ];
            }

            // Add template and values files (optional but useful)
            // $parts[] = [
            //     'fileData' => [
            //         'mimeType' => 'application/pdf',
            //         'fileUri'  => 'https://keywordgenerator2.s3.us-east-2.amazonaws.com/product_images/Whatnot+%E2%80%94+CSV+Template+-+Template.pdf',
            //     ],
            // ];
            // $parts[] = [
            //     'fileData' => [
            //         'mimeType' => 'application/pdf',
            //         'fileUri'  => 'https://keywordgenerator2.s3.us-east-2.amazonaws.com/product_images/Whatnot+%E2%80%94+CSV+Template+-+Values.pdf',
            //     ],
            // ];

            // Add instruction text to do both tasks
            $parts[] = [
                'text' => <<<EOT
                Analyze the product images and generate an SEO optimized title, description, and 10 keywords. Also generate platform-specific versions for Whatnot, eBay, and Poshmark with title, description, and keywords.

                Output the response in this JSON format:
                {
                "title": "SEO Title",
                "description": "SEO Description",
                "keywords": ["keyword1", ...],
                "whatnot": {
                    "title": "...", "description": "...", "keywords": [...]
                },
                "ebay": {
                    "title": "...", "description": "...", "keywords": [...]
                },
                "poshmark": {
                    "title": "...", "description": "...", "keywords": [...]
                }
                EOT
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type'  => 'application/json',
            ])
            ->timeout(120) // <-- Set timeout to 120 seconds
            ->post('https://aiplatform.googleapis.com/v1/projects/keywordgenerator-460202/locations/global/publishers/google/models/gemini-2.5-flash-preview-05-20:generateContent', [
                'contents' => [
                    'role' => 'user',
                    'parts' => $parts,
                ],
                'generationConfig' => [
                    "temperature" => 1,
                    "maxOutputTokens" => 65535,
                    "topP" => 1,
                ]
            ]);
        
            return $response;
        } catch (Exception $e) {
            Log::error("Error calling unified Vertex API: " . $e->getMessage());
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
        // Extract and log/store the CSV string if present
        // $csvString = $formattedJson['csv'] ?? null;
        // $this->product->csv_data = json_encode($csvString);
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
