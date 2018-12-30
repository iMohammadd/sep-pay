<?php

namespace Aries\Seppay;

use Aries\Seppay\BankException;

class VerifyException extends BankException
{
    public static $errors = [
        -1  =>  'ارسال api الزامی می باشد.',
        -2  =>  'ارسال transId الزامی می باشد.',
        -3  =>  'درگاه پرداختی با api ارسالی یافت نشد و یا غیر فعال می باشد.',
        -4  =>  'فروشنده غیر فعال می باشد.',
        -5  =>  'تراکنش با خطا مواجه شده است.',
        -6  =>  'این تراکنش قبلا وریفای شده است.',
        -7  =>  'مبلغ تراکنش با مبلغ بازگشتی از بانک مغایر است.'
    ];

    public function __construct($errorId)
    {
        $this->errorId = intval($errorId);

        parent::__construct(@self::$errors[$this->errorId], $this->errorId);
    }
}