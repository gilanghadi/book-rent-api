<?php

namespace App\Http\Controllers\Web;

use App\Models\Category;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\CategoryRequest;
use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\Category\CategoryDetailResource;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $categoriesDeleted = Category::onlyTrashed()->get();
        return response()->json([
            'status' => 'true',
            'data' => [
                'categories' => CategoryResource::collection($categories),
                'categories_deleted' => CategoryDetailResource::collection($categoriesDeleted)
            ]
        ]);
    }

    public function store(CategoryRequest $request)
    {
        $validator = $request->validated();
        $category = Category::create($validator);
        if (!$category) {
            return response()->error('Gagal Menambahkan Kategori');
        }
        return response()->json([
            'status' => 'true',
            'message' => 'Menambahkan Kategori Berhasil!',
            'data' => new CategoryResource($category)
        ]);
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $validator = $request->validated();
        $category = Category::findOrFail($category->id);
        $validator['slug'] = Str::slug(strtolower($validator['name']), "-");
        $category->update($validator);
        if (!$category) {
            return response()->error('Gagal Mengupdate Kategori');
        }
        return response()->json([
            'status' => 'true',
            'message' => 'Mengupdate Kategori Berhasil!',
            'data' => new CategoryResource($category)
        ]);
    }

    public function destroy(Category $category)
    {
        $category = Category::findOrFail($category->id);
        $category->delete();
        if (!$category) {
            return response()->error('Gagal Menghapus Kategori');
        }
        return response()->json([
            'status' => 'true',
            'message' => 'Menghapus Kategori Berhasil!',
            'data' => new CategoryResource($category)
        ]);
    }

    public function restore(Category $category)
    {
        $category = Category::withTrashed()->where('id', $category->id)->first();
        $category->restore();
        if (!$category) {
            return response()->error('Gagal Mengembalikan Data Kategori Yang Dihapus');
        }
        return response()->json([
            'status' => 'true',
            'message' => 'Mengembalikan Data Kategori Yang Dihapus Berhasil!',
            'data' => new CategoryResource($category)
        ]);
    }
}
