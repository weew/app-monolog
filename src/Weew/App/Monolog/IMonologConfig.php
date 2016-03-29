<?php

namespace Weew\App\Monolog;

interface IMonologConfig {
    /**
     * @return array
     */
    function getChannelConfigs();

    /**
     * @return string
     */
    function getDefaultChannelConfigName();

    /**
     * @return array
     */
    function getDefaultChannelConfig();

    /**
     * @param $configName
     *
     * @return array
     */
    function getChannelConfig($configName);

    /**
     * @param string $configName
     *
     * @return string
     */
    function getLogFilePathForChannelConfig($configName);

    /**
     * @param string $configName
     *
     * @return string
     */
    function getLogLevelForChannelConfig($configName);
}
