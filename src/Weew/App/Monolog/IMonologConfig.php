<?php

namespace Weew\App\Monolog;

interface IMonologConfig {
    /**
     * @return array
     */
    function getChannels();

    /**
     * @return string
     */
    function getDefaultChannelName();

    /**
     * @return array
     */
    function getDefaultChannel();

    /**
     * @param $channelName
     *
     * @return array
     */
    function getChannel($channelName);

    /**
     * @param string $channelName
     *
     * @return string
     */
    function getLogFilePathForChannel($channelName);

    /**
     * @param string $channelName
     *
     * @return string
     */
    function getLogLevelForChannel($channelName);
}
