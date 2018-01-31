<?php

namespace App\Service\Text;


use App\Service\Text\Processor\TextProcessorInterface;
use Psr\Log\LoggerInterface;

class TextProcessorService implements TextProcessorRegistry
{

    private $processors = [];

    /** @var  LoggerInterface */
    private $logger;

    /**
     * TextProcessorService constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    function register(TextProcessorInterface $processor)
    {
        $this->logger->debug('REGISTER processor', $processor->getMethods());
        foreach ($processor->getMethods() as $method) {
            $this->processors[$method] = $processor;
        }
    }

    /**
     * @param $method
     * @param $text
     * @return string
     * @throws \BadMethodCallException
     */
    function process($method, $text)
    {
        $this->logger->debug('PROCESS method', [$method, $text]);
        if (isset($this->processors[$method])) {
            /** @var TextProcessorInterface $processor */
            $processor = $this->processors[$method];
            $res = $processor->process($method, $text);
            $this->logger->debug('PROCESS result', [$res]);
            return $res;
        }
        throw new \BadMethodCallException("Method '{$method}' not implemented");
    }

}
