<?php

namespace Imi\Server\ConnectContext\Listener;

use Imi\Bean\Annotation\Listener;
use Imi\RequestContext;
use Imi\Server\Event\Listener\IWorkerStartEventListener;
use Imi\Server\Event\Param\WorkerStartEventParam;
use Imi\ServerManage;
use Imi\Util\Imi;
use Imi\Worker;

/**
 * @Listener(eventName="IMI.MAIN_SERVER.WORKER.START")
 */
class WorkerStart implements IWorkerStartEventListener
{
    /**
     * 事件处理方法.
     *
     * @param EventParam $e
     *
     * @return void
     */
    public function handle(WorkerStartEventParam $e)
    {
        if (!$e->server->getSwooleServer()->taskworker && 0 === Worker::getWorkerID()) {
            foreach (ServerManage::getServers() as $server) {
                if ($server->isLongConnection()) {
                    RequestContext::set('server', $server);
                    $server->getBean('ConnectContextStore')->getHandler();
                    if (Imi::getClassPropertyValue('ServerGroup', 'status')) {
                        $server->getBean(Imi::getClassPropertyValue('ServerGroup', 'groupHandler'));
                    }
                    $server->getBean('ConnectionBinder');
                }
            }
        }
    }
}
