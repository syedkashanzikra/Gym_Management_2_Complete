<?php

use Coderstm\Models\File;
use Coderstm\Models\AppSetting;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $files = Storage::disk('local')->files('documents');
        $documents = [];
        foreach ($files as $path) {
            // save file to files table
            if (!in_array(basename($path), ['.gitignore', '.DS_Store'])) {
                $docs = new File;
                $docs->setHttpFile(new UploadedFile(storage_path('app/' . $path), basename($path)));
                $docs->disk = 'local';
                $docs->path = $path;
                $docs->save();
                $documents[] = $docs->toArray();
            }
        }
        $documents = collect($documents)->map(function ($item) {
            $item['is_active'] = true;
            return $item;
        });
        AppSetting::create('documents', $documents->all());
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
