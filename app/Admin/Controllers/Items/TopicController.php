<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2018/5/3
 * Time: 16:12
 * Function:
 */

namespace App\Admin\Controllers\Items;


use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Topic;
use Illuminate\Http\Request;

/**
 * @module 专题管理
 *
 * Class TopicController
 * @package App\Admin\Controllers\Items
 */
class TopicController extends Controller
{
    /**
     * @permission 专题列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $topics = Topic::paginate(10);

        return view('admin::items.topics', compact('topics'));
    }

    /**
     * @permission 创建专题-页面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $items = Item::all();
        return view('admin::items.topic-create', compact('items'));
    }

    /**
     * @permission 创建专题
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $data = [
            'name' => $request->get('name')
        ];

        $topic = Topic::create($data);

        if ($topic) {
            $itemIds = array_filter($request->get('items'));
            $topic->items()->sync($itemIds);
        }

        return redirect()->route('admin::topics.index');
    }

    /**
     * @permission 修改专题-页面
     *
     * @param Topic $topic
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Topic $topic)
    {
        $items = Item::all();
        return view('admin::items.topic-edit', compact('topic', 'items'));
    }

    /**
     * @permission 修改专题
     *
     * @param Request $request
     * @param Topic $topic
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Topic $topic)
    {
        $topic->name = $request->name;

        $itemIds = array_filter($request->get('items'));
        if (!empty($itemIds)) {
            $topic->items()->sync($itemIds);
        }

        $topic->save() ? admin_toastr('修改成功') : admin_toastr('修改失败', 'danger');

        return redirect()->route('admin::topics.index');
    }

    /**
     * @permission 删除专题
     *
     * @param Topic $topic
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Topic $topic)
    {
        $topic->items()->detach();
        $topic->delete();

        return response()->json(['status' => 1, 'message' => '成功']);
    }
}