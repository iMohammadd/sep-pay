***Sep Pay*** [Donate my project](https://me.pay.ir/imohammad)
------------
*Payment Package with Saman Electronic Payment (pay.ir) over iran Shaparak Network for Laravel*



***Update note:***
---------
You need to publish Service Provider again and run migration to update transaction table:
```
php artisan vendor:publish  --provider='Aries\Seppay\SeppayServiceProvider'

php artisan migrate
```

***Installing:***
-------------
run this command:

``composer require aries/seppay``


and run this command:

``php artisan vendor:publish  --provider='Aries\Seppay\SeppayServiceProvider'
``

then run:

``php artisan migrate``


***Usage:***
---------
you can set [pay.ir](https://pay.ir) api key on `config/Seppay.php` or on your `.env` file with `SEP_API_KEY`

you have two way to payment:
1. ***With Trait:***

import `Payable` Trait in a Model you want have Payment:

```php
use Aries\Seppay\Traits\Payable;
```
and on Model class use `Payable` Like this:
```php
class Bill extends Model {
    use Payable;
    ...
}
```
and in your controller you can start a payment like this:
```php
public function payment($id) {
    $bill = Bill::find($id);
    return $bill->pay($amount, $mobile, $description, $callback_url, $factor_number, $valid_card_number);
}
```

__*`$factor_number` and `$valid_card_number` is optional and you can set them as `null` or can dont pass them to `pay()` method*__

2. ***With Using Pay() class:***

```php
<?php

namespace App\Http\Controllers;

use Aries\Seppay\Pay;
use Aries\Seppay\Models\Transaction;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function test()
    {
        $factor_number = 123;
        $amount = 1000;
        $valid_card_number = '6037999999999999';
        try {
            $pay = new Pay();
            $pay->amount($amount);
            $pay->factorNumber($factor_number);
            $pay->validCardNumber($valid_card_number);
            $pay->callback(url('/'));
            $response = $pay->ready();
            
            Transaction::create([
                'amount'            =>  $amount,
                'transId'           =>  $response->token,
                'factorNumber'      =>  $factor_number,
                'validCardNumber'   =>  $valid_card_number,
                'mobile'            =>  '09123456789'
            ]);
            
            /*
             * do anything you want with $response Object
             * Like: store Transaction ID on your cart with: $response->transId;
             */
            
            return $pay->start();

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function callback()
    {
        try {
            $pay = new Pay();
            $response = $pay->verify();
            
            /*
             * if verification was successful you can send order for your customer
             */
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
```

Special thanks to [Mohammad Babaei](https://github.com/mohammad6006)
