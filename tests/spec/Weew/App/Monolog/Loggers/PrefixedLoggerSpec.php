<?php

namespace tests\spec\Weew\App\Monolog\Loggers;

use Monolog\Logger;
use PhpSpec\ObjectBehavior;
use Psr\Log\LoggerInterface;
use stdClass;
use Weew\App\Monolog\Loggers\PrefixedLogger;

/**
 * @mixin PrefixedLogger
 */
class PrefixedLoggerSpec extends ObjectBehavior {
    function it_is_initializable(LoggerInterface $logger) {
        $this->beConstructedWith($logger, 'prefix');
        $this->shouldHaveType(PrefixedLogger::class);
        $this->beAnInstanceOf(LoggerInterface::class);
    }

    function it_returns_logger(LoggerInterface $logger) {
        $this->beConstructedWith($logger, 'prefix');
        $this->getLogger()->shouldBe($logger);
    }

    function it_returns_prefix(LoggerInterface $logger) {
        $this->beConstructedWith($logger, 'prefix');
        $this->getPrefix()->shouldBe('prefix');
    }

    function it_forwards_calls_to_logger(Logger $logger) {
        $this->beConstructedWith($logger, 'prefix');
        $logger->getName()->willReturn('name');
        $logger->getName()->shouldBeCalled();
        $this->getName()->shouldBe('name');
    }

    function it_prefixes_emergency_messages(LoggerInterface $logger) {
        $this->beConstructedWith($logger, 'prefix');
        $logger->emergency('prefix: message', [])->shouldBeCalled();
        $this->emergency('message');
    }

    function it_prefixes_alert_messages(LoggerInterface $logger) {
        $this->beConstructedWith($logger, 'prefix');
        $logger->alert('prefix: message', [])->shouldBeCalled();
        $this->alert('message');
    }

    function it_prefixes_critical_messages(LoggerInterface $logger) {
        $this->beConstructedWith($logger, 'prefix');
        $logger->critical('prefix: message', [])->shouldBeCalled();
        $this->critical('message');
    }

    function it_prefixes_error_messages(LoggerInterface $logger) {
        $this->beConstructedWith($logger, 'prefix');
        $logger->error('prefix: message', [])->shouldBeCalled();
        $this->error('message');
    }

    function it_prefixes_warning_messages(LoggerInterface $logger) {
        $this->beConstructedWith($logger, 'prefix');
        $logger->warning('prefix: message', [])->shouldBeCalled();
        $this->warning('message');
    }

    function it_prefixes_notice_messages(LoggerInterface $logger) {
        $this->beConstructedWith($logger, 'prefix');
        $logger->notice('prefix: message', [])->shouldBeCalled();
        $this->notice('message');
    }

    function it_prefixes_info_messages(LoggerInterface $logger) {
        $this->beConstructedWith($logger, 'prefix');
        $logger->info('prefix: message', [])->shouldBeCalled();
        $this->info('message');
    }

    function it_prefixes_debug_messages(LoggerInterface $logger) {
        $this->beConstructedWith($logger, 'prefix');
        $logger->debug('prefix: message', [])->shouldBeCalled();
        $this->debug('message');
    }

    function it_prefixes_log_messages(LoggerInterface $logger) {
        $this->beConstructedWith($logger, 'prefix');
        $logger->log('level', 'prefix: message', [])->shouldBeCalled();
        $this->log('level', 'message');
    }

    function it_works_without_prefix(LoggerInterface $logger) {
        $this->beConstructedWith($logger);
        $logger->log('level', 'message', [])->shouldBeCalled();
        $this->log('level', 'message');
    }

    function it_uses_class_name_as_prefix_if_possible(LoggerInterface $logger) {
        $this->beConstructedWith($logger, new stdClass());
        $logger->log('level', 'stdClass: message', [])->shouldBeCalled();
        $this->log('level', 'message');
    }
}
