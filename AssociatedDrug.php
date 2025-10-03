<?php
//class AssociatedDrug {
//    private $id;
//    private $dose;
//    private $name;
//    private $strength;
//
//    public function __construct($name, $strength, $dose = "", $id = null) {
//        $this->id = $id;
//        $this->dose = $dose;
//        $this->name = $name;
//        $this->strength = $strength;
//    }
//
//    public function getId() { return $this->id; }
//    public function getDose() { return $this->dose; }
//    public function getName() { return $this->name; }
//    public function getStrength() { return $this->strength; }
//
//    public function toArray() {
//        return [
//            'dose' => $this->dose,
//            'name' => $this->name,
//            'strength' => $this->strength
//        ];
//    }
//}

//
class AssociatedDrug
{
    private $id;
    private $dose;
    private $name;
    private $strength;

    // Enhanced constructor with validation
    public function __construct(string $name, string $strength, string $dose = "", $id = null)
    {
//        $this->validateInputs($name, $strength, $dose, $id);

        $this->id = $id;
        $this->name = $name;
        $this->strength = $strength;
        $this->dose = $dose;
    }

    // Comprehensive input validation
    private function validateInputs(string $name, string $strength, string $dose, $id): void
    {
        // Name validation
        if (empty(trim($name))) {
            throw new InvalidArgumentException("Drug name cannot be empty or whitespace");
        }

        if (strlen($name) > 100) {
            throw new InvalidArgumentException("Drug name cannot exceed 100 characters");
        }

        // Strength validation
        if (empty(trim($strength))) {
            throw new InvalidArgumentException("Drug strength cannot be empty");
        }

        if (!preg_match('/^\d+(\.\d+)?\s*(mg|mcg|g|ml|%|IU)$/i', $strength)) {
            throw new InvalidArgumentException(
                "Invalid strength format. Expected format: '500mg', '0.5g', '5ml', etc."
            );
        }

        // Dose validation
        if ($dose !== "" && strlen($dose) > 50) {
            throw new InvalidArgumentException("Dose instructions cannot exceed 50 characters");
        }

        // ID validation
        if ($id !== null && (!is_int($id) || $id <= 0)) {
            throw new InvalidArgumentException("ID must be a positive integer or null");
        }
    }



    private function validateStrength(string $strength): string
    {
        $strength = trim($strength);
        // Standardize unit formatting
        $strength = preg_replace('/\s+/', '', $strength);
        return $strength;
    }

    private function validateDose(string $dose): string
    {
        $dose = trim($dose);
        // Basic dose format validation
        if ($dose !== "" && !preg_match('/^[\w\s\-\d\.]+$/i', $dose)) {
            throw new InvalidArgumentException("Invalid characters in dose instructions");
        }
        return $dose;
    }

    //  Enhanced getters with type hints
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDose(): string
    {
        return $this->dose;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getStrength(): string
    {
        return $this->strength;
    }

    //Setters with validation for mutable operations
    public function updateDose(string $newDose): void
    {
        $validatedDose = $this->validateDose($newDose);
        $this->dose = $validatedDose;
    }

    public function updateStrength(string $newStrength): void
    {
        $validatedStrength = $this->validateStrength($newStrength);
        $this->strength = $validatedStrength;
    }

    //  Business logic methods
    public function isHighStrength(): bool
    {
        $numericStrength = floatval(preg_replace('/[^\d.]/', '', $this->strength));
        $unit = preg_replace('/[\d.]/', '', $this->strength);

        $thresholds = [
            'mg' => 500,
            'mcg' => 1000,
            'g' => 0.5,
            'ml' => 10
        ];

        return isset($thresholds[$unit]) && $numericStrength > $thresholds[$unit];
    }

    public function getFormattedDisplay(): string
    {
        $display = $this->name . ' ' . $this->strength;
        if (!empty($this->dose)) {
            $display .= ' - ' . $this->dose;
        }
        return $display;
    }



    //  Additional serialization methods
    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);
    }

    public function toArray(): array
    {
        // For API responses where minimal data is needed
        return [
            'dose' => $this->dose,
            'name' => $this->name,
            'strength' => $this->strength
//
        ];
    }

    //  Equality comparison methods
    public function equals(self $otherDrug): bool
    {
        if ($this->id !== null && $otherDrug->getId() !== null) {
            return $this->id === $otherDrug->getId();
        }

        return $this->name === $otherDrug->getName() &&
            $this->strength === $otherDrug->getStrength();
    }

    public function isSameDrugDifferentStrength(self $otherDrug): bool
    {
        return $this->name === $otherDrug->getName() &&
            $this->strength !== $otherDrug->getStrength();
    }

    //  Static factory methods for common patterns


    public static function fromArray(array $data): self
    {
        return new self(
            $data['name'] ?? '',
            $data['strength'] ?? '',
            $data['dose'] ?? '',
            $data['id'] ?? null
        );
    }
}

