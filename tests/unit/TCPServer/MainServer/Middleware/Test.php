<?php
namespace Imi\Test\TCPServer\MainServer\Middleware;

use Imi\Bean\Annotation\Bean;
use Imi\Server\TcpServer\IReceiveHandler;
use Imi\Server\TcpServer\Message\IReceiveData;
use Imi\Server\TcpServer\Middleware\IMiddleware;

/**
 * @Bean
 */
class Test implements IMiddleware
{
    public function process(IReceiveData $data, IReceiveHandler $handler)
    {
        var_dump('test middleware');
        return $handler->handle($data, $handler);
    }
}