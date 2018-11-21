<?php

namespace App\Policies;

use App\Models\OrderRefund;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Tanmo\Admin\Models\Administrator;

class OrderRefundPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @param OrderRefund $orderRefund
     * @return bool
     */
    public function show(User $user, OrderRefund $orderRefund)
    {
        return $user->id === $orderRefund->user_id;
    }

    /**
     * @param User $user
     * @param OrderRefund $orderRefund
     * @return bool
     */
    public function cancel(User $user, OrderRefund $orderRefund)
    {
        $cancelableStatus = [
            OrderRefund::APPLYING,
            OrderRefund::REFUSE
        ];

        if (! in_array($orderRefund->status, $cancelableStatus)) {
            return false;
        }

        return $user->id === $orderRefund->user_id;
    }

    /**
     * @param Administrator $user
     * @param OrderRefund $orderRefund
     * @return bool
     */
    public function handle(Administrator $user, OrderRefund $orderRefund)
    {
        return $orderRefund->status === OrderRefund::APPLYING;
    }
}
