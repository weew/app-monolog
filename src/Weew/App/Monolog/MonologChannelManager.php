<?php

namespace Weew\App\Monolog;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Weew\App\Monolog\Exceptions\UndefinedChannelException;

class MonologChannelManager implements IMonologChannelManager {
    /**
     * @var IMonologConfig
     */
    protected $config;

    /**
     * @var Logger[]
     */
    protected $loggers = [];

    /**
     * MonologChannelManager constructor.
     *
     * @param IMonologConfig $config
     */
    public function __construct(IMonologConfig $config) {
        $this->config = $config;
    }

    /**
     * @param null $channelName
     *
     * @return Logger
     * @throws UndefinedChannelException
     */
    public function getLogger($channelName = null) {
        if ($channelName === null) {
            $channelName = $this->config->getDefaultChannelName();
        }

        if ( ! array_has($this->loggers, $channelName)) {
            if ($this->config->getChannel($channelName) === null) {
                throw new UndefinedChannelException(s(
                    'Configuration for logging channel "%s" does not exist, this channel can not be created.',
                    $channelName
                ));
            }

            $this->loggers[$channelName] = $this->createLogger($channelName);
        }

        return array_get($this->loggers, $channelName);
    }

    /**
     * @return Logger[]
     */
    public function getLoggers() {
        return $this->loggers;
    }

    /**
     * @return IMonologConfig
     */
    public function getConfig() {
        return $this->config;
    }

    /**
     * @param $channelName
     *
     * @return Logger
     */
    protected function createLogger($channelName) {
        $this->ensureLogFileExists(
            $this->config->getLogFilePathForChannel($channelName)
        );

        $stream = new StreamHandler(
            $this->config->getLogFilePathForChannel($channelName),
            $this->config->getLogLevelForChannel($channelName)
        );
        $logger = new Logger($channelName, [$stream]);

        return $logger;
    }

    /**
     * @param $logFilePath
     */
    protected function ensureLogFileExists($logFilePath) {
        if ( ! file_exists($logFilePath)) {
            file_create($logFilePath);
        }
    }
}
