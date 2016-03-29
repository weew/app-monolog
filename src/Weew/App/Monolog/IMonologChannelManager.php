<?php

namespace Weew\App\Monolog;

use Monolog\Logger;

interface IMonologChannelManager {
    /**
     * @param $configName
     *
     * @return Logger
     */
    function getLogger($configName);

    /**
     * @return Logger[]
     */
    function getLoggers();

    /**
     * @return IMonologConfig
     */
    function getConfig();
}
