<?php

namespace App\Modules\Sizes\Repository;

use App\Modules\Sizes\Models\Size;

class SizeRepository
{
    private $size;


    public function __construct()
    {

        $this->size = new Size();
    }

    public function getAll($records)
    {
        return $this->size->paginate($records);
    }
    public function getSizeOptionsByProductsCategory($category_id)
    {
        return $this->size->whereHasProductsWithCount($category_id)->get();
    }
    public function getAllSizes()
    {
        return $this->size->get();
    }

    public function createSize($validatedRequest)
    {
        return $this->saveSize($validatedRequest);
    }

    public function saveSize($validatedRequest, Size $size = null)
    {
        if (is_null($size)) {
            $size = new Size();
        }

        $size->name = $validatedRequest->name;
        $size->slug = $validatedRequest->name;

        $size->save();
    }

    public function getSize($id)
    {
        return $this->size->find($id);
    }

    public function updateSize($validatedRequest, $id)
    {
        $size = $this->getSize($id);
        $this->saveSize($validatedRequest, $size);

        return $size;
    }

    public function deleteSize($id)
    {
        return $this->size->where('id', $id)->delete();
    }
}