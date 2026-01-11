<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     *
     * Configure in .env with TRUSTED_PROXIES
     * Use '*' for all proxies (less secure, but works with most hosting)
     * Or specify exact IPs: '192.168.1.1,10.0.0.1'
     *
     * @var array<int, string>|string|null
     */
    protected $proxies = null;

    /**
     * The headers that should be used to detect proxies.
     *
     * @var int
     */
    protected $headers =
        Request::HEADER_X_FORWARDED_FOR |
        Request::HEADER_X_FORWARDED_HOST |
        Request::HEADER_X_FORWARDED_PORT |
        Request::HEADER_X_FORWARDED_PROTO |
        Request::HEADER_X_FORWARDED_AWS_ELB;

    public function __construct()
    {
        // Load trusted proxies from environment
        $proxies = env('TRUSTED_PROXIES');

        if ($proxies === '*') {
            $this->proxies = '*';
        } elseif ($proxies) {
            $this->proxies = explode(',', $proxies);
        }
    }
}