<?php

namespace tests\spec\Weew\App\Monolog;

use PhpSpec\ObjectBehavior;
use Psr\Log\LoggerInterface;
use stdClass;
use Weew\App\Monolog\Exceptions\UndefinedChannelException;
use Weew\App\Monolog\IMonologChannelManager;
use Weew\App\Monolog\IMonologConfig;
use Weew\App\Monolog\Loggers\PrefixedLogger;
use Weew\App\Monolog\MonologChannelManager;
use Weew\App\Monolog\MonologConfig;
use Weew\Config\Config;

/**
 * @mixin MonologChannelManager
 */
class MonologChannelManagerSpec extends ObjectBehavior {
    function let() {
        $config = new Config();
        $config->set(MonologConfig::LOG_CHANNEL_FILE_PATH('config1'), path('/tmp', uuid(), 'config1.log'));
        $config->set(MonologConfig::LOG_CHANNEL_LOG_LEVEL('config1'), 'debug');
        $config->set(MonologConfig::DEFAULT_CHANNEL_NAME, 'config1');
        $monologConfig = new MonologConfig($config);

        $this->beConstructedWith($monologConfig);
    }

    function it_is_initializable() {
        $this->shouldHaveType(MonologChannelManager::class);
    }

    function it_implements_imonolog_channel_manager() {
        $this->beAnInstanceOf(IMonologChannelManager::class);
    }

    function it_returns_loggers() {
        $this->getLoggers()->shouldBe([]);
    }

    function it_returns_used_config() {
        $this->getConfig()->shouldHaveType(IMonologConfig::class);
    }

    function it_returns_a_new_logger_and_stores_it_locally() {
        $this->getLoggers()->shouldBe([]);
        $logger = $this->getLogger('config1');
        $logger->shouldHaveType(LoggerInterface::class);

        $this->getLoggers()->shouldBe([
            'config1' => $logger->getWrappedObject(),
        ]);
        $logger->getName()->shouldBe('config1');
    }

    function it_reuses_existing_loggers_with_the_same_channel_name() {
        $this->getLoggers()->shouldBe([]);
        $logger = $this->getLogger('config1');
        $logger->shouldHaveType(LoggerInterface::class);
        $this->getLogger('config1')->shouldBe($logger);
    }

    function it_throws_an_error_if_no_channel_configuration_is_available() {
        $this->getLoggers()->shouldBe([]);
        $this->shouldThrow(UndefinedChannelException::class)
            ->duringGetLogger('foo');
    }

    function it_returns_the_default_logger_if_channel_is_not_specified() {
        $logger = $this->getLogger('config1');
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

    function it_returns_all_loggers() {
        $this->getAllLoggers()->shouldHaveCount(1);
    }

    function it_creates_a_logger_for_class() {
        $logger = $this->getLoggerForClass(new stdClass());
        $logger->shouldHaveType(PrefixedLogger::class);
        $logger->getName()->shouldBe('config1');
        $logger->getPrefix()->shouldBe('stdClass');
    }
}
