<?php

namespace Weew\App\Monolog;

use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Weew\Container\IContainer;

class MonologProvider {
    /**
     * @param IContainer $container
     */
    public function initialize(IContainer $container) {
        $container->set(IMonologConfig::class, MonologConfig::class);
    }

    /**
     * @param IContainer $container
     * @param MonologChannelManager $channelManager
     *
     * @throws Exceptions\UndefinedChannelException
     */
    public function boot(IContainer $container, MonologChannelManager $channelManager) {
        $container->set(
            [LoggerInterface::class, Logger::class], $channelManager->getLogger()
        );
        $container->set(
            [IMonologChannelManager::class, MonologChannelManager::class], $channelManager
        );
    }
}
