<?php

namespace Imi\Lock\Aop;

use Imi\Aop\Annotation\Around;
use Imi\Aop\Annotation\Aspect;
use Imi\Aop\Annotation\PointCut;
use Imi\Aop\AroundJoinPoint;
use Imi\Aop\PointCutType;
use Imi\Bean\Annotation\AnnotationManager;
use Imi\Lock\Annotation\Lockable;

/**
 * @Aspect
 */
class LockAop
{
    use TLockableParser;

    /**
     * 处理方法加锁
     *
     * @PointCut(
     *         type=PointCutType::ANNOTATION,
     *         allow={
     *             \Imi\Lock\Annotation\Lockable::class,
     *         }
     * )
     * @Around
     *
     * @return mixed
     */
    public function parseLock(AroundJoinPoint $joinPoint)
    {
        $class = get_parent_class($joinPoint->getTarget());
        // Lockable 注解
        $lockable = AnnotationManager::getMethodAnnotations($class, $joinPoint->getMethod(), Lockable::class)[0] ?? null;

        return $this->parseLockable($joinPoint->getTarget(), $joinPoint->getMethod(), $joinPoint->getArgs(), $lockable, function () use ($joinPoint) {
            return $joinPoint->proceed();
        });
    }
}
