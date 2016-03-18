<?php

namespace Weew\App\Monolog;

use Monolog\Logger;

interface IMonologChannelManager {
    /**
     * @param $channelName
     *
     * @return Logger
     */
    function getLogger($channelName);

    /**
     * @return Logger[]
     */
    function getLoggers();

    /**
     * @return IMonologConfig
     */
    function getConfig();
}
