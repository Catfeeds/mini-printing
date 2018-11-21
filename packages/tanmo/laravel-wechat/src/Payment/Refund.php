<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2018/5/21
 * Time: 17:32
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

namespace Tanmo\Wechat\Payment;


use Tanmo\Wechat\Support\BaseClient;

class Refund extends BaseClient
{
    /**
     * @param string $outTradeNumber
     * @param string $outRefundNumber
     * @param int $totalFee
     * @param int $refundFee
     * @param array $optional
     * @return array
     */
    public function byOutTradeNumber(string $outTradeNumber, string $outRefundNumber, int $totalFee, int $refundFee, array $optional = [])
    {
        return $this->refund($outRefundNumber, $totalFee, $refundFee, array_merge($optional, ['out_trade_no' => $outTradeNumber]));
    }

    /**
     * @param string $transactionId
     * @param string $outRefundNumber
     * @param int $totalFee
     * @param int $refundFee
     * @param array $optional
     * @return array
     */
    public function byTransactionId(string $transactionId, string $outRefundNumber, int $totalFee, int $refundFee, array $optional = [])
    {
        return $this->refund($outRefundNumber, $totalFee, $refundFee, array_merge($optional, ['transaction_id' => $transactionId]));
    }

    /**
     * @param string $transactionId
     * @return array
     */
    public function queryByTransactionId(string $transactionId)
    {
        return $this->query('transaction_id', $transactionId);
    }

    /**
     * @param string $outTradeNumber
     * @return array
     */
    public function queryByOutTradeNumber(string $outTradeNumber)
    {
        return $this->query('out_trade_no', $outTradeNumber);
    }

    /**
     * @param string $outRefundNumber
     * @return array
     */
    public function queryByOutRefundNumber(string $outRefundNumber)
    {
        return $this->query('out_refund_no', $outRefundNumber);
    }

    /**
     * @param string $refundId
     * @return array
     */
    public function queryByRefundId(string $refundId)
    {
        return $this->query('refund_id', $refundId);
    }

    /**
     * @param string $outRefundNumber
     * @param int $totalFee
     * @param int $refundFee
     * @param array $optional
     * @return array
     */
    protected function refund(string $outRefundNumber, int $totalFee, int $refundFee, $optional = [])
    {
        $params = array_merge([
            'out_refund_no' => $outRefundNumber,
            'total_fee' => $totalFee,
            'refund_fee' => $refundFee
        ], $optional);

        ///
        return $this->safeRequest('https://api.mch.weixin.qq.com/secapi/pay/refund', $params);
    }

    /**
     * @param string $type
     * @param string $number
     * @return array
     */
    protected function query(string $type, string $number)
    {
        $params = [
            $type => $number
        ];

        return $this->request('https://api.mch.weixin.qq.com/pay/refundquery', $params);
    }
}