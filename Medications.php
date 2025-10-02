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

    // ADD THESE MISSING METHODS:
    public function isValid(): bool {
        return empty($this->validate());
    }

    public function validate(): array {
        $errors = [];

        if ($this->id !== null && $this->id <= 0) {
            $errors[] = 'Medication ID must be positive';
        }

        if (empty($this->medicationsClasses)) {
            $errors[] = 'Medication must have at least one medication class';
        }

        foreach ($this->medicationsClasses as $index => $medicationClass) {
            if (!$medicationClass instanceof MedicationClass) {
                $errors[] = "Item at index {$index} is not a MedicationClass instance";
                continue;
            }

            // If MedicationClass has validation, use it
            if (method_exists($medicationClass, 'validate')) {
                $classErrors = $medicationClass->validate();
                if (!empty($classErrors)) {
                    $errors[] = "Medication class at index {$index} has errors: " . implode(', ', $classErrors);
                }
            }
        }

        return $errors;
    }


    public function toJson() {
        return json_encode($this->toArray(), JSON_PRETTY_PRINT);
    }
}