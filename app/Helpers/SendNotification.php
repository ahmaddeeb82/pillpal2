<?php

namespace App\Helpers;

class SendNotification {
    public static function send($tokens, $title, $body)
    {

    $SERVER_API_KEY = 'AAAA684HzUk:APA91bEB2RkfxLZBPLDwL85QCbqXs-7r3_sctDoOiwtIo_p-MURrCx7A9v5X8uFvtJEVbr4B911vJcXduDyB4oMBYM9dVO2n6iBOZz8dI3sOeAmC8magCH-zQq0daXLnimh6MqW3K31K';


    $data = [

        "to" => $tokens,

        "notification" => [

            "title" => $title,

            "body" => $body,

            "sound"=> "default" // required for sound on ios

        ],

    ];

    $dataString = json_encode($data);

    $headers = [

        'Authorization: key=' . $SERVER_API_KEY,

        'Content-Type: application/json',

    ];

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');

    curl_setopt($ch, CURLOPT_POST, true);

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

    $response = curl_exec($ch);

    //return $response;

    }
}
