<?php

namespace App\Http\Controllers\User;

use Coderstm\Models\File;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DocumentController extends Controller
{
    /**
     * Get documents list
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $readDocuments = current_user()->documents;
        $documents = File::whereIn('id', app_settings('documents')
            ->where('is_active', true)
            ->pluck('id'))->get()
            ->map(function ($item) use ($readDocuments) {
                $item->has_read = $readDocuments->contains('id', $item->id);
                return $item;
            });

        return response()->json($documents, 200);
    }

    /**
     * Read the specified document
     *
     * @param  \Coderstm\Models\File  $document
     * @return \Illuminate\Http\Response
     */
    public function show(File $document)
    {
        current_user()->documents()->syncWithoutDetaching([
            $document->id => [
                'read_at' => now()
            ]
        ]);
        return response()->json($document, 200);
    }
}
