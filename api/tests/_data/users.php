<?php

use api\enums\Language;
use api\enums\UserRole;
use api\enums\UserStatus;

return [
    'simple' => [
        'id' => 1,
        'name' => 'John Dow',
        'email' => 'john@example.com',
        'new_email' => null,
        'password_hash' => '$2y$13$rDrZxCfkqzeBvW0soFdVMeJ4VQDdJnBg7I6nr3U0xB2Qn/7kTay1K', // 11111111
        'premium_until' => null,
        'language' => Language::EN,
        'status' => UserStatus::ACTIVE,
        'role' => UserRole::USER,
        'auth_key' => '1wTNae9t34OmnK6l4vT4IeaTk-YWI2hv',
        'api_key' => 'kosXxTGbIGomtEoQlQKtMsXJAXlQLNCn',
        'created_at' => '2020-06-21 10:00:00',
        'updated_at' => '2020-06-21 10:00:00',
    ],
    'premium' => [
        'id' => 2,
        'name' => 'Bill Gates',
        'email' => 'billy@microsoft.com',
        'new_email' => null,
        'password_hash' => '$2y$13$DUdNt63ckQd7KlYjgljpLOYGA6N7XntwSqGJ40DwkQzrBumphiG6a', // 22222222
        'premium_until' => date('Y-m-d H:i:s', strtotime('+1 year')),
        'language' => Language::EN,
        'status' => UserStatus::ACTIVE,
        'role' => UserRole::USER,
        'auth_key' => 'MIZUESbDSKS2eoWcfD9IKBW_K-Q_LXID',
        'api_key' => '8vyrkNkSU3pr_LMVVHwETtSfLLYUAY99',
        'created_at' => '2020-06-22 10:00:00',
        'updated_at' => '2020-06-22 10:00:00',
    ],
    'pending' => [
        'id' => 3,
        'name' => 'Sleeping Mike',
        'email' => 'mike@yahoo.com',
        'new_email' => null,
        'password_hash' => '$2y$13$grUMEIX13HuuI6qVLdpUCup1f1eYXdlcIYhdFllnuN.V5Ir4myRS2', // 33333333
        'premium_until' => null,
        'language' => Language::EN,
        'status' => UserStatus::PENDING,
        'role' => UserRole::USER,
        'auth_key' => '4AaHGyUbq4G4yjo3SS29uq2IIjwsZkAn',
        'api_key' => 'mMk97LOFz-GZZldLeSy7rmaDNxq_mb1X',
        'created_at' => '2020-06-23 10:00:00',
        'updated_at' => '2020-06-23 10:00:00',
    ],
    'banned' => [
        'id' => 4,
        'name' => 'Bad Boy',
        'email' => 'dude@haker.org',
        'new_email' => null,
        'password_hash' => '$2y$13$Cqf5tNZre4oNs4IWl1AQwOmNFfV2gHLR8iTtxSxPYPUOMKb6tnapG', // 44444444
        'premium_until' => null,
        'language' => Language::EN,
        'status' => UserStatus::BANNED,
        'role' => UserRole::USER,
        'auth_key' => 'kSQ8jnj-MUpe34TmYATb_LAQ3HzK0vlq',
        'api_key' => 'wTZwf7u_CokchCTjleMqBPcLsHvQVk9M',
        'created_at' => '2020-06-24 10:00:00',
        'updated_at' => '2020-06-24 10:00:00',
    ],
];
