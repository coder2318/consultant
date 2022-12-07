<?php

if (! function_exists('dealDataForm')) {
    function dealDataForm($type, $chat_id, $status, $payment_verified = false, $from_profile_id = null)
    {
        $data = [
            'type' => $type,
            'chat_id' => $chat_id,
            'status' => $status,
            'payment_verified' => $payment_verified,
            'from_profile_id' => $from_profile_id,
        ];
        broadcast(new \App\Events\DealEvent($data));
        return $data;
    }
}




