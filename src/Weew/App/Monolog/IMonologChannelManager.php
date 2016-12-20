<?php

namespace Weew\App\Monolog;

use Monolog\Logger;
use Weew\App\Monolog\Loggers\PrefixedLogger;

interface IMonologChannelManager {
    /**
     * @param string $configName
     *
     * @return Logger
     */
    function getLogger($configName = null);

    /**
     * @param mixed $object
     * @param string $configName
     *
     * @return PrefixedLogger
     */
    function getLoggerForClass($object, $configName = null);

    /**
     * Get all instantiated loggers.
     *
     * @return Logger[]
     */
    function getLoggers();

    /**
     * Get all registered loggers. Instantiate if necessary.
     *
     * @return Logger[]
     */
    function getAllLoggers();

    /**
     * @return IMonologConfig
     */
    function getConfig();
}
