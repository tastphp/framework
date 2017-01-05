<?php

namespace TastPHP\Framework\CsrfToken;

use Schnittstabil\Csrf\TokenService\TokenService;

/**
 * Class CsrfTokenService
 * @package TastPHP\Framework\CsrfToken
 */
class CsrfTokenService extends TokenService
{
    public function __construct($key, $ttl, $algo = 'SHA512')
    {
        parent::__construct($key, $ttl, $algo);
    }
}