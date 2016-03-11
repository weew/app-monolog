<?php

namespace tests\spec\Weew\App\Monolog;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;
use Weew\App\Monolog\MonologChannelManager;
use Weew\App\Monolog\NamedLogger;

/**
 * @mixin NamedLogger
 */
class NamedLoggerSpec extends ObjectBehavior {
    function let(
        MonologChannelManager $channelManager,
        LoggerInterface $logger
    ) {
        $channelManager->getLogger(Argument::type('string'))
            ->willReturn($logger);
        $this->beConstructedWith($channelManager);
    }

    function it_is_initializable() {
        $this->shouldHaveType(NamedLogger::class);
    }

    function it_proxies_emergency_method_calls_to_original_logger(
        LoggerInterface $logger
    ) {
        $logger->emergency('message', ['context'])->shouldBeCalled();
        $this->emergency('message', ['context']);
    }

    function it_proxies_alert_method_calls_to_original_logger(
        LoggerInterface $logger
    ) {
        $logger->alert('message', ['context'])->shouldBeCalled();
        $this->alert('message', ['context']);
    }

    function it_proxies_critical_method_calls_to_original_logger(
        LoggerInterface $logger
    ) {
        $logger->critical('message', ['context'])->shouldBeCalled();
        $this->critical('message', ['context']);
    }

    function it_proxies_error_method_calls_to_original_logger(
        LoggerInterface $logger
    ) {
        $logger->error('message', ['context'])->shouldBeCalled();
        $this->error('message', ['context']);
    }

    function it_proxies_warning_method_calls_to_original_logger(
        LoggerInterface $logger
    ) {
        $logger->warning('message', ['context'])->shouldBeCalled();
        $this->warning('message', ['context']);
    }

    function it_proxies_notice_method_calls_to_original_logger(
        LoggerInterface $logger
    ) {
        $logger->notice('message', ['context'])->shouldBeCalled();
        $this->notice('message', ['context']);
    }

    function it_proxies_info_method_calls_to_original_logger(
        LoggerInterface $logger
    ) {
        $logger->info('message', ['context'])->shouldBeCalled();
        $this->info('message', ['context']);
    }

    function it_proxies_debug_method_calls_to_original_logger(
        LoggerInterface $logger
    ) {
        $logger->debug('message', ['context'])->shouldBeCalled();
        $this->debug('message', ['context']);
    }

    function it_proxies_log_method_calls_to_original_logger(
        LoggerInterface $logger
    ) {
        $logger->log('level', 'message', ['context'])->shouldBeCalled();
        $this->log('level', 'message', ['context']);
    }
}
