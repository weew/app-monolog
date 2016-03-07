<?php

namespace Weew\App\Monolog;

use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Weew\Container\IContainer;

class MonologProvider {
    /**
     * MonologProvider constructor.
     *
     * @param IContainer $container
     */
    public function __construct(IContainer $container) {
        $container->set(IMonologConfig::class, MonologConfig::class);
    }

    /**
     * @param IContainer $container
     * @param MonologChannelManager $channelManager
     */
    public function initialize(
        IContainer $container,
        MonologChannelManager $channelManager
    ) {
        $container->set(
            [LoggerInterface::class, Logger::class], $channelManager->getLogger()
        );
        $container->set(
            [IMonologChannelManager::class, MonologChannelManager::class], $channelManager
        );
    }
}
