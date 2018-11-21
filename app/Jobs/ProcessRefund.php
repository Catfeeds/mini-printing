<?php

namespace App\Jobs;

use App\Models\OrderRefund;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Tanmo\Wechat\Facades\Payment;

class ProcessRefund implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var OrderRefund
     */
    protected $orderRefund;

    /**
     * Create a new job instance.
     *
     * @param OrderRefund $orderRefund
     */
    public function __construct(OrderRefund $orderRefund)
    {
        $this->orderRefund = $orderRefund;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $optional = [
            'refund_account' => 'REFUND_SOURCE_RECHARGE_FUNDS',
//            'notify_url' => url()->route('wechat.refund_notify')
        ];

        $res = Payment::app()->refund()->byOutTradeNumber(
            $this->orderRefund->order->sn,
            $this->orderRefund->sn,
            $this->orderRefund->order->price * 100,
            $this->orderRefund->price * 100,
            $optional
        );

        if ($res['return_code'] === 'SUCCESS') {
            if ($res['result_code'] === 'SUCCESS') {
                $this->orderRefund->status = OrderRefund::SUCCESS;
            }
            else {
                $this->orderRefund->status = OrderRefund::REFUNDING;
                Log::error(sprintf('Refund Fail:refund_id[%s]:[%s]', $this->orderRefund->id, $res['err_code_des']));
            }

            $this->orderRefund->save();
        }
        else {
            Log::error(sprintf('Refund Error:refund_id[%s]:[%s]', $this->orderRefund->id, $res['return_msg']));
        }
    }
}
