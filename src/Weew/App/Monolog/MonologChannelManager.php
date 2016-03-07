<?php

namespace Weew\App\Monolog;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Weew\App\Monolog\Exceptions\UndefinedChannelException;

class MonologChannelManager implements IMonologChannelManager {
    /**
     * @var IMonologConfig
     */
    protected $config;

    /**
     * @var LoggerInterface[]
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
     * @return LoggerInterface
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
     * @return LoggerInterface[]
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
     * @return LoggerInterface
     */
    protected function createLogger($channelName) {
        $stream = new StreamHandler(
            $this->config->getLogFilePathForChannel($channelName),
            $this->config->getLogLevelForChannel($channelName)
        );
        $logger = new Logger($channelName, [$stream]);

        return $logger;
    }
}
