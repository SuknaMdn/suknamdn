<?php

namespace App\Services;

use App\Exceptions\OtpException;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use HossamMonir\Msegat\Facades\Msegat;
use App\Notifications\Developer\UnitOrderNotification;

class OrderService
{
    public function CreateOrder($order_id){

        // $orderDetails = [
        //     'developer_id' => auth()->user()->id,
        //     'unit_name' => 'Unit 1',
        //     'unit_id' => '1'
        // ];
        // $notification = auth()->user()->notify(new UnitOrderNotification($orderDetails));

    }
}
