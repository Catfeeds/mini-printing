<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2018/4/26
 * Time: 17:01
 * Function:
 */

namespace App\Admin\Controllers\Users;


use App\Models\User;
use Tanmo\Search\Facades\Search;
use Tanmo\Search\Query\Searcher;

/**
 * @module 会员管理
 *
 * Class UserController
 * @package App\Api\Controllers\Users
 */
class UserController
{
    /**
     * @permission 会员列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $searcher = Search::build(function (Searcher $searcher) {
            $searcher->equal('id');
            $searcher->like('nickname');
            $searcher->like('phone');
        });
        $data = request()->all();
        $users = (new User())->search($searcher)->latest()->paginate(10);

        $header = '会员列表';
        return view('admin::users.users', compact('users','data','header'));
    }

    /**
     * @permission 会员详情
     *
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(User $user)
    {
        return view('admin::users.detail', compact('user'));
    }
}