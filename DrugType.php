<?php


//  Enum-like pattern for predefined drug types
final class DrugType
{
    const PAIN_RELIEF = 'pain_relief';
    const ANTIBIOTIC = 'antibiotic';
    const ANTIHISTAMINE = 'antihistamine';
    const CARDIOVASCULAR = 'cardiovascular';
    const DIABETES = 'diabetes';
    const MENTAL_HEALTH = 'mental_health';
    const GASTROINTESTINAL = 'gastrointestinal';
    const RESPIRATORY = 'respiratory';

    private static $validTypes = [
        self::PAIN_RELIEF => 'Pain Relief',
        self::ANTIBIOTIC => 'Antibiotic',
        self::ANTIHISTAMINE => 'Antihistamine',
        self::CARDIOVASCULAR => 'Cardiovascular',
        self::DIABETES => 'Diabetes',
        self::MENTAL_HEALTH => 'Mental Health',
        self::GASTROINTESTINAL => 'Gastrointestinal',
        self::RESPIRATORY => 'Respiratory'
    ];

    public static function isValid(string $type): bool
    {
        return array_key_exists($type, self::$validTypes);
    }

    public static function getDisplayName(string $type): string
    {
        if (!self::isValid($type)) {
            throw new InvalidArgumentException("Invalid drug type: {$type}");
        }
        return self::$validTypes[$type];
    }

    public static function getAllTypes(): array
    {
        return array_keys(self::$validTypes);
    }

    public static function getAllTypesWithDisplayNames(): array
    {
        return self::$validTypes;
    }
}

// Enhanced ClassObject with predefined drug types
//class ClassObject
//{
//    private $id;
//    private $associatedDrugs = [];
//
//    public function __construct($id = null)
//    {
//        $this->id = $id;
//        // Initialize with empty arrays for all valid types (optional)
//        // $this->initializeDrugTypes();
//    }
//
//    // OPTIONAL: Pre-initialize all valid drug types
//    private function initializeDrugTypes(): void
//    {
//        foreach (DrugType::getAllTypes() as $type) {
//            $this->associatedDrugs[$type] = [];
//        }
//    }
//
//    // ENHANCED: Validate drug types against predefined enum
//    public function addAssociatedDrug(string $drugType, $associatedDrug): void
//    {
//        // Validate drug type against predefined types
//        if (!DrugType::isValid($drugType)) {
//            $validTypes = implode(', ', DrugType::getAllTypes());
//            throw new InvalidArgumentException(
//                "Invalid drug type '{$drugType}'. Valid types are: {$validTypes}"
//            );
//        }
//
//        // Rest of validation remains the same...
//        if ($associatedDrug === null) {
//            throw new InvalidArgumentException("Associated drug cannot be null");
//        }
//
//        if (!$associatedDrug instanceof Arrayable) {
//            throw new InvalidObjectException(
//                "Associated drug must implement Arrayable interface. " .
//                "Got: " . (is_object($associatedDrug) ? get_class($associatedDrug) : gettype($associatedDrug))
//            );
//        }
//
//        // Initialize if not exists
//        if (!isset($this->associatedDrugs[$drugType])) {
//            $this->associatedDrugs[$drugType] = [];
//        }
//
//        // Check for duplicates
//        if ($this->hasDrug($drugType, $associatedDrug)) {
//            throw new InvalidArgumentException(
//                "Duplicate drug found in type '{$drugType}'. Drug: " . $this->getDrugIdentifier($associatedDrug)
//            );
//        }
//
//        $this->associatedDrugs[$drugType][] = $associatedDrug;
//    }
//
//    // ENHANCED: Get display name for drug types in output
//    public function toArray(): array
//    {
//        $result = [];
//
//        foreach ($this->associatedDrugs as $drugType => $drugs) {
//            $drugTypeData = [];
//
//            foreach ($drugs as $drug) {
//                try {
//                    $drugTypeData[] = $drug->toArray();
//                } catch (Exception $e) {
//                    error_log("Skipping invalid drug: " . $e->getMessage());
//                    continue;
//                }
//            }
//
//            if (!empty($drugTypeData)) {
//                // Use display name for better readability
//                $displayName = DrugType::getDisplayName($drugType);
//                $result[$displayName] = $drugTypeData;
//            }
//        }
//
//        return $result;
//    }
//}