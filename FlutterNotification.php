<?php
function notifications($message, $token, $body, $title, $dataset = null, $type)
{
    $array = array();
    $config = config('app.carrier_firebase_fcm_token');
    if ($type == 'driver') {
        $config = config('app.driver_firebase_fcm_token');
        array_push($array, $config);
    }
    $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
    $headers = [
        'Authorization: key=' . $config,
        'Content-Type: application/json',
    ];

    $message['dataset'] = $dataset;
    $message['click_action'] = "FLUTTER_NOTIFICATION_CLICK";

    $fcmNotification = [
        "data" => $message,
        "notification" => [
            "body" => $body,
            "title" => !empty($title) ? $title : 'Laravel',
            "badge" => "1",
            "sound" => "default",
        ],
        "registration_ids" => $token,
        "content_available" => true,
        "priority" => "high",
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $fcmUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
    $result = curl_exec($ch);

    if ($result === false) {
        die('Oops! FCM Send Error: ' . curl_error($ch));
    }

    curl_close($ch);
    return true;
}

// notification
function sendUserReviewNotification($user_id, $code, $message, $title, $event_id = null, $event_type = null, $type = 'carrier')
{
    // pluck fcm tokens from user devices
    $tokens = Device::where('resource_id', $user_id)
        ->distinct()
        ->pluck('fcm_token')
        ->toArray();

    if (!empty($tokens)) {
        $body = $message;

        $message = [
            'code' => $code,
            'user_id' => $user_id,
            'event_type' => $event_type,
            'event_id' => $event_id,
        ];

        notifications($message, $tokens, $body, $title, null, $type);
    }
}
