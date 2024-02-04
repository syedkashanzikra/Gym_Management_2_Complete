<?php

namespace App\Http\Controllers\Admin\Shop\Product;

use App\Models\Shop\Product\Tag;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TagController extends Controller
{
    public function index(Request $request, Tag $tag)
    {
        $tag = $tag->query();

        if ($request->filled('filter')) {
            $tag->where('name', 'like', "%{$request->filter}%");
        }

        if ($request->boolean('deleted') ?? false) {
            $tag->onlyTrashed();
        }

        $tag = $tag->orderBy($request->sortBy ?? 'created_at', $request->direction ?? 'desc')
            ->paginate($request->rowsPerPage ?? 15);
        return response()->json($tag, 200);
    }

    public function store(Request $request, Tag $tag)
    {
        $rules = [
            'name' => 'required',
        ];

        $this->validate($request, $rules);

        $tag = $tag->create($request->input());

        return response()->json([
            'data' => $tag->fresh(),
            'message' => 'Tag has been created successfully!'
        ], 200);
    }

    public function show(Tag $tag)
    {
        return response()->json($tag, 200);
    }
}
