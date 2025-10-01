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


class MedicationRepository
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Save medication structure to database
    public function saveMedication(Medication $medication)
    {
        try {
            $this->pdo->beginTransaction();

            // Insert into medications table
            $stmt = $this->pdo->prepare("INSERT INTO medications () VALUES ()");
            $stmt->execute();
            $medicationId = $this->pdo->lastInsertId();

            // Save medication classes
            foreach ($medication->getMedicationsClasses() as $medClass) {
                // Insert into medication_classes (we don't need class_name anymore for the structure)
                $stmt = $this->pdo->prepare("INSERT INTO medication_classes (medication_id) VALUES (?)");
                $stmt->execute([$medicationId]);
                $classId = $this->pdo->lastInsertId();

                // Save class names and their objects
                foreach ($medClass->getClassNames() as $className => $classObjects) {
                    foreach ($classObjects as $classObj) {
                        // Insert into class_objects (store className as object_name)
                        $stmt = $this->pdo->prepare("INSERT INTO class_objects (medication_class_id, object_name) VALUES (?, ?)");
                        $stmt->execute([$classId, $className]);
                        $objectId = $this->pdo->lastInsertId();

                        // Save associated drugs
                        foreach ($classObj->getAssociatedDrugs() as $drugType => $drugs) {
                            foreach ($drugs as $drug) {
                                $stmt = $this->pdo->prepare("INSERT INTO associated_drugs (class_object_id, drug_type, dose, name, strength) VALUES (?, ?, ?, ?, ?)");
                                $stmt->execute([
                                    $objectId,
                                    $drugType,
                                    $drug->getDose(),
                                    $drug->getName(),
                                    $drug->getStrength()
                                ]);
                            }
                        }
                    }
                }
            }

            $this->pdo->commit();
            return $medicationId;

        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    // Load medication structure from database
    public function getMedicationById($id)
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
        LEFT JOIN medication_classes mc ON m.id = mc.medication_id
        LEFT JOIN class_objects co ON mc.id = co.medication_class_id
        LEFT JOIN associated_drugs ad ON co.id = ad.class_object_id
        WHERE m.id = ?
        ORDER BY mc.id, co.id, ad.drug_type
    ";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$id]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($results)) {
            return null;
        }

        return $this->buildMedicationFromResults($results);
    }

    private function buildMedicationFromResults($results)
    {
        $medication = new Medication($results[0]['medication_id']);
        $medClass = new MedicationClass();

        $classObjects = [];

        foreach ($results as $row) {
            $className = $row['class_name'];

            // Skip if no class name (shouldn't happen in valid data)
            if (empty($className)) {
                continue;
            }

            // Initialize class object if not exists
            if (!isset($classObjects[$className])) {
                $classObjects[$className] = new ClassObject($row['object_id']);
            }

            // Add drug to class object if drug data exists
            if (!empty($row['drug_type']) && !empty($row['drug_name'])) {
                $classObjects[$className]->addAssociatedDrug(
                    $row['drug_type'],
                    new AssociatedDrug(
                        $row['drug_name'],
                        $row['strength'],
                        $row['dose'],
                        $row['drug_id']
                    )
                );
            }
        }

        // Add all class objects to medication class
        foreach ($classObjects as $className => $classObj) {
            $medClass->addClassName($className, [$classObj]);
        }

        $medication->addMedicationClass($medClass);
        return $medication;
    }

    // Get all medications
    public function getAllMedications()
    {
        $stmt = $this->pdo->prepare("SELECT id FROM medications ORDER BY created_at DESC");
        $stmt->execute();
        $medicationIds = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $medications = [];
        foreach ($medicationIds as $id) {
            $medications[] = $this->getMedicationById($id);
        }

        return $medications;
    }

    // Delete medication
    public function deleteMedication($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM medications WHERE id = ?");
        return $stmt->execute([$id]);
    }
}

