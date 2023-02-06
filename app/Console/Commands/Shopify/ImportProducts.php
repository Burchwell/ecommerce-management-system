<?php

namespace App\Console\Commands\Shopify;

use App\Models\Image;
use App\Models\Products\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use PHPShopify\ShopifySDK;
use Symfony\Component\Console\Output\ConsoleOutput;

/**
 * Class importProducts
 * @package App\Console\Commands\Shopify
 */
class ImportProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shopify:import:products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private $shopify;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $config = [
            'ShopUrl' => config('shopify.store'),
            'ApiKey' => config('shopify.api_key'),
            'Password' => config('shopify.password')
        ];

        $this->shopify = new ShopifySDK($config);
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $out = new ConsoleOutput();
        $products = $this->shopify->Product->get();
        Log::info(json_encode($products));
        foreach ($products as $product) {
            $out->writeln("Processing Shopify ID " . $product['id']);
            $dbProduct = Product::where('sku', '=', $product['variants'][0]['sku'])->update(
                [
                    "product_type" => $product['product_type'],
                    "handle" => $product['handle'],
                    "body_html" => $product['body_html'],
                    "shopify_id" => $product['id']
                ],
                [
                    "product_type" => $product['product_type'],
                    "handle" => $product['handle'],
                    "body_html" => $product['body_html'],
                    "shopify_id" => $product['id']
                ]);


            foreach ($product['variants'] as $variant) {
                $var = [
                    "shopify_id" => $variant['product_id'],
                    "title" => $variant['title'],
                    "price" => $variant['price'],
                    "compare_at_price" => $variant['compare_at_price'],
                    "sku" => $variant['sku'],
                    "barcode" => $variant['barcode'],
                    "image_id" => $variant['image_id']
                ];

                $dbProduct->variants()
                    ->updateOrCreate($var, $var);
            }
        }

        if ($product['image'] !== null) {
            Image::updateOrCreate(
                [
                    "imagable_id" => $dbProduct->getKey(),
                    "imagable_type" => Product::class,
                    "alt" => $product['image']['alt'],
                    "src" => $product['image']['src'],
                    "width" => $product['image']['width'],
                    "height" => $product['image']['height'],
                ],
                [
                    "imageable_id" => $dbProduct->getKey(),
                    "imagable_type" => Product::class,
                    "alt" => $product['image']['alt'],
                    "src" => $product['image']['src'],
                    "width" => $product['image']['width'],
                    "height" => $product['image']['height'],
                ]
            );
        }

        foreach ($product['images'] as $image) {
            Image::updateOrCreate(
                [
                    "imagable_id" => $dbProduct->getKey(),
                    "imagable_type" => Product::class,
                    "alt" => $image['alt'],
                    "src" => $image['src'],
                    "width" => $image['width'],
                    "height" => $image['height'],
                ],
                [
                    "imageable_id" => $dbProduct->getKey(),
                    "imagable_type" => Product::class,
                    "alt" => $image['alt'],
                    "src" => $image['src'],
                    "width" => $image['width'],
                    "height" => $image['height'],
                ]
            );
        }

        $dbProduct->save();
    }
}
