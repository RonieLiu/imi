<?php

namespace Imi\Server\WebSocket;

use Imi\Bean\Annotation\Bean;
use Imi\RequestContext;
use Imi\Server\DataParser\DataParser;
use Imi\Server\WebSocket\Message\IFrame;

/**
 * @Bean("WebSocketDispatcher")
 */
class Dispatcher
{
    /**
     * 中间件数组.
     *
     * @var string[]
     */
    protected $middlewares = [];

    public function dispatch(IFrame $frame)
    {
        $requestHandler = new MessageHandler($this->getMiddlewares());
        $responseData = $requestHandler->handle($frame);
        if (null !== $responseData) {
            RequestContext::getServer()->getSwooleServer()->push($frame->getFd(), RequestContext::getServerBean(DataParser::class)->encode($responseData));
        }
    }

    protected function getMiddlewares()
    {
        return array_merge($this->middlewares, [
            \Imi\Server\WebSocket\Middleware\ActionWrapMiddleware::class,
        ]);
    }
}
