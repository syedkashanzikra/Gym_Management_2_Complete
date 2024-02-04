<?php

namespace App\Http\Controllers\Admin\Shop\Product;

use Exception;
use App\Models\Shop\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Shop\Product\Collection;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CollectionController extends Controller
{
    public function index(Request $request, Collection $collection)
    {
        $collection = $collection->query();

        if ($request->filled('filter')) {
            $collection->where('name', 'like', "%{$request->filter}%");
        }

        if ($request->filled('status')) {
            $collection->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $collection->where('type', $request->type);
        }

        if ($request->boolean('deleted')) {
            $collection->onlyTrashed();
        }

        $collection = $collection->orderBy($request->sortBy ?? 'created_at', $request->direction ?? 'desc')
            ->paginate($request->rowsPerPage ?? 15);
        return new ResourceCollection($collection);
    }

    public function store(Request $request, Collection $collection)
    {
        $rules = [
            'name' => 'required',
        ];

        $this->validate($request, $rules);

        $collection = $collection->create($request->input());

        $this->saveRelated($request, $collection);

        return response()->json([
            'data' => $collection->fresh([
                'thumbnail',
                'products',
                'conditions',
            ]),
            'message' => 'Collection has been created successfully!',
        ], 200);
    }

    public function show(Collection $collection)
    {
        return response()->json($collection->fresh([
            'thumbnail',
            'products',
            'conditions',
        ]), 200);
    }

    public function update(Request $request, Collection $collection)
    {
        // Set rules
        $rules = [
            'name' => 'required',
        ];

        // Validate those rules
        $this->validate($request, $rules);

        $collection->update($request->input());

        $this->saveRelated($request, $collection);

        return response()->json([
            'data' => $collection->fresh([
                'thumbnail',
                'products',
                'conditions',
            ]),
            'message' => 'Collection has been updated successfully!',
        ], 200);
    }

    public function destroy(Collection $collection)
    {
        $collection->delete();
        return response()->json([
            'message' => 'Collection has been deleted successfully!',
        ], 200);
    }

    public function destroySelected(Request $request, Collection $collection)
    {
        $this->validate($request, [
            'collections' => 'required',
        ]);
        $collection->whereIn('id', $request->collections)->each(function ($item) {
            $item->delete();
        });
        return response()->json([
            'message' => 'Collections has been deleted successfully!',
        ], 200);
    }

    public function restore($id)
    {
        Collection::onlyTrashed()
            ->where('id', $id)->each(function ($item) {
                $item->restore();
            });
        return response()->json([
            'message' => 'Collection has been restored successfully!',
        ], 200);
    }

    public function restoreSelected(Request $request, Collection $collection)
    {
        $this->validate($request, [
            'collections' => 'required',
        ]);
        $collection->onlyTrashed()
            ->whereIn('id', $request->collections)->each(function ($item) {
                $item->restore();
            });
        return response()->json([
            'message' => 'Collections has been restored successfully!',
        ], 200);
    }

    protected function saveRelated(Request $request, Collection $collection)
    {
        // Update collection thumbnail
        if (isset($request['thumbnail']['id'])) {
            $collection->media()->sync($request['thumbnail']['id']);
        } else {
            $collection->media()->detach();
        }

        // Update collection conditions
        $collection->setConditions($request->input('conditions'));
    }

    public function addProducts(Request $request, Collection $collection)
    {
        $this->validate($request, [
            'products' => 'required|array',
            'products.*' => 'exists:products,id',
        ]);

        try {
            Product::find($request->products)->each(function ($product) use ($collection) {
                $product->collections()->syncWithoutDetaching($collection);
            });
        } catch (Exception $ex) {
            throw $ex;
        }

        return response()->json([
            'message' => 'Products has been added successfully!',
        ], 200);
    }
}
