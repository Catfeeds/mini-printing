<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2018/4/24
 * Time: 16:20
 * Function:
 */

namespace Tanmo\Wechat\Payment\Notify;


class Paid extends Notify
{
    /**
     * @param \Closure $closure
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function handle(\Closure $closure)
    {
        $this->strict(call_user_func($closure, $this->getMessage(), [$this, 'fail']));

        return $this->toResponse();
    }
}