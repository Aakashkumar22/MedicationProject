<?php
class ClassObject {
    private $id;
    private $associatedDrugs = [];

    public function __construct($id = null) {
        $this->id = $id;
    }

    public function getId() { return $this->id; }
    public function getAssociatedDrugs() { return $this->associatedDrugs; }

//    public function addAssociatedDrug($drugType, $associatedDrug) {
//        if (!isset($this->associatedDrugs[$drugType])) {
//            $this->associatedDrugs[$drugType] = [];
//        }
//        $this->associatedDrugs[$drugType][] = $associatedDrug;
//    }

    public function addAssociatedDrug(string $drugType, $associatedDrug): void {
        // Input validation
        if (empty(trim($drugType))) {
            throw new InvalidArgumentException("Drug type cannot be empty or whitespace");
        }

        if ($associatedDrug === null) {
            throw new InvalidArgumentException("Associated drug cannot be null");
        }

        // Validate object implements Arrayable (if we're using the interface)
//        if (!$associatedDrug instanceof Arrayable) {
//            throw new InvalidObjectException(
//                "Associated drug must implement Arrayable interface. " .
//                "Got: " . (is_object($associatedDrug) ? get_class($associatedDrug) : gettype($associatedDrug))
//            );
//        }
//
//        // Strategy 1: Prevent duplicate drugs within same type
//        if ($this->hasDrug($drugType, $associatedDrug)) {
//            throw new InvalidArgumentException(
//                "Duplicate drug found in type '{$drugType}'. Drug: " . $this->getDrugIdentifier($associatedDrug)
//            );
//        }

        // Strategy 2: Prevent duplicate drugs across ALL types
        // if ($this->hasDrugInAnyType($associatedDrug)) {
        //     throw new InvalidArgumentException("Drug already exists in another drug type");
        // }

        // Initialize drug type array if needed
        if (!isset($this->associatedDrugs[$drugType])) {
            $this->associatedDrugs[$drugType] = [];
        }

        $this->associatedDrugs[$drugType][] = $associatedDrug;
    }

    private function hasDrug(string $drugType, $drug): bool {
        if (!isset($this->associatedDrugs[$drugType])) {
            return false;
        }

        foreach ($this->associatedDrugs[$drugType] as $existingDrug) {
            if ($this->areDrugsEqual($existingDrug, $drug)) {
                return true;
            }
        }
        return false;
    }

    private function hasDrugInAnyType($drug): bool {
        foreach ($this->associatedDrugs as $drugType => $drugs) {
            foreach ($drugs as $existingDrug) {
                if ($this->areDrugsEqual($existingDrug, $drug)) {
                    return true;
                }
            }
        }
        return false;
    }

    private function areDrugsEqual($drug1, $drug2): bool {
        // Strategy 1: Compare by object identity
        // return $drug1 === $drug2;

        // Strategy 2: Compare by unique identifier (if drugs have IDs)
        if (method_exists($drug1, 'getId') && method_exists($drug2, 'getId')) {
            return $drug1->getId() === $drug2->getId();
        }

        // Strategy 3: Compare by array representation
        return $drug1->toArray() === $drug2->toArray();
    }

    private function getDrugIdentifier($drug): string {
        if (method_exists($drug, 'getId')) {
            return "ID: " . $drug->getId();
        }
        if (method_exists($drug, 'getName')) {
            return "Name: " . $drug->getName();
        }
        return spl_object_hash($drug);
    }

    // Enhanced management methods
    public function removeDrug(string $drugType, $drugToRemove): bool {
        if (!isset($this->associatedDrugs[$drugType])) {
            return false;
        }

        foreach ($this->associatedDrugs[$drugType] as $index => $drug) {
            if ($this->areDrugsEqual($drug, $drugToRemove)) {
                unset($this->associatedDrugs[$drugType][$index]);
                // Re-index array
                $this->associatedDrugs[$drugType] = array_values($this->associatedDrugs[$drugType]);

                // Remove empty drug type
                if (empty($this->associatedDrugs[$drugType])) {
                    unset($this->associatedDrugs[$drugType]);
                }
                return true;
            }
        }
        return false;
    }

    public function removeDrugType(string $drugType): bool {
        if (isset($this->associatedDrugs[$drugType])) {
            unset($this->associatedDrugs[$drugType]);
            return true;
        }
        return false;
    }

    public function getDrugsByType(string $drugType): array {
        return $this->associatedDrugs[$drugType] ?? [];
    }

    public function hasDrugType(string $drugType): bool {
        return isset($this->associatedDrugs[$drugType]);
    }

    public function getDrugTypes(): array {
        return array_keys($this->associatedDrugs);
    }

    public function toArray() {
        $result = [];
        foreach ($this->associatedDrugs as $drugType => $drugs) {
            $result[$drugType] = array_map(function($drug) {
                return $drug->toArray();
            }, $drugs);
        }
        return $result;
    }
}

