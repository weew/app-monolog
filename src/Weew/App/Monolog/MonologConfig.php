<?php

namespace Weew\App\Monolog;

use Weew\Config\Exceptions\InvalidConfigValueException;
use Weew\Config\IConfig;

class MonologConfig implements IMonologConfig {
    const LOG_CHANNELS = 'monolog.channels';
    const DEFAULT_CHANNEL_NAME = 'monolog.default_channel';
    const LOG_CHANNEL_FILE_PATH = 'monolog.channels.%s.log_file_path';
    const LOG_CHANNEL_LOG_LEVEL = 'monolog.channels.%s.log_level';

    /**
     * @var IConfig
     */
    protected $config;

    /**
     * MonologConfig constructor.
     *
     * @param IConfig $config
     *
     * @throws InvalidConfigValueException
     */
    public function __construct(IConfig $config) {
        $this->config = $config;

        $config
            ->ensure(self::LOG_CHANNELS, 'Missing a list of logging channels.')
            ->ensure(self::DEFAULT_CHANNEL_NAME, 'Missing default channel name.');

        $channels = $this->getChannelConfigs();

        if ( ! is_array($channels)) {
            throw new InvalidConfigValueException(s(
                'Config under the key "%s" must be an array.',
                self::LOG_CHANNELS
            ));
        }

        foreach ($channels as $name => $channel) {
            $config->ensure(
                s(self::LOG_CHANNEL_FILE_PATH, $name),
                s('Missing log file path for logging channel "%s".', $name)
            );

            $config->ensure(
                s(self::LOG_CHANNEL_LOG_LEVEL, $name),
                s('Missing log file path for logging channel "%s".', $name)
            );
        }

        if ($this->getDefaultChannelConfig() === null) {
            throw new InvalidConfigValueException(s(
                'Default channel with name "%s" does not exist.',
                $this->getDefaultChannelConfigName()
            ));
        }
    }

    /**
     * @return array
     */
    public function getChannelConfigs() {
        return $this->config->get(self::LOG_CHANNELS);
    }

    /**
     * @return string
     */
    public function getDefaultChannelConfigName() {
        return $this->config->get(self::DEFAULT_CHANNEL_NAME);
    }

    /**
     * @return array
     */
    public function getDefaultChannelConfig() {
        return array_get($this->getChannelConfigs(), $this->getDefaultChannelConfigName());
    }

    /**
     * @param $configName
     *
     * @return array
     */
    public function getChannelConfig($configName) {
        return array_get($this->getChannelConfigs(), $configName);
    }

    /**
     * @param $configName
     *
     * @return string
     */
    public function getLogFilePathForChannelConfig($configName) {
        return $this->config->get(s(self::LOG_CHANNEL_FILE_PATH, $configName));
    }

    /**
     * @param $configName
     *
     * @return string
     */
    public function getLogLevelForChannelConfig($configName) {
        return $this->config->get(s(self::LOG_CHANNEL_LOG_LEVEL, $configName));
    }
}
