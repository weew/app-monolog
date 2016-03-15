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

        $channels = $this->getChannels();

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

        if ($this->getDefaultChannel() === null) {
            throw new InvalidConfigValueException(s(
                'Default channel with name "%s" does not exist.',
                $this->getDefaultChannelName()
            ));
        }
    }

    /**
     * @return array
     */
    public function getChannels() {
        return $this->config->get(self::LOG_CHANNELS);
    }

    /**
     * @return string
     */
    public function getDefaultChannelName() {
        return $this->config->get(self::DEFAULT_CHANNEL_NAME);
    }

    /**
     * @return array
     */
    public function getDefaultChannel() {
        return array_get($this->getChannels(), $this->getDefaultChannelName());
    }

    /**
     * @param $channelName
     *
     * @return array
     */
    public function getChannel($channelName) {
        return array_get($this->getChannels(), $channelName);
    }

    /**
     * @param $channelName
     *
     * @return string
     */
    public function getLogFilePathForChannel($channelName) {
        return $this->config->get(s(self::LOG_CHANNEL_FILE_PATH, $channelName));
    }

    /**
     * @param $channelName
     *
     * @return string
     */
    public function getLogLevelForChannel($channelName) {
        return $this->config->get(s(self::LOG_CHANNEL_LOG_LEVEL, $channelName));
    }
}
