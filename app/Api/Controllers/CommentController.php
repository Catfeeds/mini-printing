<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2018/5/3
 * Time: 17:18
 * Function:
 */

namespace App\Api\Controllers;


use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\CommentImage;
use App\Models\Item;
use App\Models\Order;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * CommentController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => 'show']);
    }

    /**
     * @param Item $item
     * @return \Tanmo\Api\Http\Response
     */
    public function show(Item $item)
    {
        $comments = $item->comments()->paginate(10);
        $comments->load('images');

        return api()->collection($comments, CommentResource::class);
    }

    /**
     * @param Request $request
     * @param Order $order
     * @return \Tanmo\Api\Http\Response
     */
    public function store(Request $request, Order $order)
    {
        $this->authorize('comment', $order);

        ///
        $order->status = Order::FINISH;
        $order->save();

        ///
        $contents = $request->get('contents');
        $contents = json_decode($contents, true);

        ///
        if (json_last_error() != JSON_ERROR_NONE) {
            return api()->error('Submission of data errors', 400);
        }

        ///
        foreach ($order->items as $item) {
            if (!array_key_exists($item->item_id, $contents)) {
                continue;
            }

            ///
            $content = $contents[$item->item_id];

            ///
            $comment = new Comment();
            $comment->item_id = $item->item_id;
            $comment->order_id = $order->id;
            $comment->user_id = $order->user_id;
            $comment->message = $content['message'];
            $comment->save();

            ///
            if (isset($content['images'])) {
                foreach ($content['images'] as $imageId) {
                    $image = CommentImge::find($imageId);
                    $comment->images()->save($image);
                }
            }
        }

        return api()->created();
    }

    /**
     * @param Request $request
     * @param Order $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(Request $request, Order $order)
    {
        $this->authorize('comment', $order);

        if ($request->hasFile('img')) {
            $path = $request->file('img')->store('comment');

            $image = new CommentImage();
            $image->order_id = $order->id;
            $image->path = $path;
            $image->save();

            return response()->json(['data' => ['image_id' => $image->id]]);
        }

        return api()->errorBadRequest('No img field');
    }
}