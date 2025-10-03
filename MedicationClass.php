<?php

class MedicationClass {
    private $id;
    private $classNames = [];

    public function __construct($id = null) {
        $this->id = $id;
    }

    public function getId() { return $this->id; }
    public function getClassNames() { return $this->classNames; }

    public function addClassName(string $className,  array$classObjects ) {
        if (empty(trim($className))) {
            throw new InvalidArgumentException("Class name cannot be empty or whitespace");
        }

        if (empty($classObjects)) {
            throw new InvalidArgumentException("Class objects array cannot be empty");
        }
//        if (empty($classObjects)) {
//            throw new InvalidArgumentException("Class objects array cannot be empty");
//        }

//        if (array_key_exists($className, $this->classNames)) {
//            throw new InvalidArgumentException("Class category '{$className}' already exists");
//        }

        $this->classNames[$className] = $classObjects;
    }

    public function addClassCategories(array $categories): void {

        foreach ($categories as $className => $classObjects) {
            $this->addClassName($className, $classObjects);
        }
    }
    public function updateClassCategory(string $className, array $classObjects): void {
        if (!array_key_exists($className, $this->classNames)) {
            throw new InvalidArgumentException("Class category '{$className}' does not exist");
        }
        $this->classNames[$className] = $classObjects;
    }

    public function removeClassCategory(string $className): void {
        if (!array_key_exists($className, $this->classNames)) {
            throw new InvalidArgumentException("Class category '{$className}' does not exist");
        }
        unset($this->classNames[$className]);
    }
    public function hasClassCategory(string $className): bool {
        return array_key_exists($className, $this->classNames);
    }

    //  Get objects by category
    public function getClassObjects(string $className): array {
        return $this->classNames[$className] ?? [];
    }

    //  Count total objects across all categories
    public function countAllObjects(): int {
        return array_reduce($this->classNames, function($carry, $objects) {
            return $carry + count($objects);
        }, 0);
    }

    //  Count categories
    public function countCategories(): int {
        return count($this->classNames);
    }

    //  Get all category names
    public function getCategoryNames(): array {
        return array_keys($this->classNames);
    }

    // OPTIMIZED toArray with error handling



    public function toArray() {
        $result = [];
        foreach ($this->classNames as $className => $classObjects) {
            $classNameData = [];
            foreach ($classObjects as $object) {
                $classNameData[] = $object->toArray();
            }
            $result[$className] = $classNameData;
        }
        return $result;
    }
}



