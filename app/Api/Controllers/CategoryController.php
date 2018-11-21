<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2018/4/17
 * Time: 16:25
 * Function:
 */

namespace App\Api\Controllers;


use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ItemResource;
use App\Models\Item;
use App\Models\ItemCategory;

class CategoryController extends Controller
{
    /**
     * @return \Tanmo\Api\Http\Response
     */
    public function index()
    {
        $categories = ItemCategory::all();

        return api()->collection($categories, CategoryResource::class);
    }

    /**
     * 分类商品列表
     *
     * @param $id
     * @return \Tanmo\Api\Http\Response
     */
    public function show($id)
    {
        $order = request()->get('order', '');

        $items = (new Item())->where('category_id', $id)->WithOrder($order)->orderBy('order', 'asc')->orderBy('created_at', 'desc')->paginate(10);

        $items->load('covers');

        return api()->collection($items, ItemResource::class);
    }
}