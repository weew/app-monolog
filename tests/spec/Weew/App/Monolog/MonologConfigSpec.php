<?php

namespace tests\spec\Weew\App\Monolog;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Weew\App\Monolog\IMonologConfig;
use Weew\App\Monolog\MonologConfig;
use Weew\Config\Config;
use Weew\Config\Exceptions\MissingConfigException;
use Weew\Config\Exceptions\InvalidConfigValueException;

/**
 * @mixin MonologConfig
 */
class MonologConfigSpec extends ObjectBehavior {
    function let() {
        $config = new Config();
        $config->set(MonologConfig::LOG_CHANNEL_FILE_PATH('channel1'), '/tmp');
        $config->set(MonologConfig::LOG_CHANNEL_FILE_PATH('channel2'), '/tmp');
        $config->set(MonologConfig::LOG_CHANNEL_LOG_LEVEL('channel1'), 'debug');
        $config->set(MonologConfig::LOG_CHANNEL_LOG_LEVEL('channel2'), 'debug');
        $config->set(MonologConfig::DEFAULT_CHANNEL_NAME, 'channel1');

        $this->beConstructedWith($config);
    }

    function it_is_initializable() {
        $this->shouldHaveType(MonologConfig::class);
    }

    function it_implements_imonolog_config() {
        $this->beAnInstanceOf(IMonologConfig::class);
    }

    function it_throws_an_error_if_default_channel_config_is_not_set() {
        $config = new Config();
        $this->beConstructedWith($config);

        $this->shouldThrow(MissingConfigException::class)->duringInstantiation();
    }

    function it_throws_an_error_if_logging_channels_configs_are_not_set() {
        $config = new Config();
        $config->set(MonologConfig::DEFAULT_CHANNEL_NAME, 'channel_name');
        $this->beConstructedWith($config);

        $this->shouldThrow(MissingConfigException::class)->duringInstantiation();
    }

    function it_throws_an_error_if_default_channel_config_is_absent() {
        $config = new Config();
        $config->set(MonologConfig::DEFAULT_CHANNEL_NAME, 'channel_name');
        $config->set(MonologConfig::LOG_CHANNELS, []);
        $this->beConstructedWith($config);

        $this->shouldThrow(
            new InvalidConfigValueException('Default channel with name "channel_name" does not exist.')
        )->duringInstantiation();
    }

    function it_throws_an_error_if_logging_channel_config_is_not_an_array() {
        $config = new Config();
        $config->set(MonologConfig::DEFAULT_CHANNEL_NAME, 'channel_name');
        $config->set(MonologConfig::LOG_CHANNELS, 'channels');
        $this->beConstructedWith($config);

        $this->shouldThrow(
            new InvalidConfigValueException('Config under the key "monolog.channels" must be an array.')
        )->duringInstantiation();
    }

    function it_throws_an_error_if_a_logging_channel_config_has_no_logging_path() {
        $config = new Config();
        $config->set(MonologConfig::DEFAULT_CHANNEL_NAME, 'channel_name');
        $config->set(MonologConfig::LOG_CHANNEL_LOG_LEVEL('channel_name'), 'log_level');
        $this->beConstructedWith($config);

        $this->shouldThrow(MissingConfigException::class)->duringInstantiation();
    }

    function it_throws_an_error_if_a_logging_channel_config_has_no_log_level() {
        $config = new Config();
        $config->set(MonologConfig::DEFAULT_CHANNEL_NAME, 'channel_name');
        $config->set(MonologConfig::LOG_CHANNEL_FILE_PATH('channel_name'), 'log_path');
        $this->beConstructedWith($config);

        $this->shouldThrow(MissingConfigException::class)->duringInstantiation();
    }

    function it_returns_default_channel_config_name() {
        $this->getDefaultChannelConfigName()->shouldBe('channel1');
    }
    
    function it_returns_default_channel_config() {
        $this->getDefaultChannelConfig()->shouldBe([
            'log_file_path' => '/tmp',
            'log_level' => 'debug',
        ]);
    }

    function it_returns_channel_configs() {
        $this->getChannelConfigs()->shouldBe([
            'channel1' => [
                'log_file_path' => '/tmp',
                'log_level' => 'debug',
            ],
            'channel2' => [
                'log_file_path' => '/tmp',
                'log_level' => 'debug',
            ]
        ]);
    }
    
    function it_returns_log_file_path_for_channel_config() {
        $this->getLogFilePathForChannelConfig('channel1')->shouldBe('/tmp');
        $this->getLogLevelForChannelConfig('foo')->shouldBe(null);
    }

    function it_returns_log_level_for_channel_config() {
        $this->getLogLevelForChannelConfig('channel1')->shouldBe('debug');
        $this->getLogLevelForChannelConfig('foo')->shouldBe(null);
    }

    function it_returns_a_channel_config() {
        $this->getChannelConfig('channel1')->shouldBe([
            'log_file_path' => '/tmp',
            'log_level' => 'debug',
        ]);
        $this->getChannelConfig('foo')->shouldBe(null);
    }
}
