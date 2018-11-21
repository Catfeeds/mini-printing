<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2018/4/13
 * Time: 15:35
 * Function:
 */

namespace App\Admin\Controllers\Items;


use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\ItemCover;
use App\Models\ItemRecommend;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Tanmo\Search\Facades\Search;
use Tanmo\Search\Query\Searcher;

/**
 * @module 商品管理
 *
 * Class ItemController
 * @package App\Admin\Controllers\Items
 */
class ItemController extends Controller
{
    /**
     * @permission 商品列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $searcher = Search::build(function (Searcher $searcher) {
            $searcher->equal('id');
            $searcher->like('title');
        });
        $data = request()->all();
        $items = (new Item())->search($searcher)->orderBy('order', 'asc')->orderBy('created_at', 'desc')->paginate(10);
        $recommends = ItemRecommend::pluck('item_id')->toArray();

        $header = '商品列表';
        return view('admin::items.items', compact('items','recommends','data','header'));
    }

    /**
     * @permission 新增商品-页面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $categories = ItemCategory::all();
        return view('admin::items.item-create', compact('categories'));
    }

    /**
     * @permission 新增商品
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $item = new Item();
        $item->category_id = $request->get('category_id');
        $item->sn = $request->get('sn');
        $item->title = $request->get('title');
        $item->price = $request->get('price');
        $item->original_price = $request->get('original_price');
        $item->freight = $request->get('freight');
        $item->stock = $request->get('stock');
        $item->details = $request->get('details');
        $item->status = $request->get('status');
        $item->save();

        ///
        foreach ($request->file('covers') as $file) {
            /**
             * @var $file UploadedFile
             */
            $path = $file->store('covers', 'public');
            $cover = new ItemCover(['path' => $path]);
            $item->covers()->save($cover);
        }

        return redirect()->route('admin::items.index');
    }

    /**
     * @permission 修改商品-页面
     *
     * @param Item $item
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Item $item)
    {
        $categories = ItemCategory::all();
        return view('admin::items.item-edit', compact('item', 'categories'));
    }

    /**
     * @permission 修改商品
     *
     * @param Item $item
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Item $item, Request $request)
    {
        $item->title = $request->get('title');
        $item->sn = $request->get('sn');
        $item->category_id = $request->get('category_id');
        $item->price = $request->get('price');
        $item->original_price = $request->get('original_price');
        $item->freight = $request->get('freight');
        $item->stock = $request->get('stock');
        $item->details = $request->get('details');
        $item->status = $request->get('status');
        $item->save();
        if($request->file('covers')){
            foreach ($request->file('covers') as $file) {
                /**
                 * @var $file UploadedFile
                 */
                $path = $file->store('covers', 'public');
                $cover = new ItemCover(['path' => $path]);
                $item->covers()->save($cover);
            }
        }
        return redirect()->route('admin::items.index');
    }

    /**
     * @permission 删除商品
     *
     * @param Item $item
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Item $item)
    {
        $item->delete();

        return response()->json(['status' => 1, 'message' => '成功']);
    }

    /**
     * @permission 库存预警
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function warning()
    {
        if (request()->method() == 'PUT') {
            $count = request()->get('warning_count');

            edit_env(['ITEMS_EARLY_WARNING' => (int)$count]);

            return response()->json(['status' => 1, 'message' => '成功']);
        }

        $items = Item::warning()->orderBy('order', 'asc')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin::items.items-warning', compact('items'));
    }

    /**
     * @param Request $request
     * @return string
     */
    public function checkSn(Request $request)
    {
        if($request->get('current_sn')) {
            if($request->get('current_sn') == $request->get('sn')) {
                return '{"valid":true}';
            }
        }

        $item = Item::where('sn',$request->get('sn'))->first();
        if($item) {
            return '{"valid":false}';
        }
        return '{"valid":true}';

    }
}