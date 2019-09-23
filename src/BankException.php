<?php
namespace Aries\Seppay;

class BankException extends \Exception {
    protected $code = -100;
    protected $message = 'خطای بانک';
}