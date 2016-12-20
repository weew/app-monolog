<?php

namespace tests\spec\Weew\App\Monolog\Loggers;

use Monolog\Logger as BaseLogger;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Weew\App\Monolog\Loggers\Logger;

/**
 * @mixin Logger
 */
class LoggerSpec extends ObjectBehavior {
    function let() {
        $this->beConstructedWith('name');
    }

    function it_is_initializable() {
        $this->shouldHaveType(Logger::class);
        $this->beAnInstanceOf(BaseLogger::class);
    }
}
