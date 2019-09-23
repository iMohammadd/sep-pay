<?php

namespace Aries\Seppay\Traits;

trait Request {
    private function send_request($url, array $data, $toObject = false)
    {
        $fields = $this->create_data($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "{$url}");
        curl_setopt($ch, CURLOPT_POSTFIELDS, "$fields");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $res = curl_exec($ch);
        curl_close($ch);

        return json_decode($res, $toObject);
    }

    private function create_data(array $data)
    {
        $length = count($data);
        $index = 0;
        $string = "";
        foreach ($data as $key => $value) {
            if($value != null)
                $string .= $key.'='.$value;
            if($index<$length-1) {
                $index++;
                $string .='&';
            }
        }

        return $string;
    }
}