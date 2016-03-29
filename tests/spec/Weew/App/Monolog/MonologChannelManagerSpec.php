<?php

namespace tests\spec\Weew\App\Monolog;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;
use stdClass;
use Weew\App\Monolog\Exceptions\UndefinedChannelException;
use Weew\App\Monolog\IMonologChannelManager;
use Weew\App\Monolog\IMonologConfig;
use Weew\App\Monolog\MonologChannelManager;
use Weew\App\Monolog\MonologConfig;
use Weew\Config\Config;

/**
 * @mixin MonologChannelManager
 */
class MonologChannelManagerSpec extends ObjectBehavior {
    function let() {
        $config = new Config();
        $config->set(s(MonologConfig::LOG_CHANNEL_FILE_PATH, 'channel1'), path('/tmp', uuid(), 'channel1.log'));
        $config->set(s(MonologConfig::LOG_CHANNEL_FILE_PATH, 'channel2'), path('/tmp', uuid(), 'channel2.log'));
        $config->set(s(MonologConfig::LOG_CHANNEL_LOG_LEVEL, 'channel1'), 'debug');
        $config->set(s(MonologConfig::LOG_CHANNEL_LOG_LEVEL, 'channel2'), 'debug');
        $config->set(MonologConfig::DEFAULT_CHANNEL_NAME, 'channel1');
        $monologConfig = new MonologConfig($config);

        $this->beConstructedWith($monologConfig);
    }
    
    function it_is_initializable() {
        $this->shouldHaveType(MonologChannelManager::class);
    }
    
    function it_implements_imonolog_channel_manager() {
        $this->beAnInstanceOf(IMonologChannelManager::class);
    }

    function it_returns_local_loggers() {
        $this->getLoggers()->shouldBe([]);
    }

    function it_returns_used_config() {
        $this->getConfig()->shouldHaveType(IMonologConfig::class);
    }

    function it_returns_a_new_channel_and_stores_it_locally() {
        $this->getLoggers()->shouldBe([]);
        $logger = $this->getLogger('channel1');
        $logger->shouldHaveType(LoggerInterface::class);

        $this->getLoggers()->shouldBe([
            'channel1-channel1' => $logger->getWrappedObject(),
        ]);
        $logger->getName()->shouldBe('channel1');
    }

    function it_creates_a_logger_with_different_name() {
        $this->getLoggers()->shouldBe([]);
        $logger = $this->getLogger('channel1', 'name1');
        $logger->shouldHaveType(LoggerInterface::class);

        $this->getLoggers()->shouldBe([
            'channel1-name1' => $logger->getWrappedObject(),
        ]);
        $logger->getName()->shouldBe('name1');
    }

    function it_creates_a_logger_for_class() {
        $object = new stdClass();
        $logger = $this->getLoggerForClass($object, 'channel2');
        $logger->shouldHaveType(LoggerInterface::class);

        $this->getLoggers()->shouldBe([
            'channel2-stdClass' => $logger->getWrappedObject(),
        ]);
        $logger->getName()->shouldBe('stdClass');
    }

    function it_returns_default_logger_for_invalid_object() {
        $logger = $this->getLoggerForClass('invalid_object');
        $logger->shouldHaveType(LoggerInterface::class);

        $this->getLoggers()->shouldBe([
            'channel1-channel1' => $logger->getWrappedObject(),
        ]);
        $logger->getName()->shouldBe('channel1');
    }

    function it_creates_a_logger_for_class_with_different_config() {
        $object = new stdClass();
        $logger = $this->getLoggerForClass($object);
        $logger->shouldHaveType(LoggerInterface::class);

        $this->getLoggers()->shouldBe([
            'channel1-stdClass' => $logger->getWrappedObject(),
        ]);
        $logger->getName()->shouldBe('stdClass');
    }

    function it_reuses_existing_loggers_with_the_same_channel_name() {
        $this->getLoggers()->shouldBe([]);
        $logger = $this->getLogger('channel1');
        $logger->shouldHaveType(LoggerInterface::class);
        $this->getLogger('channel1')->shouldBe($logger);
    }

    function it_throws_an_error_if_no_channel_configuration_is_available() {
        $this->getLoggers()->shouldBe([]);
        $this->shouldThrow(UndefinedChannelException::class)
            ->duringGetLogger('foo');
    }

    function it_returns_the_default_logger_if_channel_is_not_specified() {
        $logger = $this->getLogger('channel1');
        $this->getLogger()->shouldBe($logger);
    }

    function it_creates_logs_directory_if_it_does_not_exist() {
        $channelFilePath = array_get(
            $this->getConfig()->getDefaultChannelConfig()->getWrappedObject(),
            'log_file_path'
        );

        expect(file_exists($channelFilePath))->shouldBe(false);
        $this->getLogger($this->getConfig()->getDefaultChannelConfigName());
        expect(file_exists($channelFilePath))->shouldBe(true);
    }
}
