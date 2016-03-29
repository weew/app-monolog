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
     * @return Logger[]
     */
    function getLoggers();

    /**
     * @return IMonologConfig
     */
    function getConfig();
}
