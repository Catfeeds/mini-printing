<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2018/5/3
 * Time: 9:16
 * Function:
 */

namespace App\Api\Controllers;


use App\Http\Controllers\Controller;
use App\Http\Resources\ItemResource;
use App\Models\Topic;

class TopicController extends Controller
{
    /**
     * @param Topic $topic
     * @return \Tanmo\Api\Http\Response
     */
    public function show(Topic $topic)
    {
        $topic->items->load('covers');
        return api()->collection($topic->items()->paginate(10), ItemResource::class);
    }
}