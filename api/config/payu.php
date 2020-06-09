<?php

return [
    'env' => env('PAYU_ENV', 'sandbox'),
    'pos_id' => env('PAYU_POS_ID'),
    'md5' => env('PAYU_MD5'),
    'client_id' => env('PAYU_CLIENT_ID'),
    'client_secret' => env('PAYU_CLIENT_SECRET')
];