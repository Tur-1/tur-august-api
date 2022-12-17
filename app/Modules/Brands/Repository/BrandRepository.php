<?php

namespace App\Modules\Brands\Repository;

use App\Modules\Brands\Models\Brand;
use App\Traits\ImageUpload;
use Illuminate\Support\Str;

class BrandRepository
{
    use ImageUpload;
    private $imageFolder = 'brands';
    private $brand;

    public function __construct(Brand $brand)
    {
        $this->brand = $brand;
    }

    public function getAll($records)
    {
        return $this->brand->paginate($records);
    }

    public function saveBrand($validatedRequest, Brand $brand = null)
    {
        if (is_null($brand)) {
            $brand = new Brand();
        }

        $brand->name = Str::title($validatedRequest->name);
        $brand->slug = Str::slug($validatedRequest->name);

        if ($validatedRequest->hasFile('image')) {
            $this->deletePreviousImage($this->getBrandOldImagePath($brand->image));
            $brand->image = $this->uploadImage($validatedRequest->file('image'), $this->imageFolder);
        }

        $brand->save();
    }

    public function getBrand($id)
    {
        return $this->brand->find($id);
    }

    public function updateBrand($validatedRequest, $id)
    {
        $brand = $this->getBrand($id);

        $this->saveBrand($validatedRequest, $brand);

        return $brand;
    }

    public function deleteBrand($id)
    {
        $brand = $this->getBrand($id);

        $this->destroyModelWithImage($brand, $this->getBrandOldImagePath($brand->image));
    }

    private function getBrandOldImagePath($image)
    {
        return $this->imageFolder.'/'.$image;
    }
}