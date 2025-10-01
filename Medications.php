<?php


class Medication {
    private $id;
    private $medicationsClasses = [];

    public function __construct($id = null) {
        $this->id = $id;
    }

    public function getId() { return $this->id; }
    public function getMedicationsClasses() { return $this->medicationsClasses; }

    public function addMedicationClass($medicationClass) {
        $this->medicationsClasses[] = $medicationClass;
    }

    public function toArray() {
        return [
            'medications' => [
                [
                    'medicationsClasses' => array_map(function($class) {
                        return $class->toArray();
                    }, $this->medicationsClasses)
                ]
            ]
        ];
    }

    public function toJson() {
        return json_encode($this->toArray(), JSON_PRETTY_PRINT);
    }
}