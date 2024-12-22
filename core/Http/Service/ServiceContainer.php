<?php

namespace Core\Http\Service;

class ServiceContainer
{
    private array $instances = [];

    public function get(string $class)
    {
        if (isset($this->instances[$class])) {
            return $this->instances[$class];
        }

        $reflectionClass = new \ReflectionClass($class);
        $constructor = $reflectionClass->getConstructor();
        $parameters = $constructor ? $constructor->getParameters() : [];

        $dependencies = [];
        foreach ($parameters as $param) {
            $dependencyClass = $param->getType() && !$param->getType()->isBuiltin()
                ? new \ReflectionClass($param->getType()->getName())
                : null;

            if ($dependencyClass) {
                $dependencies[] = $this->get($dependencyClass->getName());
            } else {
                throw new \Exception("Cannot resolve dependency {$param->getName()}.");
            }
        }

        $this->instances[$class] = $reflectionClass->newInstanceArgs($dependencies);

        return $this->instances[$class];
    }
}