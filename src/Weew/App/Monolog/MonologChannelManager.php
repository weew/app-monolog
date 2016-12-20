<?php

namespace Weew\App\Monolog;

use Monolog\Handler\StreamHandler;
use Weew\App\Monolog\Exceptions\UndefinedChannelException;
use Weew\App\Monolog\Loggers\Logger;
use Weew\App\Monolog\Loggers\PrefixedLogger;

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
     *
     * @return Logger
     * @throws UndefinedChannelException
     */
    public function getLogger($configName = null) {
        if ($configName === null) {
            $configName = $this->config->getDefaultChannelConfigName();
        }

        if ( ! array_has($this->loggers, $configName)) {
            if ($this->config->getChannelConfig($configName) === null) {
                throw new UndefinedChannelException(s(
                    'Configuration for logging channel "%s" does not exist, this channel can not be created.',
                    $configName
                ));
            }

            $this->loggers[$configName] = $this->createLogger($configName);
        }

        return array_get($this->loggers, $configName);
    }

    /**
     * @param mixed $object
     * @param string $configName
     *
     * @return PrefixedLogger
     */
    public function getLoggerForClass($object, $configName = null) {
        return new PrefixedLogger($this->getLogger($configName), $object);
    }

    /**
     * Get all logger instances.
     *
     * @return Logger[]
     */
    public function getLoggers() {
        return $this->loggers;
    }

    /**
     * Get all registered loggers. Instantiate if necessary.
     *
     * @return Logger[]
     * @throws UndefinedChannelException
     */
    public function getAllLoggers() {
        // instantiate all loggers
        foreach ($this->config->getChannelConfigs() as $name => $config) {
            $this->getLogger($name);
        }

        return $this->getLoggers();
    }

    /**
     * @return IMonologConfig
     */
    public function getConfig() {
        return $this->config;
    }

    /**
     * @param string $configName
     *
     * @return Logger
     */
    protected function createLogger($configName) {
        $this->ensureLogFileExists(
            $this->config->getLogFilePathForChannelConfig($configName)
        );

        $stream = new StreamHandler(
            $this->config->getLogFilePathForChannelConfig($configName),
            $this->config->getLogLevelForChannelConfig($configName)
        );
        $logger = new Logger($configName, [$stream]);

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
