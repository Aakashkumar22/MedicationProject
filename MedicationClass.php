<?php

class MedicationClass {
    private $id;
    private $classNames = [];

    public function __construct($id = null) {
        $this->id = $id;
    }

    public function getId() { return $this->id; }
    public function getClassNames() { return $this->classNames; }

    public function addClassName($className, $classObjects) {
        $this->classNames[$className] = $classObjects;
    }

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

