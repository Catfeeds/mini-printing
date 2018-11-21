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
use Illuminate\Http\Request;

class CatController extends Controller
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
        $this->user->cats->load('covers');
        return api()->collection($this->user->cats, ItemResource::class);
    }

    public function destroy($itemId)
    {
        $this->user->cats()->detach($itemId);

        return api()->noContent();
    }

    public function store($itemId,Request $request)
    {
        $count = $this->user->cats()->where('item_id','=',$itemId)->count();
        if($count == 0) {
            $this->user->cats()->attach($itemId);
        }
        return api()->created();
    }



}