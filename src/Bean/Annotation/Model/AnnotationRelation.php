<?php

namespace Imi\Bean\Annotation\Model;

class AnnotationRelation
{
    /**
     * 类关联列表.
     *
     * @var \Imi\Bean\Annotation\Model\ClassAnnotationRelation[]
     */
    private $classRelations = [];

    /**
     * 方法关联列表.
     *
     * @var \Imi\Bean\Annotation\Model\MethodAnnotationRelation[]
     */
    private $methodRelations = [];

    /**
     * 属性关联列表.
     *
     * @var \Imi\Bean\Annotation\Model\PropertyAnnotationRelation[]
     */
    private $propertyRelations = [];

    /**
     * 常量关联列表.
     *
     * @var \Imi\Bean\Annotation\Model\ConstantAnnotationRelation[]
     */
    private $constantRelations = [];

    /**
     * 所有关联列表.
     *
     * @var \Imi\Bean\Annotation\Model\IAnnotationRelation[]
     */
    private $allRelations = [];

    /**
     * Get 类关联列表.
     *
     * @return \Imi\Bean\Annotation\Model\ClassAnnotationRelation[]
     */
    public function getClassRelations()
    {
        return $this->classRelations;
    }

    /**
     * 增加类关联.
     *
     * @param \Imi\Bean\Annotation\Model\ClassAnnotationRelation $relation
     *
     * @return void
     */
    public function addClassRelation(ClassAnnotationRelation $relation)
    {
        $class = get_class($relation->getAnnotation());
        $this->classRelations[$class][] = $relation;
        $this->allRelations[$class] = null;
    }

    /**
     * Get 方法关联列表.
     *
     * @return \Imi\Bean\Annotation\Model\MethodAnnotationRelation[]
     */
    public function getMethodRelations()
    {
        return $this->methodRelations;
    }

    /**
     * 增加方法关联.
     *
     * @param \Imi\Bean\Annotation\Model\MethodAnnotationRelation $relation
     *
     * @return void
     */
    public function addMethodRelation(MethodAnnotationRelation $relation)
    {
        $class = get_class($relation->getAnnotation());
        $this->methodRelations[$class][] = $relation;
        $this->allRelations[$class] = null;
    }

    /**
     * Get 属性关联列表.
     *
     * @return \Imi\Bean\Annotation\Model\PropertyAnnotationRelation[]
     */
    public function getpropertyRelations()
    {
        return $this->propertyRelations;
    }

    /**
     * 增加属性关联.
     *
     * @param \Imi\Bean\Annotation\Model\PropertyAnnotationRelation $relation
     *
     * @return void
     */
    public function addPropertyRelation(PropertyAnnotationRelation $relation)
    {
        $class = get_class($relation->getAnnotation());
        $this->propertyRelations[$class][] = $relation;
        $this->allRelations[$class] = null;
    }

    /**
     * Get 常量关联列表.
     *
     * @return \Imi\Bean\Annotation\Model\ConstantAnnotationRelation[]
     */
    public function getConstantRelations()
    {
        return $this->constantRelations;
    }

    /**
     * 增加常量关联.
     *
     * @param \Imi\Bean\Annotation\Model\ConstantAnnotationRelation $relation
     *
     * @return void
     */
    public function addConstantRelation(ConstantAnnotationRelation $relation)
    {
        $class = get_class($relation->getAnnotation());
        $this->constantRelations[$class][] = $relation;
        $this->allRelations[$class] = null;
    }

    /**
     * 获取所有注解列表
     * 如果 $where 为 null，则返回指定注解列表.
     *
     * @param string      $className
     * @param string|null $where
     *
     * @return \Imi\Bean\Annotation\Model\IAnnotationRelation[]
     */
    public function getAll($className, $where = null)
    {
        if (null === $where) {
            $allRelations = &$this->allRelations;
            if (!isset($allRelations[$className])) {
                $allRelations[$className] = array_merge(
                    $this->classRelations[$className] ?? [],
                    $this->methodRelations[$className] ?? [],
                    $this->propertyRelations[$className] ?? [],
                    $this->constantRelations[$className] ?? []
                );
            }

            return $allRelations[$className];
        }

        return $this->{$where.'Relations'}[$className] ?? [];
    }

    /**
     * 移除类注解关联.
     *
     * @param string $annotationClassName
     * @param string $className
     *
     * @return void
     */
    public function removeClassRelation($annotationClassName, $className)
    {
        $classRelations = &$this->classRelations;
        if (isset($classRelations[$annotationClassName])) {
            foreach ($classRelations[$annotationClassName] as $i => $relation) {
                if ($relation->getClass() === $className) {
                    unset($classRelations[$annotationClassName][$i]);
                }
            }
            $classRelations[$annotationClassName] = array_values($classRelations[$annotationClassName]);
        }
        $this->allRelations[$annotationClassName] = null;
    }

    /**
     * 移除类注解关联.
     *
     * @param string $annotationClassName
     * @param string $className
     * @param string $methodName
     *
     * @return void
     */
    public function removeMethodRelation(string $annotationClassName, string $className, string $methodName)
    {
        $methodRelations = &$this->methodRelations;
        if (isset($methodRelations[$annotationClassName])) {
            foreach ($methodRelations[$annotationClassName] as $i => $relation) {
                if ($relation->getClass() === $className && $relation->getMethod() === $methodName) {
                    unset($methodRelations[$annotationClassName][$i]);
                }
            }
            $methodRelations[$annotationClassName] = array_values($methodRelations[$annotationClassName]);
        }
        $this->allRelations[$annotationClassName] = null;
    }

    /**
     * 移除类注解关联.
     *
     * @param string $annotationClassName
     * @param string $className
     * @param string $propertyName
     *
     * @return void
     */
    public function removePropertyRelation(string $annotationClassName, string $className, string $propertyName)
    {
        $propertyRelations = &$this->propertyRelations;
        if (isset($propertyRelations[$annotationClassName])) {
            foreach ($propertyRelations[$annotationClassName] as $i => $relation) {
                if ($relation->getClass() === $className && $relation->getProperty() === $propertyName) {
                    unset($propertyRelations[$annotationClassName][$i]);
                }
            }
            $propertyRelations[$annotationClassName] = array_values($propertyRelations[$annotationClassName]);
        }
        $this->allRelations[$annotationClassName] = null;
    }

    /**
     * 移除类注解关联.
     *
     * @param string $annotationClassName
     * @param string $className
     * @param string $constantName
     *
     * @return void
     */
    public function removeConstantRelation(string $annotationClassName, string $className, string $constantName)
    {
        $constantRelations = &$this->constantRelations;
        if (isset($constantRelations[$annotationClassName])) {
            foreach ($constantRelations[$annotationClassName] as $i => $relation) {
                if ($relation->getClass() === $className && $relation->getConstant() === $constantName) {
                    unset($constantRelations[$annotationClassName][$i]);
                }
            }
            $constantRelations[$annotationClassName] = array_values($constantRelations[$annotationClassName]);
        }
        $this->allRelations[$annotationClassName] = null;
    }
}
