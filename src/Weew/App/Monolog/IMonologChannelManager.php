<?php

namespace Weew\App\Monolog;

use Monolog\Logger;

interface IMonologChannelManager {
    /**
     * @param null $configName
     * @param null $channelName
     *
     * @return Logger
     */
    function getLogger($configName = null, $channelName = null);

    /**
     * @param $object
     * @param null $configName
     *
     * @return Logger
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
