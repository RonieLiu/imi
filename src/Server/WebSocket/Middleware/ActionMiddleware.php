<?php

namespace Imi\Server\WebSocket\Middleware;

use Imi\Bean\Annotation\Bean;
use Imi\Controller\WebSocketController;
use Imi\RequestContext;
use Imi\Server\WebSocket\IMessageHandler;
use Imi\Server\WebSocket\Message\IFrame;

/**
 * @Bean("WebSocketActionMiddleware")
 */
class ActionMiddleware implements IMiddleware
{
    /**
     * 处理方法.
     *
     * @param IFrame          $frame
     * @param IMessageHandler $handler
     *
     * @return void
     */
    public function process(IFrame $frame, IMessageHandler $handler)
    {
        $requestContext = RequestContext::getContext();
        // 获取路由结果
        /** @var \Imi\Server\WebSocket\Route\RouteResult $result */
        $result = $requestContext['routeResult'] ?? null;
        if (null === $result) {
            return $handler->handle($frame);
        }
        // 路由匹配结果是否是[控制器对象, 方法名]
        $isObject = is_array($result->callable) && isset($result->callable[0]) && $result->callable[0] instanceof WebSocketController;
        if ($isObject) {
            if (!$result->routeItem->singleton) {
                // 复制一份控制器对象
                $result->callable[0] = clone $result->callable[0];
            }
            $result->callable[0]->server = RequestContext::getServer();
            $result->callable[0]->frame = $frame;
        }
        // 执行动作
        $actionResult = ($result->callable)($frame->getFormatData());

        $requestContext['wsResult'] = $actionResult;

        $actionResult = $handler->handle($frame);

        if (null !== $actionResult) {
            $requestContext['wsResult'] = $actionResult;
        }

        return $requestContext['wsResult'];
    }
}
