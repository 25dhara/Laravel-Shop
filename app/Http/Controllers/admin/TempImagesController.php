<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;


class TempImagesController extends Controller
{
    public function create(Request $request)
    {

        $image = $request->image;
        if (!empty($image)) {
            $ext = $image->getClientOriginalExtension();
            $newFileName = time() . '.' . $ext;

            $tempImage = new TempImage();
            $tempImage->name = $newFileName;
            $tempImage->save();

            $image->move(public_path() . '/temp', $newFileName);

            //Generate thumbnail
            $sourcePath = public_path() . '/temp/' . $newFileName;
            $destPath = public_path() . '/temp/thumb/' . $newFileName;
            $image = Image::make($sourcePath);
            $image->fit(300, 275);
            $image->save($destPath);

            // return response()->json([
            //     'status' => true,
            //     'name' => $newFileName,
            //     'id' => $tempImage->id,
            //     // 'name'=> asset('/temp/thumb/'.$newName),
            //     'message' => 'image uploaded successfully'
            // ]);

            return response()->json([
                'status' => true,
                'image_id' => $tempImage->id,
                'ImagePath' => asset('/temp/thumb/' . $newFileName),
                'message' => 'image uploaded successfully'
            ]);
        }
    }
}
