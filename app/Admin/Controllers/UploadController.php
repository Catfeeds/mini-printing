<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2018/4/14
 * Time: 16:02
 * Function:
 */

namespace App\Admin\Controllers;


use App\Http\Controllers\Controller;
use App\Models\ItemCover;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    /**
     * 上传编辑器图片
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function image(Request $request)
    {
        $url = [];
        foreach ($request->file('imgs') as $file) {
            /**
             * @var $file UploadedFile
             */
            $path = $file->store('imgs', 'public');
            $url[] = Storage::url($path);
        }

        return response()->json([
            'errno' => 0,
            'data' => $url
        ]);
    }

    /**
     * 上传产品封面
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function cover(Request $request)
    {
        $path = $request->file('covers')->store('covers', 'public');
        return response()->json(['path' => $path]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteCover(Request $request)
    {
        ItemCover::find($request->key)->delete();
        return response()->json([]);
    }
}