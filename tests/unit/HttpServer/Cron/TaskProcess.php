<?php

namespace Imi\Test\HttpServer\Cron;

use Imi\Cron\Annotation\Cron;
use Imi\Cron\Traits\TWorkerReport;
use Imi\Process\Annotation\Process;
use Imi\Process\IProcess;
use Imi\Util\Args;

/**
 * @Cron(id="CronProcess1", second="3n")
 * @Process("CronProcess1")
 */
class TaskProcess implements IProcess
{
    use TWorkerReport;

    public function run(\Swoole\Process $process)
    {
        $success = false;
        $message = '';

        try {
            $id = Args::get('id');
            if (null === $id) {
                return;
            }
            $data = json_decode(Args::get('data'), true);
            $success = true;
        } catch (\Throwable $th) {
            $message = $th->getMessage();

            throw $th;
        } finally {
            $this->reportCronResult($id, $success, $message);
        }
    }
}
