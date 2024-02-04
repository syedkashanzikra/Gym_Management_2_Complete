<?php

namespace App\Http\Controllers\Admin\Shop\Product;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Shop\Product\Attribute;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AttributeController extends Controller
{
    public function index(Request $request, Attribute $attribute)
    {
        $attributes = $attribute->query();

        if ($request->boolean('deleted')) {
            $attributes->onlyTrashed();
        }

        $attributes = $attributes->orderBy($request->sortBy ?? 'created_at', $request->direction ?? 'desc')
            ->paginate($request->rowsPerPage ?? 15);
        return new ResourceCollection($attributes);
    }

    public function store(Request $request, Attribute $attribute)
    {
        // Set rules
        $rules = [
            'name' => 'required',
            // Values
            'values' => 'array|required',
            'values.*.id' => 'sometimes|required_unless:values.*.name,null',
            'values.*.name' => 'required_if:values.*.id,null|string',
        ];

        // Validate those rules
        $this->validate($request, $rules);

        $attribute = $attribute->create($request->input());
        $attribute->setValues($request->input('values'));

        return response()->json([
            'data' => $attribute->load([
                'values',
                'values.thumbnail',
            ]),
            'message' => 'Attribute has been created successfully!'
        ], 200);
    }

    public function show(Attribute $attribute)
    {
        return response()->json($attribute->load([
            'values',
            'values.thumbnail',
        ]), 200);
    }

    public function update(Request $request, Attribute $attribute)
    {
        // Set rules
        $rules = [
            'name' => 'required',
            // Values
            'values' => 'array',
            'values.*.id' => 'sometimes|required_unless:values.*.name,null',
            'values.*.name' => 'required_if:values.*.id,null|string',
        ];

        // Validate those rules
        $this->validate($request, $rules);

        $attribute->update($request->input());
        $attribute->setValues($request->input('values'));

        return response()->json([
            'data' => $attribute->load([
                'values',
                'values.thumbnail',
            ]),
            'message' => 'Attribute has been updated successfully!'
        ], 200);
    }

    public function destroy(Attribute $attribute)
    {
        $attribute->delete();
        return response()->json([
            'message' => 'Attribute has been deleted successfully!'
        ], 200);
    }

    public function destroySelected(Request $request, Attribute $attribute)
    {
        $this->validate($request, [
            'attributes' => 'required',
        ]);
        $attribute->whereIn('id', $request->attributes)->each(function ($item) {
            $item->delete();
        });
        return response()->json([
            'message' => 'Attributes has been deleted successfully!',
        ], 200);
    }

    public function restore($id)
    {
        Attribute::onlyTrashed()
            ->where('id', $id)->each(function ($item) {
                $item->restore();
            });
        return response()->json([
            'message' => 'Attribute has been restored successfully!',
        ], 200);
    }

    public function restoreSelected(Request $request, Attribute $attribute)
    {
        $this->validate($request, [
            'attributes' => 'required',
        ]);
        $attribute->onlyTrashed()
            ->whereIn('id', $request->attributes)->each(function ($item) {
                $item->restore();
            });
        return response()->json([
            'message' => 'Attributes has been restored successfully!',
        ], 200);
    }
}
