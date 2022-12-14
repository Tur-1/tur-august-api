<?php

namespace App\Modules\Categories\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Categories\Requests\StoreCategoryRequest;
use App\Modules\Categories\Requests\StoreCategorySectionRequest;
use App\Modules\Categories\Requests\UpdateCategorySectionRequest;
use App\Modules\Categories\Requests\UpdateCategoryRequest;
use App\Modules\Categories\Services\CategoryService;

class CategoryController extends Controller
{
    private $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function getAllSectionsWithCategories(Request $request)
    {
        return $this->categoryService->getAllSectionsWithCategories($request->records);
    }

    public function getCategoriesBySection($section_id)
    {
        return $this->categoryService->getCategoriesBySection($section_id);
    }

    public function getSections(Request $request)
    {
        return $this->categoryService->getSections();
    }

    public function storeNewSection(StoreCategorySectionRequest $request)
    {
        $request->validated();

        $this->categoryService->storeNewSection($request);

        return response()->success([
            'message' => 'section has been created successfully',
        ]);
    }

    public function updateSection(UpdateCategorySectionRequest $request, $id)
    {
        $request->validated();

        return response()->success([
            'message' => 'section has been updated successfully',
            'category' => $this->categoryService->updateSection($request, $id),
        ]);
    }

    public function storeCategory(StoreCategoryRequest $request)
    {
        $request->validated();

        $this->categoryService->storeCategory($request);

        return response()->success([
            'message' => 'category has been created successfully',
        ]);
    }

    public function showCategory($id)
    {
        $category = $this->categoryService->getCategory($id);

        return response()->success($category);
    }

    public function updateCategory(UpdateCategoryRequest $request, $id)
    {
        $request->validated();

        $category = $this->categoryService->updateCategory($request, $id);

        return response()->success([
            'message' => 'category has been updated successfully',
            'category' => $category,
        ]);
    }

    public function destroyCategory($id)
    {
        $this->categoryService->destroyCategory($id);

        return response()->success([
            'message' => 'category has been deleted successfully',
        ]);
    }
}