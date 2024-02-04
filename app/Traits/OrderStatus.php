<?php

namespace App\Traits;

use App\Models\Shop\Order;

trait OrderStatus
{
    public function attachStatus(string $status)
    {
        return $this->status()->firstOrCreate([
            'label' => $status,
        ]);
    }

    public function detachStatus(array $status)
    {
        return $this->status()->whereIn('label', $status)->delete();
    }

    public function updateStatus($collection, $status)
    {
        // return if status doesn't contain to collection
        if (!$collection->contains($status)) {
            return false;
        }

        // remove current status if available
        $this->detachStatus($collection->diff([$status])->all());

        // attach the status
        return $this->attachStatus($status);
    }

    public function generalStatus(string $status)
    {
        return $this->updateStatus(collect([
            Order::STATUS_OPEN,
            Order::STATUS_PENDING,
            Order::STATUS_COMPLETED,
            Order::STATUS_CANCELLED,
            Order::STATUS_DECLINED,
            Order::STATUS_DISPUTED,
        ]), $status);
    }

    public function paymentStatus(string $status)
    {
        return $this->updateStatus(collect([
            Order::STATUS_PAYMENT_PENDING,
            Order::STATUS_PAYMENT_FAILED,
            Order::STATUS_PAYMENT_SUCCESS,
            Order::STATUS_PARTIALLY_PAID,
            Order::STATUS_PAID,
        ]), $status);
    }

    public function refundStatus(string $status)
    {
        return $this->updateStatus(collect([
            Order::STATUS_REFUNDED,
            Order::STATUS_PARTIALLY_REFUNDED,
        ]), $status);
    }

    public function markedAsOpen()
    {
        $this->generalStatus(Order::STATUS_OPEN);
    }

    public function markedAsPending()
    {
        $this->generalStatus(Order::STATUS_PENDING);
    }

    public function markedAsCompleted()
    {
        $this->generalStatus(Order::STATUS_COMPLETED);
    }

    public function markedAsCancelled()
    {
        $this->generalStatus(Order::STATUS_CANCELLED);
    }

    public function markedAsArchived()
    {
        $this->attachStatus(Order::STATUS_ARCHIVED);
    }

    public function markedAsUnarchived()
    {
        $this->detachStatus([Order::STATUS_ARCHIVED]);
    }

    public function markedAsPaid()
    {
        $this->paymentStatus(Order::STATUS_PAID);
    }

    public function markedAsPartiallyPaid()
    {
        $this->paymentStatus(Order::STATUS_PARTIALLY_PAID);
    }

    public function markedAsPaymentPending()
    {
        $this->paymentStatus(Order::STATUS_PAYMENT_PENDING);
    }

    public function markedAsPaymentFailed()
    {
        $this->paymentStatus(Order::STATUS_PAYMENT_FAILED);
    }

    public function markedAsPaymentSuccess()
    {
        $this->paymentStatus(Order::STATUS_PAYMENT_SUCCESS);
    }

    public function markedAsRefunded()
    {
        $this->refundStatus(Order::STATUS_REFUNDED);
    }

    public function markedAsPartiallyRefunded()
    {
        $this->refundStatus(Order::STATUS_PARTIALLY_REFUNDED);
    }

    public function syncCurrentStatus()
    {
        if ($this->refund_total == $this->paid_total) {
            $this->markedAsRefunded();
        } else if ($this->refund_total > 0) {
            $this->markedAsPartiallyRefunded();
        } else {
            $this->detachStatus([Order::STATUS_REFUNDED, Order::STATUS_PARTIALLY_REFUNDED]);
        }
        return $this;
    }

    public function hasStatus(string $status)
    {
        return $this->status->contains('label', $status);
    }

    public function hasAnyStatus(array $status)
    {
        return $this->status->contains(function ($item, $key) use ($status) {
            return in_array($item->label, $status);
        });
    }

    public function getIsCompletedAttribute(): bool
    {
        return $this->hasStatus(Order::STATUS_COMPLETED);
    }

    public function getIsCancelledAttribute(): bool
    {
        return $this->hasStatus(Order::STATUS_CANCELLED);
    }

    public function getIsPaidAttribute(): bool
    {
        return $this->hasStatus(Order::STATUS_PAID);
    }

    public function getCanEditAttribute(): bool
    {
        return !$this->hasStatus(Order::STATUS_CANCELLED) && !$this->hasStatus(Order::STATUS_COMPLETED);
    }

    public function getCanRefundAttribute(): bool
    {
        return $this->hasAnyStatus([Order::STATUS_PAID, Order::STATUS_PARTIALLY_PAID]) && !$this->hasStatus(Order::STATUS_REFUNDED);
    }
}
