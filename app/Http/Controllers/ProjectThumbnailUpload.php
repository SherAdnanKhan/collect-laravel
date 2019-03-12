<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use \Illuminate\Http\Request;

class ProjectThumbnailUpload extends Controller
{
    public function handle(Request $request)
    {
        $originalName = $request->file('file')->getClientOriginalName();
        $newName = time() . $originalName;

        $photo = Image::make($request->file('file'))
          ->fit(400)
          ->encode('jpg',80);

        Storage::disk('s3')->put('project-images/' . $newName, $photo, 'public');

        return response()->json([
            'name' => $newName,
        ]);
    }
}
