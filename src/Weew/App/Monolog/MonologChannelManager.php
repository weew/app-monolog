<?php

namespace Weew\App\Monolog;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use ReflectionClass;
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
     * @param null $configName
     * @param null $channelName
     *
     * @return Logger
     * @throws UndefinedChannelException
     */
    public function getLogger($configName = null, $channelName = null) {
        if ($configName === null) {
            $configName = $this->config->getDefaultChannelConfigName();
        }

        if ($channelName === null) {
            $channelName = $configName;
        }

        $loggerIdentifier = s('%s-%s', $configName, $channelName);

        if ( ! array_has($this->loggers, $loggerIdentifier)) {
            if ($this->config->getChannelConfig($configName) === null) {
                throw new UndefinedChannelException(s(
                    'Configuration for logging channel "%s" does not exist, this channel can not be created.',
                    $configName
                ));
            }

            $this->loggers[$loggerIdentifier] = $this->createLogger($configName, $channelName);
        }

        return array_get($this->loggers, $loggerIdentifier);
    }

    /**
     * @param object $object
     * @param null $configName
     *
     * @return Logger
     * @throws UndefinedChannelException
     */
    public function getLoggerForClass($object, $configName = null) {
        if (is_object($object)) {
            $reflector = new ReflectionClass($object);

            return $this->getLogger($configName, $reflector->getShortName());
        }

        return $this->getLogger();
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
     * @param $configName
     * @param $channelName
     *
     * @return Logger
     */
    protected function createLogger($configName, $channelName) {
        $this->ensureLogFileExists(
            $this->config->getLogFilePathForChannelConfig($configName)
        );

        $stream = new StreamHandler(
            $this->config->getLogFilePathForChannelConfig($configName),
            $this->config->getLogLevelForChannelConfig($configName)
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
