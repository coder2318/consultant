<?php

namespace App\Repositories;

use App\Models\Payment;

class PaymentRepository extends BaseRepository
{
    public function __construct(Payment $payment)
    {
        $this->entity = $payment;
    }
}
