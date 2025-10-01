<?php
class ClassObject {
    private $id;
    private $associatedDrugs = [];

    public function __construct($id = null) {
        $this->id = $id;
    }

    public function getId() { return $this->id; }
    public function getAssociatedDrugs() { return $this->associatedDrugs; }

    public function addAssociatedDrug($drugType, $associatedDrug) {
        if (!isset($this->associatedDrugs[$drugType])) {
            $this->associatedDrugs[$drugType] = [];
        }
        $this->associatedDrugs[$drugType][] = $associatedDrug;
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

