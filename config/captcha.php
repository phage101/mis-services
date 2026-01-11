<?php

return [
    'secret' => env('NOCAPTCHA_SECRET', '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe'),
    'sitekey' => env('NOCAPTCHA_SITEKEY', '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI'),
    'options' => [
        'timeout' => 30,
    ],
];
