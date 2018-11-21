<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2018/4/28
 * Time: 21:41
 * Function:
 */

namespace App\Api\Controllers;


use App\Http\Controllers\Controller;
use App\Http\Resources\ItemResource;
use App\Models\User;

class FavoriteController extends Controller
{
    /**
     * @var User
     */
    protected $user;

    /**
     * FavoriteController constructor.
     */
    public function __construct()
    {
        $this->user = auth()->user();
    }

    /**
     * @return \Tanmo\Api\Http\Response
     */
    public function index()
    {
        $this->user->favorites->load('covers');
        return api()->collection($this->user->favorites, ItemResource::class);
    }

    /**
     * @param $itemId
     * @return \Tanmo\Api\Http\Response
     */
    public function store($itemId)
    {
        $this->user->favorites()->attach($itemId);

        return api()->created();
    }

    /**
     * @param $itemId
     * @return \Tanmo\Api\Http\Response
     */
    public function destroy($itemId)
    {
        $this->user->favorites()->detach($itemId);

        return api()->noContent();
    }
}