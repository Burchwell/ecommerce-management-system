<?php

namespace App\Jobs;

use App\Models\Products\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PHPShopify\ShopifySDK;

class ImportProducts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $shopify;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $config = [
            'ShopUrl' => config('shopify.store'),
            'ApiKey' => config('shopify.api_key'),
            'Password' => config('shopify.password')
        ];

        $this->shopify = new ShopifySDK($config);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $products = collect($this->shopify->Product->get());
        foreach ($products->chunk(100) as $chunk) {
            foreach ($chunk as $product) {
                $db_product = Product::where('sku', '=', $product->sku)->updateOrCreate($product);
            }
        }
    }
}
