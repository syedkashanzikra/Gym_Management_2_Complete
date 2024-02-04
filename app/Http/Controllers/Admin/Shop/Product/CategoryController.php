<?php

namespace App\Http\Controllers\Admin\Shop\Product;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Shop\Product\Category;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CategoryController extends Controller
{
    public function index(Request $request, Category $category)
    {
        $category = $category->query();

        if ($request->filled('filter')) {
            $category->where('name', 'like', "%{$request->filter}%");
        }

        if ($request->filled('status')) {
            $category->where('status', $request->status);
        }

        if ($request->boolean('deleted')) {
            $category->onlyTrashed();
        }

        $category = $category->orderBy($request->sortBy ?? 'created_at', $request->direction ?? 'desc')
            ->paginate($request->rowsPerPage ?? 15);
        return new ResourceCollection($category);
    }

    public function store(Request $request, Category $category)
    {
        $rules = [
            'name' => 'required',
        ];

        $this->validate($request, $rules);

        $data = $request->input();

        // Process the parent category
        if (isset($request['parent']['id'])) {
            $data['parent_id'] = $request['parent']['id'];
        }

        $category = $category->create($data);

        // save category's realted model
        $this->saveRelated($request, $category);

        return response()->json([
            'data' => $category->fresh()->load([
                'parent',
                // 'thumbnail',
            ]),
            'message' => 'Category has been created successfully!',
        ], 200);
    }

    public function show(Category $category)
    {
        return response()->json($category->load([
            'parent',
            // 'thumbnail',
        ]), 200);
    }

    public function update(Request $request, Category $category)
    {
        // Set rules
        $rules = [
            'name' => 'required',
        ];

        // Validate those rules
        $this->validate($request, $rules);

        $data = $request->input();

        // Process the parent category
        if (isset($request['parent']['id'])) {
            $data['parent_id'] = $request['parent']['id'];
        }

        $category->update($data);

        // save category's realted model
        $this->saveRelated($request, $category);

        return response()->json([
            'data' => $category->fresh()->load([
                'parent',
                // 'thumbnail',
            ]),
            'message' => 'Category has been updated successfully!',
        ], 200);
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json([
            'message' => 'Category has been deleted successfully!',
        ], 200);
    }

    public function destroySelected(Request $request, Category $category)
    {
        $this->validate($request, [
            'categories' => 'required',
        ]);
        $category->whereIn('id', $request->categories)->each(function ($item) {
            $item->delete();
        });
        return response()->json([
            'message' => 'Categories has been deleted successfully!',
        ], 200);
    }

    public function restore($id)
    {
        Category::onlyTrashed()
            ->where('id', $id)->each(function ($item) {
                $item->restore();
            });
        return response()->json([
            'message' => 'Category has been restored successfully!',
        ], 200);
    }

    public function restoreSelected(Request $request, Category $category)
    {
        $this->validate($request, [
            'categories' => 'required',
        ]);
        $category->onlyTrashed()
            ->whereIn('id', $request->categories)->each(function ($item) {
                $item->restore();
            });
        return response()->json([
            'message' => 'Categories has been restored successfully!',
        ], 200);
    }

    protected function saveRelated(Request $request, Category $category)
    {
        // Update category thumbnail
        if (isset($request['thumbnail']['id'])) {
            $category->media()->sync($request['thumbnail']['id']);
        } else {
            $category->media()->detach();
        }
    }
}
