<?php

/*
 * This file is part of JSON-API.
 *
 * (c) Toby Zerner <toby.zerner@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tobscure\Tests\JsonApi;

use Exception;
use Tobscure\JsonApi\ErrorHandler;
use Yoast\PHPUnitPolyfills\Polyfills\ExpectException;

class ErrorHandlerTest extends AbstractTestCase
{
    use ExpectException;

    public function test_it_should_throw_an_exception_when_no_handlers_are_present()
    {
        $this->expectException(\RuntimeException::class);

        $handler = new ErrorHandler;

        $handler->handle(new Exception);
    }
}
