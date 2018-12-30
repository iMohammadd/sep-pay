<?php
namespace Aries\Seppay\Traits;

trait Data {
    private $token;
    private $amount;
    private $callback;
    private $factorNumber;
    private $mobile;
    private $description;

    public function amount($amount)
    {
        $this->amount = $amount;
    }

    public function callback($url)
    {
        $this->callback = urlencode($url);
    }

    public function factorNumber($number = null)
    {
        $this->factorNumber = $number;
    }

    public function mobile($mobile = null) {
        $this->mobile = $mobile;
    }

    public function description($description = null) {
        $this->description = $description;
    }
}