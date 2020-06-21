<?php

use common\enums\DomainSslStatus;

return [
    'd1' => [
        'id' => 1,
        'name' => 'opera.com',
        'ssl_status' => DomainSslStatus::EXPIRED,
        'checked_at' => '2020-06-21 10:05:00',
        'created_at' => '2020-06-21 10:00:00',
        'updated_at' => '2020-06-21 10:05:01',
    ],
    'd2' => [
        'id' => 2,
        'name' => 'chrome.com',
        'ssl_status' => DomainSslStatus::TRUSTED,
        'checked_at' => '2020-06-21 10:05:00',
        'created_at' => '2020-06-21 10:00:00',
        'updated_at' => '2020-06-21 10:05:02',
    ],
    'd3' => [
        'id' => 3,
        'name' => 'firefox.org',
        'ssl_status' => DomainSslStatus::NO_SSL,
        'checked_at' => '2020-06-21 10:05:00',
        'created_at' => '2020-06-21 10:00:00',
        'updated_at' => '2020-06-21 10:05:03',
    ],
    'd4' => [
        'id' => 4,
        'name' => 'google.com',
        'ssl_status' => DomainSslStatus::TRUSTED,
        'checked_at' => '2020-06-22 10:05:00',
        'created_at' => '2020-06-22 10:00:00',
        'updated_at' => '2020-06-22 10:05:03',
    ],
];
