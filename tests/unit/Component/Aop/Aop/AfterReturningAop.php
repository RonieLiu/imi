<?php

namespace Imi\Test\Component\Aop\Aop;

use Imi\Aop\AfterReturningJoinPoint;
use Imi\Aop\Annotation\AfterReturning;
use Imi\Aop\Annotation\Aspect;
use Imi\Aop\Annotation\PointCut;
use Imi\Bean\BeanFactory;
use Imi\Bean\BeanProxy;
use PHPUnit\Framework\Assert;

/**
 * @Aspect
 */
class AfterReturningAop
{
    /**
     * @AfterReturning
     * @PointCut(
     *     allow={
     *         "Imi\Test\Component\Aop\Classes\TestAfterReturningClass::test"
     *     }
     * )
     *
     * @return void
     */
    public function injectAfterReturningAop(AfterReturningJoinPoint $joinPoint)
    {
        Assert::assertEquals([1], $joinPoint->getArgs());
        Assert::assertEquals('test', $joinPoint->getMethod());
        Assert::assertEquals(\Imi\Test\Component\Aop\Classes\TestAfterReturningClass::class, BeanFactory::getObjectClass($joinPoint->getTarget()));
        Assert::assertEquals(BeanProxy::class, get_class($joinPoint->getThis()));
        Assert::assertEquals('afterReturning', $joinPoint->getType());
        Assert::assertEquals(1, $joinPoint->getReturnValue());
        $joinPoint->setReturnValue(2);
    }
}
