<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2018/4/17
 * Time: 15:34
 * Function:
 */

namespace App\Admin\Presenters;


use App\Models\Item;
use Illuminate\Support\Facades\Storage;

class ItemPresenter
{
    /**
     * @param $item
     * @return string
     */
    public function status($item)
    {
        $label = '<span class="label label-warning">错误</span>';

        if ($item->status == 1) {
            $label = '<span class="label label-primary">上架</span>';
        }

        if ($item->status == 0) {
            $label = '<span class="label label-danger">下架</span>';
        }

        return $label;
    }

    /**
     * @param $item
     * @return string
     */
    public function stock($item)
    {
        return '<span class="badge bg-green">' . $item->stock . '</span>';
    }

    /**
     * @param $item
     * @return string
     */
    public function price($item)
    {
        $price = number_format($item->price, 2);
        $originalPrice = number_format($item->original_price, 2);
        return "<span class=\"badge bg-green\">{$price}</span>";
    }

    /**
     * @param $item
     * @return string
     */
    public function originalPrice($item)
    {
        $originalPrice = number_format($item->original_price, 2);

        return "<span class=\"badge bg-green\"><s>{$originalPrice}</s></span>";
    }

    /**
     * @param Item $item
     * @return string
     */
    public function cover(Item $item)
    {
        $cover = $item->covers()->first();
        $url = Storage::url($cover->path);
        return "<img src='$url' width='50' class='img-circle' height='50'/>";
    }
}