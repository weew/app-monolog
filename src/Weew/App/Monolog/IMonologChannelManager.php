<?php

namespace Weew\App\Monolog;

use Psr\Log\LoggerInterface;

interface IMonologChannelManager {
    /**
     * @param $channelName
     *
     * @return LoggerInterface
     */
    function getLogger($channelName);

    /**
     * @return LoggerInterface[]
     */
    function getLoggers();

    /**
     * @return IMonologConfig
     */
    function getConfig();
}
