<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2018/4/20
 * Time: 17:12
 * Function:
 */

namespace App\Admin\Controllers\Home;


use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

/**
 * @module 轮播图
 *
 * Class BannerController
 * @package App\Admin\Controllers\Home
 */
class BannerController extends Controller
{
    /**
     * @var array
     */
    protected $types = [
//        'url' => '链接',
        'goods' => '商品',
    ];

    /**
     * @permission 轮播图列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $banners = Banner::all();
        return view('admin::home.banners', compact('banners'));
    }

    /**
     * @permission 新增轮播图-页面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $types = $this->types;
        return view('admin::home.banner-create', compact('types'));
    }

    /**
     * @permission 新增轮播图
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $banner = new Banner();
        $banner->order = $request->order;
        $banner->status = $request->status;
        $http_val = $request->http;
        $type = $request->type;


        if($type == 'url'){
            switch ($http_val){
                case 1:
                    $url_pre = 'http://';
                    break;
                case 2:
                    $url_pre = 'https://';
                    break;
                default:
                    $url_pre = '';
            }
        }else{
            $url_pre = '';
        }
        $banner->redirect = [
            'target' => $url_pre.$request->target,
            'type' => $type
        ];

        if ($request->hasFile('banner')) {
            $path = $request->file('banner')->store('banner', 'public');
            $banner->path = $path;
        }

        $banner->save();

        return redirect()->route('admin::banners.index');
    }

    /**
     * @permission 修改轮播图-页面
     *
     * @param Banner $banner
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Banner $banner)
    {
        if($banner->type == 'goods'){
            $title = \DB::table('items')->select('title')->find($banner->target);
            $banner->target_2 =$banner->target;
        }else{
            $str = $banner->target;
            $needle1 = "http://";
            $needle2 = "https://";
            $result = str_replace($needle1,'',$str);
            $result = str_replace($needle2,'',$result);
            $banner->target_2 =$result;
        }
        $types = $this->types;
        return view('admin::home.banner-edit', compact('banner', 'types','title'));
    }

    /**
     * @permission 修改轮播图
     *
     * @param Banner $banner
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Banner $banner, Request $request)
    {
        $banner->order = $request->order;
        $banner->status = $request->status;
        $http_val = $request->http;
        $type = $request->type;
        $url_pre='';

        if($type == 'url'){
            switch ($http_val){
                case 1:
                    $url_pre = 'http://';
                    break;
                case 2:
                    $url_pre = 'https://';
                    break;
                default:
                    $url_pre = '';
            }
        }
        $banner->redirect = [
            'target' => $url_pre.$request->target,
            'type' => $type,
        ];
        if ($request->hasFile('banner')) {
            $path = $request->file('banner')->store('banner', 'public');
            $banner->path = $path;
        }

        $banner->save();

        return redirect()->route('admin::banners.index');
    }

    /**
     * @permission 删除轮播图
     *
     * @param Banner $banner
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Banner $banner)
    {
        $banner->delete();

        return response()->json(['status' => 1, 'message' => '成功']);
    }
}