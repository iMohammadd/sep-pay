<?php
namespace Aries\Seppay\Traits;

use Aries\Seppay\Models\Transaction;
use Aries\Seppay\Pay;

trait Payable {

    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'payable');
    }

    public function pay($amount, $mobile, $description, $callbackUrl, $factorNumber = null)
    {
        $payment = new Pay();
        $payment->amount($amount);
        $payment->callback($callbackUrl);
        $payment->factorNumber($factorNumber);
        $payment->mobile($mobile);
        $payment->description($description);
        $response = $payment->ready();

        $this->transactions()->create([
            'amount'        =>  $amount,
            'transId'       =>  $response->token,
            'factorNumber'  =>  $factorNumber,
            'mobile'        =>  $mobile,
            'description'   =>  $description
        ]);

        return $payment->start();
    }
}