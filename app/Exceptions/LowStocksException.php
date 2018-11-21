<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2018/4/27
 * Time: 17:21
 * Function:
 */

namespace App\Exceptions;


use Tanmo\Api\Exceptions\ApiException;

class LowStocksException extends ApiException
{
    /**
     * LowStocksException constructor.
     * @param int $itemId
     */
    public function __construct($itemId)
    {
        parent::__construct(423, "库存不足或已下架");

        $this->response->setMeta(['id' => $itemId]);
    }
}