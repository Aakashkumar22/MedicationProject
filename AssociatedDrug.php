<?php
class AssociatedDrug {
    private $id;
    private $dose;
    private $name;
    private $strength;

    public function __construct($name, $strength, $dose = "", $id = null) {
        $this->id = $id;
        $this->dose = $dose;
        $this->name = $name;
        $this->strength = $strength;
    }

    public function getId() { return $this->id; }
    public function getDose() { return $this->dose; }
    public function getName() { return $this->name; }
    public function getStrength() { return $this->strength; }

    public function toArray() {
        return [
            'dose' => $this->dose,
            'name' => $this->name,
            'strength' => $this->strength
        ];
    }
}

