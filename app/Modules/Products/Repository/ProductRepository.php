<?php

namespace App\Modules\Products\Repository;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Products\Models\Product;
use App\Modules\Categories\Models\Category;
use App\Traits\ImageUpload;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository
{
    use ImageUpload;

    private $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }
    public function getAll($records)
    {
        return $this->product->paginate($records);
    }
    public function createProduct($validatedRequest)
    {
        return $this->saveProduct($validatedRequest);
    }


    public function getProduct($id)
    {
        return $this->product->with('sizes', 'categories', 'productImages')->find($id);
    }
    public function updateProduct($validatedRequest, $id)
    {
        $product = $this->getProduct($id);
        $product->update($validatedRequest);
        return  $product;
    }
    public function deleteProduct($id)
    {
        return $this->product->where('id', $id)->delete();
    }

    public function saveProduct($request)
    {
        $sizeOptions = $this->getSizeOptions($request->sizeOptions);

        $this->product->details = $request->details;
        $this->product->info_and_care = $request->info_and_care;
        $this->product->brand_id = $request->brand_id;
        $this->product->color_id = $request->color_id;
        $this->product->price = $request->price;
        $this->product->shipping_cost = $request->shipping_cost;
        $this->product->name = Str::title($request->name);
        $this->product->slug = $this->generateSlug($request->name, $this->product->id);
        $this->product->stock = $this->getProductStock($sizeOptions);

        $this->product->save();

        $this->product->sizes()->sync($sizeOptions);
        $this->storeCategories($this->product, $request->category_id);
        $this->storeProductImages($this->product, $request->images);
    }
    private function generateSlug($product_name, $product_id): string
    {
        $product_slug = Str::slug($product_name);

        if (Product::where('slug',  $product_slug)->where('id', '!=', $product_id)->exists()) { // if exists ? add random strings to product slug 
            $product_slug .=  '-' . Str::random(2) . '-' . rand(1, 100) . '-' . Str::random(1);
        }

        return $product_slug;
    }

    private function getSizeOptions($sizeOptionsRequest): array
    {
        $sizes = [];
        $sizeOptions = [];
        foreach ($sizeOptionsRequest as $size) {

            $size = json_decode($size, true);

            $sizeOptions[$size['size_id']] = ['size_id' => $size['size_id'], 'stock' => $size['stock']];
        }

        return $sizeOptions;
    }
    private function storeCategories(Model $product,  $category_id): void
    {

        $category = Category::where("id",  $category_id)->first();
        $parents_ids = $category['parents_ids'];

        $parents_ids[] = $category['id'];
        $parents_ids = array_unique($parents_ids);

        $product->categories()->sync($parents_ids);
    }
    private function getProductStock($sizeOptions)
    {
        return collect($sizeOptions)->sum('stock');
    }

    private  function storeProductImages(Model $product, $productImages)
    {
        if (is_null($productImages))  return;

        $images = [];
        $imagesFolder = 'products/product_' . $product->id;

        foreach ($productImages as $image) {

            $newImageName = $this->uploadImage($image,  $imagesFolder);
            $images[] = [
                'image' => $newImageName,
            ];
        }


        // if there is no main image then the first image will be the main image


        $product->productImages()->createMany($images);
    }
}