<?php
namespace Aries\Seppay;

use Aries\Seppay\Traits\Data;
use Aries\Seppay\Traits\Request;

class Pay {

    use Data, Request;

    public function __construct()
    {
        $this->factorNumber = null;
    }

    public function ready()
    {
        $params                 =   [];
        $params['api']          =   config('Seppay.api');
        $params['amount']       =   $this->amount;
        $params['factorNumber'] =   $this->factorNumber;
        $params['redirect']     =   $this->callback;
        $params['mobile']       =   $this->mobile;
        $params['description']  =   $this->description;

        # Send initial request to Webservice
        $res = $this->send_request("https://pay.ir/pg/send", $params, false);

        # If Webservice send {status} as 1 mean we haven't error and can save token for next step
        if($res->status == 1) {
            $this->token = $res->token;
        } else {
            # If Webservice send {status} as not 1 mean we have error
            # then pass errorCode to SendException.php to show error message
            throw new SendException($res->errorCode);
        }

        return $res;
    }

    public function start()
    {
        # Redirect to Pay.ir for complete payment
        return redirect()->to("https://pay.ir/pg/". $this->token);
    }

    public function verify()
    {
        # Pay.ir send us a {token} and {transaction_status}, put them to $params array
        $params['token']      = $_REQUEST['token'];
        $params['api']  = config('Seppay.api');

        # find and put transaction from our database to $transaction
        $transaction    = \DB::table('transactions')->where('transId', '=', $params['token']);

        # check the $transaction status, if verified before show a error message
        if ($transaction->first()->status != 'INIT') {
            throw new VerifyException(-6);
        }

        # send verification request to Webservice and put result to $res
        $res            = $this->send_request("https://pay.ir/pg/verify", $params);

        # If Webservice send us {status} as not 1 mean we have error
        # update $transaction status to FAILED
        if($res->status != 1) {
            $transaction->update([
                'status' => 'FAILED'
            ]);
            throw new VerifyException($res->errorCode);
        }

        if ($transaction->first()->amount != $res->amount) {
            $transaction->update([
                'status'    =>  'FAILED'
            ]);

            throw new VerifyException(-7);
        }

        # If Webservice send us {status} as 1 mean we haven't error
        # update $transaction status to SUCCESS and store cardNumber to $transaction record
        $transaction->update([
            'status'        =>  'SUCCESS',
            'cardNumber'    =>  $res->cardNumber
        ]);

        return $res;
    }
}