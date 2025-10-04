<?php
//require_once 'Medications.php';
//require_once 'MedicationClass.php';
//require_once 'ClassObject.php';
//require_once 'AssociatedDrug.php';
//
//class MedicationRepository
//{
//    private $pdo;
//
//    public function __construct(PDO $pdo)
//    {
//        $this->pdo = $pdo;
//    }
//
//    public function saveMedication(Medication $medication)
//    {
//        try {
//            $this->pdo->beginTransaction();
//
//            // Save medication
//            $stmt = $this->pdo->prepare("INSERT INTO medications () VALUES ()");
//            $stmt->execute();
//            $medicationId = $this->pdo->lastInsertId();
//
//            // Save medication classes
//            foreach ($medication->getMedicationsClasses() as $medClass) {
//                $stmt = $this->pdo->prepare("INSERT INTO medication_classes (medication_id, class_name) VALUES (?, ?)");
//                $stmt->execute([$medicationId, $medClass->getClassName()]);
//                $classId = $this->pdo->lastInsertId();
//
//                // Save class objects
//                foreach ($medClass->getClassObjects() as $classObj) {
//                    $stmt = $this->pdo->prepare("INSERT INTO class_objects (medication_class_id, object_name) VALUES (?, ?)");
//                    $stmt->execute([$classId, $classObj->getObjectName()]);
//                    $objectId = $this->pdo->lastInsertId();
//
//                    // Save associated drugs
//                    foreach ($classObj->getAssociatedDrugs() as $drugType => $drugs) {
//                        foreach ($drugs as $drug) {
//                            $stmt = $this->pdo->prepare("INSERT INTO associated_drugs (class_object_id, drug_type, dose, name, strength) VALUES (?, ?, ?, ?, ?)");
//                            $stmt->execute([
//                                $objectId,
//                                $drugType,
//                                $drug->getDose(),
//                                $drug->getName(),
//                                $drug->getStrength()
//                            ]);
//                        }
//                    }
//                }
//            }
//
//            $this->pdo->commit();
//            return $medicationId;
//
//        } catch (Exception $e) {
//            $this->pdo->rollBack();
//            throw $e;
//        }
//    }
//
//    public function getMedicationById($id)
//    {
//        $medication = new Medication($id);
//
//        // Get medication classes
//        $stmt = $this->pdo->prepare("
//            SELECT * FROM medication_classes WHERE medication_id = ?
//        ");
//        $stmt->execute([$id]);
//        $classes = $stmt->fetchAll(PDO::FETCH_ASSOC);
//
//        foreach ($classes as $class) {
//            $medClass = new MedicationClass($class['class_name'], $class['id']);
//
//            // Get class objects
//            $stmt = $this->pdo->prepare("
//                SELECT * FROM class_objects WHERE medication_class_id = ?
//            ");
//            $stmt->execute([$class['id']]);
//            $objects = $stmt->fetchAll(PDO::FETCH_ASSOC);
//
//            foreach ($objects as $object) {
//                $classObj = new ClassObject($object['object_name'], $object['id']);
//
//                // Get associated drugs
//                $stmt = $this->pdo->prepare("
//                    SELECT * FROM associated_drugs WHERE class_object_id = ?
//                ");
//                $stmt->execute([$object['id']]);
//                $drugs = $stmt->fetchAll(PDO::FETCH_ASSOC);
//
//                foreach ($drugs as $drug) {
//                    $associatedDrug = new AssociatedDrug(
//                        $drug['name'],
//                        $drug['strength'],
//                        $drug['dose'],
//                        $drug['id']
//                    );
//                    $classObj->addAssociatedDrug($drug['drug_type'], $associatedDrug);
//                }
//
//                $medClass->addClassObject($classObj);
//            }
//
//            $medication->addMedicationClass($medClass);
//        }
//
//        return $medication;
//    }
//}


//class MedicationRepository
//{
//    private $pdo;
//
//    public function __construct($pdo)
//    {
//        $this->pdo = $pdo;
//    }
//
//    // Save medication structure to database
//    public function saveMedication(Medication $medication)
//    {
//        try {
//            $this->pdo->beginTransaction();
//
//            // Insert into medications table
//            $stmt = $this->pdo->prepare("INSERT INTO medications () VALUES ()");
//            $stmt->execute();
//            $medicationId = $this->pdo->lastInsertId();
//
//            // Save medication classes
//            foreach ($medication->getMedicationsClasses() as $medClass) {
//                // Insert into medication_classes (we don't need class_name anymore for the structure)
//                $stmt = $this->pdo->prepare("INSERT INTO medication_classes (medication_id) VALUES (?)");
//                $stmt->execute([$medicationId]);
//                $classId = $this->pdo->lastInsertId();
//
//                // Save class names and their objects
//                foreach ($medClass->getClassNames() as $className => $classObjects) {
//                    foreach ($classObjects as $classObj) {
//                        // Insert into class_objects (store className as object_name)
//                        $stmt = $this->pdo->prepare("INSERT INTO class_objects (medication_class_id, object_name) VALUES (?, ?)");
//                        $stmt->execute([$classId, $className]);
//                        $objectId = $this->pdo->lastInsertId();
//
//                        // Save associated drugs
//                        foreach ($classObj->getAssociatedDrugs() as $drugType => $drugs) {
//                            foreach ($drugs as $drug) {
//                                $stmt = $this->pdo->prepare("INSERT INTO associated_drugs (class_object_id, drug_type, dose, name, strength) VALUES (?, ?, ?, ?, ?)");
//                                $stmt->execute([
//                                    $objectId,
//                                    $drugType,
//                                    $drug->getDose(),
//                                    $drug->getName(),
//                                    $drug->getStrength()
//                                ]);
//                            }
//                        }
//                    }
//                }
//            }
//
//            $this->pdo->commit();
//            return $medicationId;
//
//        } catch (Exception $e) {
//            $this->pdo->rollBack();
//            throw $e;
//        }
//    }
//
//    // Load medication structure from database
//    public function getMedicationById($id)
//    {
//        $query = "
//        SELECT
//            m.id as medication_id,
//            m.created_at,
//            m.updated_at,
//            mc.id as class_id,
//            co.id as object_id,
//            co.object_name as class_name,
//            ad.id as drug_id,
//            ad.drug_type,
//            ad.name as drug_name,
//            ad.strength,
//            ad.dose
//        FROM medications m
//        LEFT JOIN medication_classes mc ON m.id = mc.medication_id
//        LEFT JOIN class_objects co ON mc.id = co.medication_class_id
//        LEFT JOIN associated_drugs ad ON co.id = ad.class_object_id
//        WHERE m.id = ?
//        ORDER BY mc.id, co.id, ad.drug_type
//    ";
//
//        $stmt = $this->pdo->prepare($query);
//        $stmt->execute([$id]);
//        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
//
//        if (empty($results)) {
//            return null;
//        }
//
//        return $this->buildMedicationFromResults($results);
//    }
//
//    private function buildMedicationFromResults($results)
//    {
//        $medication = new Medication($results[0]['medication_id']);
//        $medClass = new MedicationClass();
//
//        $classObjects = [];
//
//        foreach ($results as $row) {
//            $className = $row['class_name'];
//
//            // Skip if no class name (shouldn't happen in valid data)
//            if (empty($className)) {
//                continue;
//            }
//
//            // Initialize class object if not exists
//            if (!isset($classObjects[$className])) {
//                $classObjects[$className] = new ClassObject($row['object_id']);
//            }
//
//            // Add drug to class object if drug data exists
//            if (!empty($row['drug_type']) && !empty($row['drug_name'])) {
//                $classObjects[$className]->addAssociatedDrug(
//                    $row['drug_type'],
//                    new AssociatedDrug(
//                        $row['drug_name'],
//                        $row['strength'],
//                        $row['dose'],
//                        $row['drug_id']
//                    )
//                );
//            }
//        }
//
//        // Add all class objects to medication class
//        foreach ($classObjects as $className => $classObj) {
//            $medClass->addClassName($className, [$classObj]);
//        }
//
//        $medication->addMedicationClass($medClass);
//        return $medication;
//    }
//
//    // Get all medications
//    public function getAllMedications()
//    {
//        $stmt = $this->pdo->prepare("SELECT id FROM medications ORDER BY created_at DESC");
//        $stmt->execute();
//        $medicationIds = $stmt->fetchAll(PDO::FETCH_COLUMN);
//
//        $medications = [];
//        foreach ($medicationIds as $id) {
//            $medications[] = $this->getMedicationById($id);
//        }
//
//        return $medications;
//    }
//
//    // Delete medication
//    public function deleteMedication($id)
//    {
//        $stmt = $this->pdo->prepare("DELETE FROM medications WHERE id = ?");
//        return $stmt->execute([$id]);
//    }
//}
//
//can you chek my code again where is the issue and how to fix it <?php;
//require_once 'Medications.php';
//require_once 'MedicationClass.php';
//require_once 'ClassObject.php';
//require_once 'AssociatedDrug.php';
//
//class MedicationRepository
//{
//    private $pdo;
//
//    public function __construct(PDO $pdo)
//    {
//        $this->pdo = $pdo;
//    }
//
//    public function saveMedication(Medication $medication)
//    {
//        try {
//            $this->pdo->beginTransaction();
//
//            // Save medication
//            $stmt = $this->pdo->prepare("INSERT INTO medications () VALUES ()");
//            $stmt->execute();
//            $medicationId = $this->pdo->lastInsertId();
//
//            // Save medication classes
//            foreach ($medication->getMedicationsClasses() as $medClass) {
//                $stmt = $this->pdo->prepare("INSERT INTO medication_classes (medication_id, class_name) VALUES (?, ?)");
//                $stmt->execute([$medicationId, $medClass->getClassName()]);
//                $classId = $this->pdo->lastInsertId();
//
//                // Save class objects
//                foreach ($medClass->getClassObjects() as $classObj) {
//                    $stmt = $this->pdo->prepare("INSERT INTO class_objects (medication_class_id, object_name) VALUES (?, ?)");
//                    $stmt->execute([$classId, $classObj->getObjectName()]);
//                    $objectId = $this->pdo->lastInsertId();
//
//                    // Save associated drugs
//                    foreach ($classObj->getAssociatedDrugs() as $drugType => $drugs) {
//                        foreach ($drugs as $drug) {
//                            $stmt = $this->pdo->prepare("INSERT INTO associated_drugs (class_object_id, drug_type, dose, name, strength) VALUES (?, ?, ?, ?, ?)");
//                            $stmt->execute([
//                                $objectId,
//                                $drugType,
//                                $drug->getDose(),
//                                $drug->getName(),
//                                $drug->getStrength()
//                            ]);
//                        }
//                    }
//                }
//            }
//
//            $this->pdo->commit();
//            return $medicationId;
//
//        } catch (Exception $e) {
//            $this->pdo->rollBack();
//            throw $e;
//        }
//    }
//
//    public function getMedicationById($id)
//    {
//        $medication = new Medication($id);
//
//        // Get medication classes
//        $stmt = $this->pdo->prepare("
//            SELECT * FROM medication_classes WHERE medication_id = ?
//        ");
//        $stmt->execute([$id]);
//        $classes = $stmt->fetchAll(PDO::FETCH_ASSOC);
//
//        foreach ($classes as $class) {
//            $medClass = new MedicationClass($class['class_name'], $class['id']);
//
//            // Get class objects
//            $stmt = $this->pdo->prepare("
//                SELECT * FROM class_objects WHERE medication_class_id = ?
//            ");
//            $stmt->execute([$class['id']]);
//            $objects = $stmt->fetchAll(PDO::FETCH_ASSOC);
//
//            foreach ($objects as $object) {
//                $classObj = new ClassObject($object['object_name'], $object['id']);
//
//                // Get associated drugs
//                $stmt = $this->pdo->prepare("
//                    SELECT * FROM associated_drugs WHERE class_object_id = ?
//                ");
//                $stmt->execute([$object['id']]);
//                $drugs = $stmt->fetchAll(PDO::FETCH_ASSOC);
//
//                foreach ($drugs as $drug) {
//                    $associatedDrug = new AssociatedDrug(
//                        $drug['name'],
//                        $drug['strength'],
//                        $drug['dose'],
//                        $drug['id']
//                    );
//                    $classObj->addAssociatedDrug($drug['drug_type'], $associatedDrug);
//                }
//
//                $medClass->addClassObject($classObj);
//            }
//
//            $medication->addMedicationClass($medClass);
//        }
//
//        return $medication;
//    }
//}


//class MedicationRepository
//{
//    private $pdo;
//
//    public function __construct($pdo)
//    {
//        $this->pdo = $pdo;
//    }
//
//    // Save medication structure to database
//    public function saveMedication(Medication $medication)
//    {
//        try {
//            $this->pdo->beginTransaction();
//
//            // Insert into medications table
//            $stmt = $this->pdo->prepare("INSERT INTO medications () VALUES ()");
//            $stmt->execute();
//            $medicationId = $this->pdo->lastInsertId();
//
//            // Save medication classes
//            foreach ($medication->getMedicationsClasses() as $medClass) {
//                // Insert into medication_classes (we don't need class_name anymore for the structure)
//                $stmt = $this->pdo->prepare("INSERT INTO medication_classes (medication_id) VALUES (?)");
//                $stmt->execute([$medicationId]);
//                $classId = $this->pdo->lastInsertId();
//
//                // Save class names and their objects
//                foreach ($medClass->getClassNames() as $className => $classObjects) {
//                    foreach ($classObjects as $classObj) {
//                        // Insert into class_objects (store className as object_name)
//                        $stmt = $this->pdo->prepare("INSERT INTO class_objects (medication_class_id, object_name) VALUES (?, ?)");
//                        $stmt->execute([$classId, $className]);
//                        $objectId = $this->pdo->lastInsertId();
//
//                        // Save associated drugs
//                        foreach ($classObj->getAssociatedDrugs() as $drugType => $drugs) {
//                            foreach ($drugs as $drug) {
//                                $stmt = $this->pdo->prepare("INSERT INTO associated_drugs (class_object_id, drug_type, dose, name, strength) VALUES (?, ?, ?, ?, ?)");
//                                $stmt->execute([
//                                    $objectId,
//                                    $drugType,
//                                    $drug->getDose(),
//                                    $drug->getName(),
//                                    $drug->getStrength()
//                                ]);
//                            }
//                        }
//                    }
//                }
//            }
//
//            $this->pdo->commit();
//            return $medicationId;
//
//        } catch (Exception $e) {
//            $this->pdo->rollBack();
//            throw $e;
//        }
//    }
//
//    // Load medication structure from database
//    public function getMedicationById($id)
//    {
//        $query = "
//        SELECT
//            m.id as medication_id,
//            m.created_at,
//            m.updated_at,
//            mc.id as class_id,
//            co.id as object_id,
//            co.object_name as class_name,
//            ad.id as drug_id,
//            ad.drug_type,
//            ad.name as drug_name,
//            ad.strength,
//            ad.dose
//        FROM medications m
//        LEFT JOIN medication_classes mc ON m.id = mc.medication_id
//        LEFT JOIN class_objects co ON mc.id = co.medication_class_id
//        LEFT JOIN associated_drugs ad ON co.id = ad.class_object_id
//        WHERE m.id = ?
//        ORDER BY mc.id, co.id, ad.drug_type
//    ";
//
//        $stmt = $this->pdo->prepare($query);
//        $stmt->execute([$id]);
//        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
//
//        if (empty($results)) {
//            return null;
//        }
//
//        return $this->buildMedicationFromResults($results);
//    }
//
//    private function buildMedicationFromResults($results)
//    {
//        $medication = new Medication($results[0]['medication_id']);
//        $medClass = new MedicationClass();
//
//        $classObjects = [];
//
//        foreach ($results as $row) {
//            $className = $row['class_name'];
//
//            // Skip if no class name (shouldn't happen in valid data)
//            if (empty($className)) {
//                continue;
//            }
//
//            // Initialize class object if not exists
//            if (!isset($classObjects[$className])) {
//                $classObjects[$className] = new ClassObject($row['object_id']);
//            }
//
//            // Add drug to class object if drug data exists
//            if (!empty($row['drug_type']) && !empty($row['drug_name'])) {
//                $classObjects[$className]->addAssociatedDrug(
//                    $row['drug_type'],
//                    new AssociatedDrug(
//                        $row['drug_name'],
//                        $row['strength'],
//                        $row['dose'],
//                        $row['drug_id']
//                    )
//                );
//            }
//        }
//
//        // Add all class objects to medication class
//        foreach ($classObjects as $className => $classObj) {
//            $medClass->addClassName($className, [$classObj]);
//        }
//
//        $medication->addMedicationClass($medClass);
//        return $medication;
//    }
//
//    // Get all medications
//    public function getAllMedications()
//    {
//        $stmt = $this->pdo->prepare("SELECT id FROM medications ORDER BY created_at DESC");
//        $stmt->execute();
//        $medicationIds = $stmt->fetchAll(PDO::FETCH_COLUMN);
//
//        $medications = [];
//        foreach ($medicationIds as $id) {
//            $medications[] = $this->getMedicationById($id);
//        }
//
//        return $medications;
//    }
//
//    // Delete medication
//    public function deleteMedication($id)
//    {
//        $stmt = $this->pdo->prepare("DELETE FROM medications WHERE id = ?");
//        return $stmt->execute([$id]);
//    }
//}
//


//class MedicationRepository
//{
//    private PDO $pdo;
//    private const BATCH_SIZE = 100;
//
//    public function __construct(PDO $pdo)
//    {
//        $this->pdo = $pdo;
//        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//        $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
//    }
//
//    //  Input validation
//    private function validateMedication(Medication $medication): void
//    {
//        if (!$medication->isValid()) {
//            throw new InvalidArgumentException("Invalid medication data");
//        }
//    }
//
//    private function validateId($id): void
//    {
//        if (!is_int($id) || $id <= 0) {
//            throw new InvalidArgumentException("ID must be a positive integer");
//        }
//    }
//
//    // Optimized save with batch operations
//    public function saveMedication(Medication $medication): int {
//        $this->validateMedication($medication);
//
//        $this->pdo->beginTransaction();
//
//        try {
//            $medicationId = $this->insertMedication();
//            $this->insertMedicationHierarchy($medicationId, $medication);
//            $this->pdo->commit();
//
//            return $medicationId;
//
//        } catch (Exception $e) {
//            $this->pdo->rollBack();
//            throw new RepositoryException("Failed to save medication", 0, $e);
//        }
//    }
//
//    private function insertMedication(): int {
//        $stmt = $this->pdo->prepare("INSERT INTO medications (created_at) VALUES (NOW())");
//        $stmt->execute();
//        return (int)$this->pdo->lastInsertId();
//    }
//
//    private function insertMedicationHierarchy(int $medicationId, Medication $medication): void {
//        $medicationClasses = $medication->getMedicationsClasses();
//
//        if (empty($medicationClasses)) {
//            return;
//        }
//
//        // Batch insert medication classes
//        $classIds = $this->batchInsertMedicationClasses($medicationId, $medicationClasses);
//
//        // Batch insert class objects and drugs
//        $this->batchInsertClassObjectsAndDrugs($classIds, $medicationClasses);
//    }
//
//    private function batchInsertMedicationClasses(int $medicationId, array $medicationClasses): array {
//        $placeholders = [];
//        $values = [];
//
//        foreach ($medicationClasses as $medClass) {
//            $placeholders[] = '(?)';
//            $values[] = $medicationId;
//        }
//
//        $sql = "INSERT INTO medication_classes (medication_id) VALUES " . implode(', ', $placeholders);
//        $stmt = $this->pdo->prepare($sql);
//        $stmt->execute($values);
//
//        // Get all inserted IDs efficiently
//        $firstId = (int)$this->pdo->lastInsertId();
//        $classIds = range($firstId, $firstId + count($medicationClasses) - 1);
//
//        return $classIds;
//    }
//
//    private function batchInsertClassObjectsAndDrugs(array $classIds, array $medicationClasses): void {
//        $objectsBatch = [];
//        $drugsBatch = [];
//
//        // Prepare all data for batch insertion
//        foreach ($medicationClasses as $index => $medClass) {
//            $classId = $classIds[$index];
//            $classNames = $medClass->getClassNames();
//
//            foreach ($classNames as $className => $classObjects) {
//                foreach ($classObjects as $classObj) {
//                    $objectKey = "{$classId}_{$className}";
//                    $objectsBatch[$objectKey] = [$classId, $className];
//
//                    // Collect drugs for batch insertion
//                    $associatedDrugs = $classObj->getAssociatedDrugs();
//                    foreach ($associatedDrugs as $drugType => $drugs) {
//                        foreach ($drugs as $drug) {
//                            $drugsBatch[] = [
//                                'object_key' => $objectKey,
//                                'drug_type' => $drugType,
//                                'name' => $drug->getName(),
//                                'strength' => $drug->getStrength(),
//                                'dose' => $drug->getDose()
//                            ];
//                        }
//                    }
//                }
//            }
//        }
//
//        // Batch insert class objects and get their IDs
//        $objectIdMap = $this->batchInsertClassObjects($objectsBatch);
//
//        // Batch insert drugs
//        $this->batchInsertDrugs($objectIdMap, $drugsBatch);
//    }
//
//    private function batchInsertClassObjects(array $objectsBatch): array {
//        if (empty($objectsBatch)) {
//            return [];
//        }
//
//        $placeholders = [];
//        $values = [];
//        $objectKeys = [];
//
//        foreach ($objectsBatch as $objectKey => $objectData) {
//            $placeholders[] = '(?, ?)';
//            $values[] = $objectData[0]; // class_id
//            $values[] = $objectData[1]; // class_name
//            $objectKeys[] = $objectKey;
//        }
//
//        $sql = "INSERT INTO class_objects (medication_class_id, object_name) VALUES " . implode(', ', $placeholders);
//        $stmt = $this->pdo->prepare($sql);
//        $stmt->execute($values);
//
//        // Map object keys to their IDs
//        $firstId = (int)$this->pdo->lastInsertId();
//        $objectIdMap = [];
//
//        foreach ($objectKeys as $index => $objectKey) {
//            $objectIdMap[$objectKey] = $firstId + $index;
//        }
//
//        return $objectIdMap;
//    }
//
//    private function batchInsertDrugs(array $objectIdMap, array $drugsBatch): void {
//        if (empty($drugsBatch)) {
//            return;
//        }
//
//        $placeholders = [];
//        $values = [];
//
//        foreach ($drugsBatch as $drugData) {
//            $objectId = $objectIdMap[$drugData['object_key']] ?? null;
//            if (!$objectId) {
//                continue;
//            }
//
//            $placeholders[] = '(?, ?, ?, ?, ?)';
//            $values[] = $objectId;
//            $values[] = $drugData['drug_type'];
//            $values[] = $drugData['name'];
//            $values[] = $drugData['strength'];
//            $values[] = $drugData['dose'];
//        }
//
//        if (empty($placeholders)) {
//            return;
//        }
//
//        $sql = "INSERT INTO associated_drugs (class_object_id, drug_type, name, strength, dose) VALUES " .
//            implode(', ', $placeholders);
//        $stmt = $this->pdo->prepare($sql);
//        $stmt->execute($values);
//    }
//    // Optimized get by ID with caching
//    public function getMedicationById(int $id): ?Medication {
//        $this->validateId($id);
//
//        $startTime = microtime(true);
//        $results = $this->fetchMedicationData($id);
//
//        if (empty($results)) {
//            return null;
//        }
//
//        $medication = $this->buildMedicationFromResults($results);
//
//
//
//        return $medication;
//    }
//
//    private function fetchMedicationData(int $id): array {
//        $query = "
//            SELECT
//                m.id as medication_id,
//                m.created_at,
//                m.updated_at,
//                mc.id as class_id,
//                co.id as object_id,
//                co.object_name as class_name,
//                ad.id as drug_id,
//                ad.drug_type,
//                ad.name as drug_name,
//                ad.strength,
//                ad.dose
//            FROM medications m
//            INNER JOIN medication_classes mc ON m.id = mc.medication_id
//            INNER JOIN class_objects co ON mc.id = co.medication_class_id
//            LEFT JOIN associated_drugs ad ON co.id = ad.class_object_id
//            WHERE m.id = ?
//            ORDER BY mc.id, co.id, ad.id
//        ";
//
//        $stmt = $this->pdo->prepare($query);
//        $stmt->execute([$id]);
//        return $stmt->fetchAll(PDO::FETCH_ASSOC);
//    }
//
//    // Fixed and optimized hierarchy rebuilding
//    private function buildMedicationFromResults(array $results): Medication {
//        $medication = new Medication($results[0]['medication_id']);
//        $classesMap = []; // class_id => MedicationClass
//        $objectsMap = []; // object_id => ClassObject
//
//        foreach ($results as $row) {
//            $classId = $row['class_id'];
//
//            // Create MedicationClass if not exists
//            if (!isset($classesMap[$classId])) {
//                $classesMap[$classId] = new MedicationClass($classId);
//            }
//
//            $objectId = $row['object_id'];
//            $className = $row['class_name'];
//
//            if (empty($objectId) || empty($className)) {
//                continue;
//            }
//
//            // Create ClassObject if not exists
//            if (!isset($objectsMap[$objectId])) {
//                $objectsMap[$objectId] = new ClassObject($objectId);
//                $classesMap[$classId]->addClassName($className, [$objectsMap[$objectId]]);
//            }
//
//            // Add drug if exists
//
//            if (!empty($row['drug_id']) && !empty($row['drug_name'])) {
//                $drugType = $row['drug_type'] ?? 'associatedDrug'; // Default fallback
//
//                // **FIX: Use exact values from database without modification**
//                $associatedDrug = new AssociatedDrug(
//                    $row['drug_name'],  // Use exactly what's in DB: "asprin" or "somethingElse"
//                    $row['strength'],
//                    $row['dose'],
//                    $row['drug_id']
//                );
//
//                $objectsMap[$objectId]->addAssociatedDrug($drugType, $associatedDrug);
//            }
//        }
//
//        // Add all medication classes to medication
//        foreach ($classesMap as $medClass) {
//            $medication->addMedicationClass($medClass);
//        }
//
//        return $medication;
//    }
//    //  Single query for all medications
//    public function getAllMedications(): array {
//        $query = "
//            SELECT
//                m.id as medication_id,
//                m.created_at,
//                m.updated_at,
//                mc.id as class_id,
//                co.id as object_id,
//                co.object_name as class_name,
//                ad.id as drug_id,
//                ad.drug_type,
//                ad.name as drug_name,
//                ad.strength,
//                ad.dose
//            FROM medications m
//            INNER JOIN medication_classes mc ON m.id = mc.medication_id
//            INNER JOIN class_objects co ON mc.id = co.medication_class_id
//            LEFT JOIN associated_drugs ad ON co.id = ad.class_object_id
//            ORDER BY m.created_at DESC, mc.id, co.id, ad.id
//        ";
//
//        $stmt = $this->pdo->prepare($query);
//        $stmt->execute();
//        $allResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
//
//        return $this->buildMultipleMedicationsFromResults($allResults);
//    }
//
//    private function buildMultipleMedicationsFromResults(array $allResults): array {
//        $medicationsMap = [];
//        $currentMedicationId = null;
//        $currentResults = [];
//
//        // Group results by medication ID
//        foreach ($allResults as $row) {
//            $medicationId = $row['medication_id'];
//
//            if ($medicationId !== $currentMedicationId) {
//                if (!empty($currentResults)) {
//                    $medicationsMap[$currentMedicationId] = $this->buildMedicationFromResults($currentResults);
//                }
//                $currentResults = [];
//                $currentMedicationId = $medicationId;
//            }
//            $currentResults[] = $row;
//        }
//
//        // Process the last group
//        if (!empty($currentResults)) {
//            $medicationsMap[$currentMedicationId] = $this->buildMedicationFromResults($currentResults);
//        }
//
//        return array_values($medicationsMap);
//    }
//
//    // Pagination support
//    public function getMedicationsPaginated(int $page = 1, int $perPage = 10): array {
//        $offset = ($page - 1) * $perPage;
//
//        $stmt = $this->pdo->prepare("
//            SELECT id FROM medications
//            ORDER BY created_at DESC
//            LIMIT ? OFFSET ?
//        ");
//        $stmt->execute([$perPage, $offset]);
//        $medicationIds = $stmt->fetchAll(PDO::FETCH_COLUMN);
//
//        return array_map([$this, 'getMedicationById'], $medicationIds);
//    }
//
//    public function countMedications(): int {
//        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM medications");
//        $stmt->execute();
//        return (int)$stmt->fetchColumn();
//    }
//
//    //  Proper deletion with constraints
//    public function deleteMedication(int $id): bool {
//        $this->validateId($id);
//
//        $this->pdo->beginTransaction();
//        try {
//            // Delete in reverse order of dependencies
//            $queries = [
//                "DELETE ad FROM associated_drugs ad
//                 INNER JOIN class_objects co ON ad.class_object_id = co.id
//                 INNER JOIN medication_classes mc ON co.medication_class_id = mc.id
//                 WHERE mc.medication_id = ?",
//
//                "DELETE co FROM class_objects co
//                 INNER JOIN medication_classes mc ON co.medication_class_id = mc.id
//                 WHERE mc.medication_id = ?",
//
//                "DELETE FROM medication_classes WHERE medication_id = ?",
//                "DELETE FROM medications WHERE id = ?"
//            ];
//
//            foreach ($queries as $query) {
//                $stmt = $this->pdo->prepare($query);
//                $stmt->execute([$id]);
//            }
//
//            $this->pdo->commit();
//            return true;
//
//        } catch (Exception $e) {
//            $this->pdo->rollBack();
//            throw new RepositoryException("Failed to delete medication", 0, $e);
//        }
//    }
//
//    // Update operation
//    public function updateMedication(Medication $medication): bool {
//        $this->validateMedication($medication);
//
//        if ($medication->getId() === null) {
//            throw new InvalidArgumentException("Cannot update medication without ID");
//        }
//
//        $this->pdo->beginTransaction();
//        try {
//            // Delete and re-insert (simplest approach for complex hierarchies)
//            $this->deleteMedication($medication->getId());
//            $this->insertMedicationHierarchy($medication->getId(), $medication);
//
//            $this->pdo->commit();
//            return true;
//
//        } catch (Exception $e) {
//            $this->pdo->rollBack();
//            throw new RepositoryException("Failed to update medication", 0, $e);
//        }
//    }
//}
//
////  Custom exception
//class RepositoryException extends Exception {}


class MedicationRepository
{
    private PDO $pdo;
    private const BATCH_SIZE = 100;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }




    public function saveMedication(Medication $medication): int
    {
        $this->validateMedication($medication);

        $this->pdo->beginTransaction();
        try {
            $medicationId = $this->insertMedication();
            $this->insertMedicationHierarchy($medicationId, $medication);
            $this->pdo->commit();
            return $medicationId;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw new RepositoryException("Failed to save medication", 0, $e);
        }
    }

    /**
     * SQL Query: INSERT INTO medications (created_at) VALUES (NOW())
     * Time Complexity: O(1)
     */
    private function insertMedication(): int
    {
        $stmt = $this->pdo->prepare("INSERT INTO medications (created_at) VALUES (NOW())");
        $stmt->execute();
        return (int)$this->pdo->lastInsertId();
    }

    /**
     * Time Complexity: O(n + m + p) with batching vs O(n*m*p) without
     */
    private function insertMedicationHierarchy(int $medicationId, Medication $medication): void
    {
        $medicationClasses = $medication->getMedicationsClasses();
        if (empty($medicationClasses)) return;

        // FIXED: Proper ID handling
        $classIdMap = $this->batchInsertMedicationClasses($medicationId, $medicationClasses);
        $this->batchInsertClassObjectsAndDrugs($classIdMap, $medicationClasses);
    }

    /**
     * SQL Query: Batch INSERT with multiple VALUES
     * Time Complexity: O(n) where n = number of classes
     * Space Complexity: O(n) for the prepared statement
     */
    private function batchInsertMedicationClasses(int $medicationId, array $medicationClasses): array
    {
        $placeholders = [];
        $values = [];

        foreach ($medicationClasses as $index => $medClass) {
            $placeholders[] = '(?)';
            $values[] = $medicationId;
        }

        $sql = "INSERT INTO medication_classes (medication_id) VALUES " . implode(', ', $placeholders);
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($values);

        //  Get actual IDs from database
        return $this->getLastInsertedIds('medication_classes', count($medicationClasses));
    }

    /**
     * SQL Query: SELECT id FROM table ORDER BY id DESC LIMIT ?
     * Time Complexity: O(k) where k = number of IDs to retrieve
     */
    private function getLastInsertedIds(string $table, int $count): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT id FROM $table ORDER BY id DESC LIMIT ?"
        );
        $stmt->execute([$count]);
        $ids = $stmt->fetchAll(PDO::FETCH_COLUMN);
        return array_reverse($ids); // Return in insertion order
    }

    /**
     * Time Complexity: O(m * p) where:
     * - m = total class objects across all classes
     * - p = average drugs per object
     */
    private function batchInsertClassObjectsAndDrugs(array $classIdMap, array $medicationClasses): void
    {
        $objectBatches = [];
        $drugBatches = [];

        foreach ($medicationClasses as $classIndex => $medClass) {
            $classId = $classIdMap[$classIndex] ?? null;
            if (!$classId) continue;

            $classNames = $medClass->getClassNames();
            foreach ($classNames as $className => $classObjects) {
                foreach ($classObjects as $objIndex => $classObj) {
                    // FIXED: Unique key generation
                    $objectKey = "class_{$classId}_obj_{$objIndex}_" . uniqid();
                    $objectBatches[$objectKey] = [$classId, $className];

                    $this->collectDrugsForBatch($drugBatches, $objectKey, $classObj);
                }
            }
        }

        $objectIdMap = $this->batchInsertClassObjects($objectBatches);
        $this->batchInsertDrugs($objectIdMap, $drugBatches);
    }

    /**
     * Time Complexity: O(p) per class object
     */
    private function collectDrugsForBatch(array &$drugBatches, string $objectKey, ClassObject $classObj): void
    {
        $associatedDrugs = $classObj->getAssociatedDrugs();
        foreach ($associatedDrugs as $drugType => $drugs) {
            foreach ($drugs as $drug) {
                $drugBatches[] = [
                    'object_key' => $objectKey,
                    'drug_type' => $drugType,
                    'name' => $drug->getName(),
                    'strength' => $drug->getStrength(),
                    'dose' => $drug->getDose()
                ];
            }
        }
    }

    /**
     * SQL Query: Batch INSERT with multiple VALUES
     * Time Complexity: O(m) where m = number of class objects
     */
    private function batchInsertClassObjects(array $objectsBatch): array
    {
        if (empty($objectsBatch)) return [];

        $placeholders = [];
        $values = [];
        $objectKeys = array_keys($objectsBatch);

        foreach ($objectsBatch as $objectData) {
            $placeholders[] = '(?, ?)';
            $values[] = $objectData[0]; // class_id
            $values[] = $objectData[1]; // class_name
        }

        $sql = "INSERT INTO class_objects (medication_class_id, object_name) VALUES " .
            implode(', ', $placeholders);
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($values);

        // FIXED: Get actual IDs
        return $this->mapObjectKeysToIds($objectKeys, count($objectsBatch));
    }

    /**
     * Time Complexity: O(m) for mapping
     */
    private function mapObjectKeysToIds(array $objectKeys, int $count): array
    {
        $ids = $this->getLastInsertedIds('class_objects', $count);
        $objectIdMap = [];

        foreach ($objectKeys as $index => $objectKey) {
            $objectIdMap[$objectKey] = $ids[$index] ?? null;
        }

        return array_filter($objectIdMap); // Remove null values
    }

    /**
     * SQL Query: Batch INSERT with multiple VALUES
     * Time Complexity: O(p) where p = total number of drugs
     */
    private function batchInsertDrugs(array $objectIdMap, array $drugsBatch): void
    {
        if (empty($drugsBatch)) return;

        $placeholders = [];
        $values = [];
        $validDrugs = 0;

        foreach ($drugsBatch as $drugData) {
            $objectId = $objectIdMap[$drugData['object_key']] ?? null;
            if (!$objectId) continue;

            $placeholders[] = '(?, ?, ?, ?, ?)';
            $values[] = $objectId;
            $values[] = $drugData['drug_type'];
            $values[] = $drugData['name'];
            $values[] = $drugData['strength'];
            $values[] = $drugData['dose'];
            $validDrugs++;
        }

        if ($validDrugs === 0) return;

        // Process in batches to avoid query size limits
        foreach (array_chunk($placeholders, self::BATCH_SIZE) as $chunkIndex => $chunkPlaceholders) {
            $chunkValues = array_slice($values, $chunkIndex * self::BATCH_SIZE * 5, count($chunkPlaceholders) * 5);

            $sql = "INSERT INTO associated_drugs (class_object_id, drug_type, name, strength, dose) VALUES " .
                implode(', ', $chunkPlaceholders);
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($chunkValues);
        }
    }

    // ========== FIXED GET METHOD ==========

    /**
     * SQL Query: Complex JOIN across 4 tables
     * Time Complexity:
     * - Query: O(log n) with proper indexing on foreign keys
     * - Processing: O(r) where r = number of result rows
     */
    public function getMedicationById(int $id): ?Medication
    {
        $this->validateId($id);
        $results = $this->fetchMedicationData($id);
        return empty($results) ? null : $this->buildMedicationFromResults($results);
    }

    /**
     * SQL Query Analysis:
     * - 3 JOIN operations: O(j * k) where j,k = table sizes
     * - WHERE clause: O(log n) with index on medications.id
     * - Overall: O(r) where r = result set size
     */
    private function fetchMedicationData(int $id): array
    {
        $query = "
            SELECT 
                m.id as medication_id,
                m.created_at,
                m.updated_at,
                mc.id as class_id,
                co.id as object_id,
                co.object_name as class_name,
                ad.id as drug_id,
                ad.drug_type,
                ad.name as drug_name,
                ad.strength,
                ad.dose
            FROM medications m
            INNER JOIN medication_classes mc ON m.id = mc.medication_id
            INNER JOIN class_objects co ON mc.id = co.medication_class_id
            LEFT JOIN associated_drugs ad ON co.id = ad.class_object_id
            WHERE m.id = ?
            ORDER BY mc.id, co.id, ad.id
        ";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Time Complexity: O(r) where r = number of result rows
     * Space Complexity: O(c + o) where c = classes, o = objects
     */
    private function buildMedicationFromResults(array $results): Medication
    {
        $medication = new Medication($results[0]['medication_id']);
        $classesMap = [];
        $objectsMap = [];

        foreach ($results as $row) {
            $classId = $row['class_id'];

            if (!isset($classesMap[$classId])) {
                $classesMap[$classId] = new MedicationClass($classId);
            }

            $this->processClassObject($classesMap[$classId], $objectsMap, $row);
        }

        foreach ($classesMap as $medClass) {
            $medication->addMedicationClass($medClass);
        }

        return $medication;
    }

    /**
     * Time Complexity: O(1) per row (amortized)
     */
    private function processClassObject(MedicationClass $medClass, array &$objectsMap, array $row): void
    {
        $objectId = $row['object_id'];
        $className = $row['class_name'];

        if (empty($objectId) || empty($className)) return;

        if (!isset($objectsMap[$objectId])) {
            $objectsMap[$objectId] = new ClassObject($objectId);
            $medClass->addClassName($className, [$objectsMap[$objectId]]);
        }

        $this->processDrug($objectsMap[$objectId], $row);
    }

    /**
     * Time Complexity: O(1) per drug row
     */
    private function processDrug(ClassObject $classObj, array $row): void
    {
        if (!empty($row['drug_id']) && !empty($row['drug_name'])) {
            $drugType = $row['drug_type'] ?? 'default';

            $associatedDrug = new AssociatedDrug(
                $row['drug_name'],
                $row['strength'],
                $row['dose'],
                $row['drug_id']
            );

            $classObj->addAssociatedDrug($drugType, $associatedDrug);
        }
    }

    // ========== OTHER METHODS ==========

    /**
     * SQL Query: Same complex JOIN but without WHERE clause
     * Time Complexity: O(n) where n = total records across all tables
     */
    public function getAllMedications(): array
    {
        $query = "
        SELECT 
            m.id as medication_id,
            mc.id as class_id,
            co.id as object_id,
            co.object_name as class_name,
            ad.id as drug_id,
            ad.drug_type,
            ad.name as drug_name
        FROM medications m
        INNER JOIN medication_classes mc ON m.id = mc.medication_id
        INNER JOIN class_objects co ON mc.id = co.medication_class_id
        LEFT JOIN associated_drugs ad ON co.id = ad.class_object_id
        ORDER BY m.created_at DESC, mc.id, co.id, ad.id
    ";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $allResults = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $this->buildMultipleMedicationsFromResults($allResults); // 1 QUERY!
    }

    /**
     * Time Complexity: O(t) where t = total result rows
     * Groups results by medication ID in single pass
     */
    private function buildMultipleMedicationsFromResults(array $allResults): array
    {
        $medicationsMap = [];
        $currentMedicationId = null;
        $currentResults = [];

        foreach ($allResults as $row) {
            $medicationId = $row['medication_id'];

            if ($medicationId !== $currentMedicationId && !empty($currentResults)) {
                $medicationsMap[$currentMedicationId] = $this->buildMedicationFromResults($currentResults);
                $currentResults = [];
            }

            $currentMedicationId = $medicationId;
            $currentResults[] = $row;
        }

        if (!empty($currentResults)) {
            $medicationsMap[$currentMedicationId] = $this->buildMedicationFromResults($currentResults);
        }

        return array_values($medicationsMap);
    }

    /**
     * SQL Query: SELECT with LIMIT and OFFSET
     * Time Complexity: O(k) where k = page size
     */
    public function getMedicationsPaginatedOptimized(int $page = 1, int $perPage = 10): array
    {
        $offset = ($page - 1) * $perPage;

        $query = "
        SELECT 
            m.id as medication_id,
            mc.id as class_id,
            co.id as object_id,
            co.object_name as class_name,
            ad.id as drug_id,
            ad.drug_type,
            ad.name as drug_name
        FROM medications m
        INNER JOIN medication_classes mc ON m.id = mc.medication_id
        INNER JOIN class_objects co ON mc.id = co.medication_class_id
        LEFT JOIN associated_drugs ad ON co.id = ad.class_object_id
        WHERE m.id IN (
            SELECT id FROM medications 
            ORDER BY created_at DESC 
            LIMIT ? OFFSET ?
        )
        ORDER BY m.created_at DESC, mc.id, co.id, ad.id
    ";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$perPage, $offset]);
        $allResults = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $this->buildMultipleMedicationsFromResults($allResults); // 1 QUERY!
    }

    /**
     * SQL Query: SELECT COUNT(*)
     * Time Complexity: O(1) with table statistics, O(n) for full scan without
     */
    public function countMedications(): int
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM medications");
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    /**
     * SQL Queries: 4 DELETE operations with JOINs
     * Time Complexity: O(d + o + c + m) where:
     * - d = drugs to delete, o = objects, c = classes, m = medication
     * With proper indexing: O(log n) per operation
     */
    public function deleteMedication(int $id): bool
    {
        $this->validateId($id);
        $this->pdo->beginTransaction();
        try {
            $queries = [
                "DELETE ad FROM associated_drugs ad
                 INNER JOIN class_objects co ON ad.class_object_id = co.id
                 INNER JOIN medication_classes mc ON co.medication_class_id = mc.id
                 WHERE mc.medication_id = ?",

                "DELETE co FROM class_objects co
                 INNER JOIN medication_classes mc ON co.medication_class_id = mc.id
                 WHERE mc.medication_id = ?",

                "DELETE FROM medication_classes WHERE medication_id = ?",
                "DELETE FROM medications WHERE id = ?"
            ];

            foreach ($queries as $query) {
                $stmt = $this->pdo->prepare($query);
                $stmt->execute([$id]);
            }

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw new RepositoryException("Failed to delete medication", 0, $e);
        }
    }

    // ========== VALIDATION METHODS ==========

    private function validateMedication(Medication $medication): void
    {
        if (!$medication->isValid()) {
            throw new InvalidArgumentException("Invalid medication data");
        }
    }

    private function validateId($id): void
    {
        if (!is_int($id) || $id <= 0) {
            throw new InvalidArgumentException("ID must be a positive integer");
        }
    }
}

class RepositoryException extends Exception
{
}

