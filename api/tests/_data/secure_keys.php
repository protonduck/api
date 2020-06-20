<?php

use common\models\SecureKey;

return [
    'confirm_email' => [
        'id' => 1,
        'user_id' => 3,
        'type' => SecureKey::TYPE_ACTIVATE,
        'code' => 'xNRzByv_61YBfYkvuonqzhXzEu5hY0v9',
        'status' => SecureKey::STATUS_NEW,
        'valid_to' => date('Y-m-d H:i:s', strtotime('+3 days')),
        'created_at' => '2020-06-23 10:00:00',
        'updated_at' => '2020-06-23 10:00:00',
    ],
];
