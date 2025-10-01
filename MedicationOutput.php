<?php


require_once 'Medications.php';
require_once 'MedicationClass.php';
require_once 'ClassObject.php';
require_once 'AssociatedDrug.php';
require_once 'MedicationRepository.php';
// Create the exact structure from your JSON
//$medication = new Medication();
//
//// First medication class
//$medClass1 = new MedicationClass("className");
//$classObj1 = new ClassObject("className");
//$classObj1->addAssociatedDrug("associatedDrug", new AssociatedDrug("asprin", "500 mg", ""));
//$classObj1->addAssociatedDrug("associatedDrug#2", new AssociatedDrug("somethingElse", "500 mg", ""));
//$medClass1->addClassObject($classObj1);
//
//// Second medication class
//$medClass2 = new MedicationClass("className2");
//$classObj2 = new ClassObject("className2");
//$classObj2->addAssociatedDrug("associatedDrug", new AssociatedDrug("asprin", "500 mg", ""));
//$classObj2->addAssociatedDrug("associatedDrug#2", new AssociatedDrug("somethingElse", "500 mg", ""));
//$medClass2->addClassObject($classObj2);
//
//$medication->addMedicationClass($medClass1);
//$medication->addMedicationClass($medClass2);
//
//// Convert to JSON (this will output the exact JSON structure you provided)
//echo $medication->toJson();
//
//// To save to database and retrieve
//try {
//    $pdo = new PDO("mysql:host=localhost;dbname=MedicationsData", "root", "");
//    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//
//    $repo = new MedicationRepository($pdo);
//    $medicationId = $repo->saveMedication($medication);
//
//    // Retrieve and output the same JSON
//    $retrievedMedication = $repo->getMedicationById($medicationId);
//    echo $retrievedMedication->toJson();
//
//} catch (PDOException $e) {
//    echo "Database error: " . $e->getMessage();
//}

// Create medication structure
$medication = new Medication();

// Create a single MedicationClass that will contain both className and className2
$medClass = new MedicationClass();

// Create class object for className
$classObj1 = new ClassObject();
$classObj1->addAssociatedDrug("associatedDrug", new AssociatedDrug("asprin", "500 mg", ""));
$classObj1->addAssociatedDrug("associatedDrug#2", new AssociatedDrug("somethingElse", "500 mg", ""));

// Create class object for className2
$classObj2 = new ClassObject();
$classObj2->addAssociatedDrug("associatedDrug", new AssociatedDrug("asprin", "500 mg", ""));
$classObj2->addAssociatedDrug("associatedDrug#2", new AssociatedDrug("somethingElse", "500 mg", ""));

// Add both className and className2 to the same medication class
$medClass->addClassName("className", [$classObj1]);
$medClass->addClassName("className2", [$classObj2]);

// Add the single medication class to medication
$medication->addMedicationClass($medClass);

// Simple pretty print - just wrap in a pre tag
echo "<pre style='background: #f4f4f4; padding: 15px; border-radius: 5px;'>";

// Output the corrected JSON
echo $medication->toJson();


