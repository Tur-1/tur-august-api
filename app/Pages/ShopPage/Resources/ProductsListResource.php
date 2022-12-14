<?php

namespace App\Pages\ShopPage\Resources;

use App\Modules\Products\Services\ProductDiscountService;
use Illuminate\Support\Str;
use App\Modules\Users\Repository\UserRepository;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductsListResource extends JsonResource
{


    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $discountData = [
            'price' =>  $this->price,
            'discounted_price' => $this->discounted_price,
            'discount_amount' =>  $this->discount_amount,
            'discount_type' =>  $this->discount_type,
            'discount_start_at' =>  $this->discount_start_at,
            'discount_expires_at' =>   $this->discount_expires_at
        ];

        $discountService =  (new ProductDiscountService())->getDiscount($discountData);

        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' =>  $this->slug,
            'in_stock' => $this->stock > 0 ? true : false,
            'price' =>  $this->price,
            'brand_name' => $this->brand_name,
            'main_image_url' => $this->main_image_url,
            'inWishlist' => in_array($this->id,  app('inWishlist')),
            'price' => $discountService['price'],
            'price_before_discount' => $discountService['price_before_discount'],
            'discount_amount' => $discountService['discount_amount'],
        ];
    }
}