<?php

namespace App\Jobs;

use App\Models\Product;
use App\Models\ProductPicture;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessProductImages implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $imageIds;

    public function __construct(array $imageIds)
    {
        $this->imageIds = $imageIds;
    }

    public function handle()
    {
        // 1. Load images by IDs
        $images = ProductPicture::whereIn('id', $this->imageIds)->get();

        // 2. Prepare image URLs or paths to send to clustering service
        $imagePaths = $images->pluck('image_path')->toArray();

        // 3. Call clustering service (Python script or external API)
        $clusterResults = $this->callClusteringService($imagePaths);

        // 4. For each cluster, create a product and assign images
        foreach ($clusterResults as $clusterId => $clusterImagePaths) {
            $product = Product::create([
                'user_id' => $images->first()->user_id, // Or pass user_id from controller/job constructor
                'title' => '',
                'description' => '',
                'platform_data' => json_encode([]),
            ]);

            ProductPicture::whereIn('image_path', $clusterImagePaths)
                ->update(['product_id' => $product->id]);
        }
    }

    protected function callClusteringService(array $imagePaths)
    {
        // Call your Python clustering script or external API here
        // For example, using shell_exec or HTTP client

        // This is a stub - replace with actual call
        $fakeClusters = [
            'cluster_1' => [$imagePaths[0], $imagePaths[1]],
            'cluster_2' => [$imagePaths[2]],
        ];

        return $fakeClusters;
    }
}
