<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Product;

class FetchProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch products from Fake Store API';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $response = Http::get('https://fakestoreapi.com/products');
        $products = $response->json();

        foreach ($products as $product) {
            $savedProduct =  Product::updateOrCreate(
                ['name' => $product['title']],
                [
                    'description' => $product['description'],
                    'price' => $product['price']
                ]
            );
            broadcast(new \App\Events\ProductUpdated($savedProduct));
        }

        $this->info('Products fetched successfully!');
    }
}
