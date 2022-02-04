<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ConverterController extends Controller
{
    public function converter(Request $request)
    {
        $newFormat = $request->input('newFormat');
        $image = $request->file('image');
        $quality = $request->input('quality');
        
        if($request->hasFile('image') && $request->file('image')->isValid())
        {
            $imageName = str_replace(['.webp', '.jpg', '.png'], '', $image->getClientOriginalName());
            $imageFormat = $image->getClientOriginalExtension();
            $currentImage = "{$imageName}.{$imageFormat}";
            $image->storeAs('public/uploads', $currentImage);
            
            $dir = public_path().'/storage/uploads/';
            $newName = null;
            switch ($newFormat) {
                case 'webp':
                    $newName = $imageName.'.webp';
                    $img = null;
                    if($imageFormat == 'webp')
                    {
                        $img = imagecreatefromwebp($dir . $currentImage);
                    }
                    if($imageFormat == 'png')
                    {
                        $img = imagecreatefrompng($dir . $currentImage);
                    }
                    if($imageFormat == 'jpg')
                    {
                        $img = imagecreatefromjpeg($dir . $currentImage);
                    }
                    imagepalettetotruecolor($img);
                    imagealphablending($img, true);
                    imagesavealpha($img, true);
                    imagewebp($img, $dir . $newName, number_format($quality, 1, '', ''));
                    imagedestroy($img);

                    break;

                case 'png':
                    $newName = $imageName.'.png';
                    $img = null;
                    if($imageFormat == 'webp')
                    {
                        $img = imagecreatefromwebp($dir . $currentImage);
                    }
                    if($imageFormat == 'png')
                    {
                        $img = imagecreatefrompng($dir . $currentImage);
                    }
                    if($imageFormat == 'jpg')
                    {
                        $img = imagecreatefromjpeg($dir . $currentImage);
                    }
                    imagepalettetotruecolor($img);
                    imagealphablending($img, true);
                    imagesavealpha($img, true);
                    imagepng($img, $dir . $newName, $quality > 5 ? 1 : 0);
                    imagedestroy($img);
                    
                    break;

                case 'jpg':

                    $newName = $imageName.'.jpg';
                    $img = null;
                    if($imageFormat == 'webp')
                    {
                        $img = imagecreatefromwebp($dir . $currentImage);
                    }
                    if($imageFormat == 'png')
                    {
                        $img = imagecreatefrompng($dir . $currentImage);
                    }
                    if($imageFormat == 'jpg')
                    {
                        $img = imagecreatefromjpeg($dir . $currentImage);
                    }
                    imagepalettetotruecolor($img);
                    imagealphablending($img, true);
                    imagesavealpha($img, true);
                    imagejpeg($img, $dir . $newName, number_format($quality, 1, '', ''));
                    imagedestroy($img);

                    break;
            }
            
            $path = public_path().'/storage/uploads/'.$newName;
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

            if(File::exists($path))
            {
                File::delete($path);

                if(File::exists($dir . $currentImage))
                {
                    File::delete($dir . $currentImage);
                }
            }

            return response()->json($base64);
        }
    }
}
