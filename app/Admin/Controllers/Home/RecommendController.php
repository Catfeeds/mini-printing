<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2018/4/20
 * Time: 17:41
 * Function:
 */

namespace App\Admin\Controllers\Home;


use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\ItemRecommend;
use Illuminate\Http\Request;

/**
 * @module 商品推荐
 *
 * Class RecommendController
 * @package App\Admin\Controllers\Home
 */
class RecommendController extends Controller
{
    /**
     * @permission 商品列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $recommends = ItemRecommend::orderBy('created_at', 'desc')->paginate(10);

        return view('admin::home.recommends', compact('recommends'));
    }

    /**
     * @permission 增加推荐
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $recommend = new ItemRecommend();
        $recommend->item_id = $request->get('item_id');
        $recommend->save();

        return response()->json(['status' => 1, 'message' => '推荐成功']);
    }

    /**
     * @permission 删除推荐
     *
     * @param ItemRecommend $recommend
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(ItemRecommend $recommend)
    {
        $recommend->delete();

        return response()->json(['status' => 1, 'message' => '成功']);
    }
}