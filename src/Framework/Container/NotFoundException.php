<?php

namespace TastPHP\Framework\Container;

use Psr\Container\NotFoundExceptionInterface;

/**
 * Class NotFoundException
 * @package TastPHP\Framework\Container
 */
class NotFoundException extends \InvalidArgumentException implements NotFoundExceptionInterface
{

}