<?php

namespace App\Http\Controllers\Admin\Shop\Product;

use App\Models\Shop\Product;
use Illuminate\Http\Request;
use App\Models\Shop\Product\Option;
use App\Http\Controllers\Controller;
use App\Models\Shop\Product\Attribute\Value;
use Illuminate\Http\Resources\Json\ResourceResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;

class OptionController extends Controller
{
    public function index(Request $request, Product $product)
    {
        $options = $product->options();

        if ($request->boolean('deleted')) {
            $options->onlyTrashed();
        }

        $options = $options->orderBy($request->sortBy ?? 'created_at', $request->direction ?? 'desc')
            ->paginate($request->rowsPerPage ?? 15);
        return new ResourceCollection($options);
    }

    public function show(Option $option)
    {
        return response()->json($option, 200);
    }

    public function update(Request $request, Option $option)
    {
        $rules = [
            'values' => 'required|array',
        ];

        $this->validate($request, $rules);

        $data = $request->input();
        $newValues = collect($request->values);
        $oldValues = $option->values;

        // Update option and it's values
        if ($option->is_custom) {
            $data['custom_values'] = $newValues;
            $option->update($data);
        } else if ($option->attribute_id) {
            $option->attribue_values()->sync([]);
            foreach ($newValues->pluck('name') as $value) {
                $option->attribue_values()->attach(Value::firstOrCreate([
                    'name' => $value,
                    'attribute_id' => $option->attribute_id
                ]));
            }
            $option->update($data);
        }

        // Clean variants if delete any value
        if ($oldValues) {
            foreach ($oldValues as $value) {
                if (!$newValues->contains('name', $value->name)) {
                    $option->product->variants()->whereHas('options', function ($query) use ($value) {
                        $query->where('value', $value->name);
                    })->each(function ($item) {
                        $item->delete();
                    });
                }
            }
        }

        return response()->json(new ResourceResponse($option), 200);
    }

    public function destroy(Option $option)
    {
        $name = $option->name;

        if ($option->product->options()->count() > 1) {
            // Delete related variant options
            $option->variant_options()->each(function ($item) {
                $item->delete();
            });

            // Delete duplicated variants
            $allCollections = $option->product->variants()->get()->load('options');
            $uniqueCollection = $allCollections->unique('title');
            $option->product->variants()->whereNotIn('id', $uniqueCollection->pluck('id'))->each(function ($item) {
                $item->delete();
            });

            // Delete the option
            $option->delete();
            return response()->json([
                'message' => "{$name} has been deleted successfully!"
            ], 200);
        }

        return response()->json([
            'message' => "Variant product need at least one product option."
        ], 403);
    }
}
