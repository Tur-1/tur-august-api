<?php

namespace App\Pages\ProductDetailPage\Resources;

use Illuminate\Support\Str;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductDetailReviewsResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        return [
            'user' => [
                'name' => $this->user->name,
                'gender' => $this->user->gender
            ],
            'comment' => $this->comment,
            'date' =>  $this->created_at->diffForHumans(),
            'reply' => ProductDetailReviewsResource::make($this->whenLoaded('reply')),
        ];
    }
}