<?php

namespace TastPHP\Framework\Event;

use Symfony\Component\EventDispatcher\Event;

final class AppEvent extends Event
{
    const INIT = "app.init";

    const RESPONSE = "app.response";

    const REQUEST = "app.request";

    const EXCEPTION = "app.exception";

    const NOTFOUND = "app.notfound";

    const HTTPFINISH = "app.httpfinish";

    const MIDDLEWARE = "app.middleware";
}
