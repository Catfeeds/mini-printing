<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2018/4/20
 * Time: 17:40
 * Function:
 */

namespace App\Admin\Controllers\Home;


use App\Http\Controllers\Controller;
use App\Models\Navigation;
use Illuminate\Http\Request;

/**
 * @module 分类导航
 *
 * Class NavigationController
 * @package App\Admin\Controllers\Home
 */
class NavigationController extends Controller
{
    /**
     * @var array
     */
    protected $types = [
//        'url' => '链接',
//        'goods' => '商品ID',
        'all_goods' => '所有商品',
        'category' => '分类ID'
    ];

    /**
     * @permission 导航列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $navigations = Navigation::all();
        return view('admin::home.navigations', compact('navigations'));
    }

    /**
     * @permission 新建导航-页面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $category = \DB::table('item_categories')->select('id','name')->get();
        $category = $category->toArray();
        return view('admin::home.navigation-create', ['types' => $this->types,'category'=>$category]);
    }

    /**
     * @permission 新建导航
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $navigation = new Navigation();
        $navigation->title = $request->get('title');
        $navigation->order = $request->get('order');
        $input_type = $request->get('type');
        $input_target = $request->get('target') ?? '';
        $navigation->status = $request->get('status');

        if ($input_type == "all_goods"){
            $input_target = '';
        }

        $navigation->type = $input_type;
        $navigation->target = $input_target;

        if ($request->hasFile('icon')) {
            $navigation->icon = $request->file('icon')->store('navigation_icons', 'public');
        }
        $navigation->save();

        return redirect()->route('admin::navigations.index');
    }

    /**
     * @permission 修改导航-页面
     *
     * @param Navigation $navigation
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Navigation $navigation)
    {
        $types = $this->types;
        $category = \DB::table('item_categories')->select('id','name')->get();
        $category = $category->toArray();
        return view('admin::home.navigation-edit', compact('navigation', 'types','category'));
    }

    /**
     * @permission 修改导航
     *
     * @param Navigation $navigation
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Navigation $navigation, Request $request)
    {
        $navigation->title = $request->get('title');
        $navigation->order = $request->get('order');
        $input_type = $request->get('type');
        $input_target = $request->get('target') ?? '';
        $navigation->status = $request->get('status');

        if ($input_type == "all_goods"){
            $input_target = '';
        }

        $navigation->type = $input_type;
        $navigation->target = $input_target;

        if ($request->hasFile('icon')) {
            $navigation->icon = $request->file('icon')->store('navigation_icons', 'public');
        }
        $navigation->save();

        return redirect()->route('admin::navigations.index');
    }

    /**
     * @permission 删除导航
     *
     * @param Navigation $navigation
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Navigation $navigation)
    {
        $navigation->delete();

        return response()->json(['status' => 1, 'message' => '成功']);
    }
}