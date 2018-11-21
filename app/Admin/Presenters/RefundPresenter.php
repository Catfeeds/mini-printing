<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2018/5/10
 * Time: 15:06
 *
 *                                _oo8oo_
 *                               o8888888o
 *                               88" . "88
 *                               (| -_- |)
 *                               0\  =  /0
 *                             ___/'==='\___
 *                           .' \\|     |// '.
 *                          / \\|||  :  |||// \
 *                         / _||||| -:- |||||_ \
 *                        |   | \\\  -  /// |   |
 *                        | \_|  ''\---/''  |_/ |
 *                        \  .-\__  '-'  __/-.  /
 *                      ___'. .'  /--.--\  '. .'___
 *                   ."" '<  '.___\_<|>_/___.'  >' "".
 *                  | | :  `- \`.:`\ _ /`:.`/ -`  : | |
 *                  \  \ `-.   \_ __\ /__ _/   .-` /  /
 *              =====`-.____`.___ \_____/ ___.`____.-`=====
 *                                `=---=`
 *
 *
 *             ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 *
 *                         佛祖保佑    永无BUG
 *
 */

namespace App\Admin\Presenters;


use App\Models\OrderRefund;

class RefundPresenter
{
    /**
     * @var array
     */
    protected $statusMap = [
        OrderRefund::CANCEL => '已取消',
        OrderRefund::APPLYING => '申请中',
        OrderRefund::AGREE => '已同意',
        OrderRefund::REFUNDING => '退单中',
        OrderRefund::SUCCESS => '已退单',
        OrderRefund::REFUSE => '已拒绝'
    ];

    /**
     * @var array
     */
    protected $colorsMap = [
        OrderRefund::CANCEL => 'bg-gray',
        OrderRefund::APPLYING => 'bg-blue',
        OrderRefund::AGREE => 'bg-yellow',
        OrderRefund::REFUNDING => 'bg-orange',
        OrderRefund::SUCCESS => 'bg-green',
        OrderRefund::REFUSE => 'bg-red'
    ];

    /**
     * @param OrderRefund $refund
     * @return string
     */
    public function status(OrderRefund $refund)
    {
        if (!array_key_exists($refund->status, $this->statusMap)) {
            return '';
        }

        return "<span class=\"badge {$this->colorsMap[$refund->status]}\">{$this->statusMap[$refund->status]}</span>";
    }
}