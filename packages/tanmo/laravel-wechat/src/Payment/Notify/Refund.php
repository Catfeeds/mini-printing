<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2018/5/21
 * Time: 16:34
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

namespace Tanmo\Wechat\Payment\Notify;


use Tanmo\Wechat\Support\XML;

class Refund extends Notify
{
    /**
     * @param \Closure $closure
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function handle(\Closure $closure)
    {
        $this->strict(
            call_user_func($closure, $this->getMessage(), $this->reqInfo(), [$this, 'fail'])
        );

        return $this->toResponse();
    }

    /**
     * Decrypt the `req_info` from request message.
     *
     * @return array
     */
    public function reqInfo()
    {
        return XML::parse($this->decryptMessage('req_info'));
    }
}