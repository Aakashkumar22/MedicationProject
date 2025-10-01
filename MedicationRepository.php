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
        $medication = new Medication($id);

        // Get medication classes
        $stmt = $this->pdo->prepare("SELECT * FROM medication_classes WHERE medication_id = ?");
        $stmt->execute([$id]);
        $classes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($classes as $class) {
            $medClass = new MedicationClass($class['id']);

            // Get class objects grouped by className (object_name)
            $stmt = $this->pdo->prepare("
                SELECT object_name, GROUP_CONCAT(id) as object_ids 
                FROM class_objects 
                WHERE medication_class_id = ? 
                GROUP BY object_name
            ");
            $stmt->execute([$class['id']]);
            $objectGroups = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($objectGroups as $group) {
                $className = $group['object_name'];
                $objectIds = explode(',', $group['object_ids']);
                $classObjects = [];

                foreach ($objectIds as $objectId) {
                    $classObj = new ClassObject($objectId);

                    // Get associated drugs for this class object
                    $stmt = $this->pdo->prepare("SELECT * FROM associated_drugs WHERE class_object_id = ?");
                    $stmt->execute([$objectId]);
                    $drugs = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($drugs as $drug) {
                        $associatedDrug = new AssociatedDrug(
                            $drug['name'],
                            $drug['strength'],
                            $drug['dose'],
                            $drug['id']
                        );
                        $classObj->addAssociatedDrug($drug['drug_type'], $associatedDrug);
                    }

                    $classObjects[] = $classObj;
                }

                $medClass->addClassName($className, $classObjects);
            }

            $medication->addMedicationClass($medClass);
        }

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

