<?php

namespace YukataRm\CallProperty;

/**
 * Access class properties as dynamic methods
 * 
 * @package YukataRm\CallProperty
 */
trait CallProperty
{
    /**
     * Access class properties as dynamic methods
     * 
     * if parameter is empty, get property value
     * if parameter is not empty, set property value
     * 
     * @param string $propertyName
     * @param array<mixed> $parameters
     * @return mixed
     */
    public function __call(string $propertyName, array $parameters): mixed
    {
        // get reflection property
        $reflectionProperty = new \ReflectionProperty(self::class, $propertyName);

        // if property does not public, throw exception
        if (!$reflectionProperty->isPublic()) throw new \RuntimeException("Property {$propertyName} does not exist");

        // if parameter is empty, get property value
        if (empty($parameters)) return $reflectionProperty->isStatic() ? self::${$propertyName} : $this->{$propertyName};

        // if property is read-only, throw exception
        if ($reflectionProperty->isReadOnly()) throw new \RuntimeException("Property {$propertyName} is read only");

        // if parameter is too many, throw exception
        if (count($parameters) > 1) throw new \RuntimeException("Too many parameters");

        // if parameter is not empty, set property value
        $reflectionProperty->isStatic() ? self::${$propertyName} = $parameters[0] : $this->{$propertyName} = $parameters[0];
    }
}
