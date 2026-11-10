<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    protected $proxies;

    protected $headers =
        Request::HEADER_X_FORWARDED_FOR |
        Request::HEADER_X_FORWARDED_HOST |
        Request::HEADER_X_FORWARDED_PORT |
        Request::HEADER_X_FORWARDED_PROTO |
        Request::HEADER_X_FORWARDED_PREFIX;

    public function __construct()
    {
        if (app()->environment('local')) {
            $this->proxies = '*';
            return;
        }

        $raw = config('app.trusted_proxies', '');
        
        $this->proxies = $raw === ''
            ? []
            : array_map('trim', explode(',', $raw));
    }
}