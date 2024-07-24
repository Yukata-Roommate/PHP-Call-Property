<?php

namespace YukataRm\CallProperty;

/**
 * Call Property trait
 * 
 * @package YukataRm\CallProperty
 */
trait CallProperty
{
    /**
     * access class properties as dynamic methods
     * 
     * @param string $propertyName
     * @param array<mixed> $parameters
     * @return mixed
     */
    public function __call(string $propertyName, array $parameters): mixed
    {
        $reflectionProperty = new \ReflectionProperty(self::class, $propertyName);

        if (!$reflectionProperty->isPublic()) throw new \RuntimeException("property {$propertyName} does not exist");

        if (empty($parameters)) return $reflectionProperty->isStatic() ? self::${$propertyName} : $this->{$propertyName};

        if ($reflectionProperty->isReadOnly()) throw new \RuntimeException("property {$propertyName} is read only");

        if (count($parameters) > 1) throw new \RuntimeException("too many parameters");

        $reflectionProperty->isStatic() ? self::${$propertyName} = $parameters[0] : $this->{$propertyName} = $parameters[0];
    }
}
