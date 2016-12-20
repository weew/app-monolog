<?php

namespace Weew\App\Monolog\Loggers;

use Psr\Log\LoggerInterface;
use ReflectionClass;

class PrefixedLogger implements LoggerInterface {
    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var string
     */
    private $prefix;

    /**
     * PrefixedLogger constructor.
     *
     * @param LoggerInterface $logger
     * @param prefix $prefix
     */
    public function __construct(LoggerInterface $logger, $prefix = null) {
        $this->logger = $logger;
        $this->prefix = $this->determinePrefix($prefix);
    }

    /**
     * @param string $method
     * @param array $args
     *
     * @return mixed
     */
    public function __call($method, array $args) {
        return call_user_func_array([$this->logger, $method], $args);
    }

    /**
     * @return Logger
     */
    public function getLogger() {
        return $this->logger;
    }

    /**
     * @return string
     */
    public function getPrefix() {
        return $this->prefix;
    }

    /**
     * System is unusable.
     *
     * @param string $message
     * @param array $context
     *
     * @return null
     */
    public function emergency($message, array $context = []) {
        $this->logger->emergency($this->prefixMessage($message), $context);
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $message
     * @param array $context
     *
     * @return null
     */
    public function alert($message, array $context = []) {
        $this->logger->alert($this->prefixMessage($message), $context);
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message
     * @param array $context
     *
     * @return null
     */
    public function critical($message, array $context = []) {
        $this->logger->critical($this->prefixMessage($message), $context);
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message
     * @param array $context
     *
     * @return null
     */
    public function error($message, array $context = []) {
        $this->logger->error($this->prefixMessage($message), $context);
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string $message
     * @param array $context
     *
     * @return null
     */
    public function warning($message, array $context = []) {
        $this->logger->warning($this->prefixMessage($message), $context);
    }

    /**
     * Normal but significant events.
     *
     * @param string $message
     * @param array $context
     *
     * @return null
     */
    public function notice($message, array $context = []) {
        $this->logger->notice($this->prefixMessage($message), $context);
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string $message
     * @param array $context
     *
     * @return null
     */
    public function info($message, array $context = []) {
        $this->logger->info($this->prefixMessage($message), $context);
    }

    /**
     * Detailed debug information.
     *
     * @param string $message
     * @param array $context
     *
     * @return null
     */
    public function debug($message, array $context = []) {
        $this->logger->debug($this->prefixMessage($message), $context);
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     *
     * @return null
     */
    public function log($level, $message, array $context = []) {
        $this->logger->log($level, $this->prefixMessage($message), $context);
    }

    /**
     * @param string $message
     *
     * @return string
     */
    protected function prefixMessage($message) {
        if ($this->prefix !== null) {
            return s('%s: %s', $this->prefix, $message);
        }

        return $message;
    }

    /**
     * @param string|object $prefix
     *
     * @return string
     */
    protected function determinePrefix($prefix) {
        if (is_object($prefix)) {
            $reflector = new ReflectionClass($prefix);

            return $reflector->getShortName();
        }

        return $prefix;
    }
}
