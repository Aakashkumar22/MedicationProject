

<?php
// Include all required files
// Add this line
require_once 'AssociatedDrug.php';
require_once 'ClassObject.php';
require_once 'MedicationClass.php';
require_once 'Medications.php';
require_once 'MedicationRepository.php';

echo "=== Medication JSON Generator ===\n\n";

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


// Output the JSON without database
echo "=== JSON Output (Without Database) ===\n";
echo "<pre>" . $medication->toJson() . "</pre>";


// Optional: Test with database
echo "\n\n=== Database Test ===\n";
try {
    $pdo = new PDO("mysql:host=localhost;dbname=MedicationsData", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $repository = new MedicationRepository($pdo);

    // Save to database
    $medicationId = $repository->saveMedication($medication);
    echo "✓ Medication saved to database with ID: " . $medicationId . "\n";

    // Retrieve from database
    $retrievedMedication = $repository->getMedicationById($medicationId);
    echo "✓ Medication retrieved from database\n";
    echo "=== JSON Output (From Database) ===\n";
    echo "<pre>" . $retrievedMedication->toJson() . "</pre>";


} catch (PDOException $e) {
    echo "⚠ Database not available: " . $e->getMessage() . "\n";
    echo "⚠ The JSON above was generated without database.\n";
}
?>
