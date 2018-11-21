<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2018/4/17
 * Time: 16:23
 * Function:
 */

namespace App\Api\Controllers;


use App\Http\Controllers\Controller;
use App\Http\Resources\ItemDetailResource;
use App\Http\Resources\ItemResource;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    /**
     * 所有商品
     *
     * @return \Tanmo\Api\Http\Response
     */
    public function index()
    {
        $order = request()->get('order', '');
        $items = Item::orderBy('order', 'asc')->withOrder($order)->paginate(10);
        $items->load('covers');

        return api()->collection($items, ItemResource::class);
    }

    /**
     * 商品详情
     *
     * @param Item $item
     * @return \Tanmo\Api\Http\Response
     */
    public function show(Item $item)
    {
        $item->load('covers');
        return api()->item($item, ItemDetailResource::class);
    }

    /**
     * 商品推荐
     *
     * @return \Tanmo\Api\Http\Response
     */
    public function recommended()
    {
        $items = Item::inRandomOrder()->limit(8)->get();
        $items->load('covers');

        return api()->collection($items, ItemResource::class);
    }
    /**
     * 寻找商品
     */
    public function search()
    {
        $date = Input::get();
        $key = htmlspecialchars($date['keyword']);
        $page = htmlspecialchars($date['page']);
        $page = isset($page) ? $page : 1 ;
        $keyword = "%{$key}%";
        $item = Item::where('status','=',1)
            ->where(function($query) use($keyword){
                $query->orwhere('title', 'like',$keyword)
                    ->orwhere('sn','like',$keyword);
            })->paginate($perPage = 10, $columns = ['*'], $pageName = 'page', $page);

        $item->load('covers');
        foreach ($item as $key => $arr){
            $url = Storage::url($item[$key]['covers']['0']['path']);
            $item[$key]['url'] = $url;
            $item[$key]['status'] = $arr['status'] == 0 ?"下架":"上架";
            unset($item[$key]['covers']);
            unset($item[$key]['details']);
        }
        return $item;
    }


}