<?php


namespace GeekBrains\LevelTwo\Blog\Container;

use GeekBrains\LevelTwo\Blog\Exceptions\NotFoundException;
use Psr\Container\ContainerInterface;
use ReflectionClass;

class DIContainer implements ContainerInterface
{
    private array $resolvers = [];

    /**
     * @param string $type
     * @param $resolver
     */
    public function bind(string $type, $resolver): void
    {
        $this->resolvers[$type] = $resolver;
    }


    /**
     * @throws NotFoundException
     */
    public function get($id): mixed
    {
        if (array_key_exists($id, $this->resolvers)) {
            $typeToCreate = $this->resolvers[$id];
            if (is_object($typeToCreate)) {
                return $typeToCreate;
            }
            return $this->get($typeToCreate);
        }

        if (!class_exists($id)) {
            throw new NotFoundException("Cannot resolve type: $id");
        }
        $reflectionClass = new ReflectionClass($id);
        $constructor = $reflectionClass->getConstructor();
        if (null === $constructor) {
            return new $id();
        }
        $parameters = [];
        foreach ($constructor->getParameters() as $parameter) {
            $parameterType = $parameter->getType()->getName();
            $parameters[] = $this->get($parameterType);
        }

        return new $id(...$parameters);
    }


    public function has(string $id): bool
    {
        try {
            $this->get($id);
        } catch (NotFoundException $e) {
            return false;
        }
        return true;

    }
}