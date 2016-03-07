<?php

namespace tests\spec\Weew\App\Monolog;

use Monolog\Logger;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;
use Weew\App\Monolog\IMonologChannelManager;
use Weew\App\Monolog\IMonologConfig;
use Weew\App\Monolog\MonologChannelManager;
use Weew\App\Monolog\MonologConfig;
use Weew\App\Monolog\MonologProvider;
use Weew\Config\Config;
use Weew\Container\Container;
use Weew\Container\IContainer;

/**
 * @mixin MonologProvider
 */
class MonologProviderSpec extends ObjectBehavior {
    /**
     * @var IContainer
     */
    private $container;

    /**
     * @return MonologChannelManager
     */
    private function createChannelManager() {
        $config = new Config();
        $config->set(MonologConfig::DEFAULT_CHANNEL_NAME, 'channel');
        $config->set(s(MonologConfig::LOG_CHANNEL_FILE_PATH, 'channel'), '/tmp');
        $config->set(s(MonologConfig::LOG_CHANNEL_LOG_LEVEL, 'channel'), 'debug');
        $monologConfig = new MonologConfig($config);

        return new MonologChannelManager($monologConfig);
    }

    function let() {
        $this->container = new Container();
        $this->beConstructedWith($this->container);
    }

    function it_is_initializable() {
        $this->shouldHaveType(MonologProvider::class);
    }

    function it_shares_default_implementation_of_imonolog_config_in_the_container() {
        $this->shouldHaveType(MonologProvider::class);
        expect($this->container->has(IMonologConfig::class))->shouldBe(true);
    }

    function it_shares_an_instance_of_monolog_channel_manager_in_the_container() {
        $channelManager = $this->createChannelManager($this->container);

        $this->shouldHaveType(MonologProvider::class);
        $this->initialize($this->container, $channelManager);
        expect($this->container->has(IMonologChannelManager::class))->shouldBe(true);
        expect($this->container->has(MonologChannelManager::class))->shouldBe(true);
    }

    function it_shares_an_instance_of_the_default_logger_in_the_container() {
        $channelManager = $this->createChannelManager();

        expect($this->container->has(LoggerInterface::class))->shouldBe(false);
        $this->initialize($this->container, $channelManager);
        expect($this->container->has(LoggerInterface::class))->shouldBe(true);
        expect($this->container->has(Logger::class))->shouldBe(true);

        $logger = $this->container->get(LoggerInterface::class);
        $defaultLogger = $channelManager->getLogger();
        expect($logger)->shouldBe($defaultLogger);
    }
}
