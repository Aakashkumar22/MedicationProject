

<?php
// Include all required files
//require_once 'MedicationOutput.php';
//require_once 'MedicationRepository.php';
//
//echo "=== Medication JSON Generator ===\n\n";
//
//// Create medication structure
//$medication = new Medication();
//
//// Create a single MedicationClass that will contain both className and className2
//$medClass = new MedicationClass();
//
//// Create class object for className
//$classObj1 = new ClassObject();
//$classObj1->addAssociatedDrug("associatedDrug", new AssociatedDrug("asprin", "500 mg", ""));
//$classObj1->addAssociatedDrug("associatedDrug#2", new AssociatedDrug("somethingElse", "500 mg", ""));
//
//// Create class object for className2
//$classObj2 = new ClassObject();
//$classObj2->addAssociatedDrug("associatedDrug", new AssociatedDrug("asprin", "500 mg", ""));
//$classObj2->addAssociatedDrug("associatedDrug#2", new AssociatedDrug("somethingElse", "500 mg", ""));
//
//// Add both className and className2 to the same medication class
//$medClass->addClassName("className", [$classObj1]);
//$medClass->addClassName("className2", [$classObj2]);
//
//// Add the single medication class to medication
//$medication->addMedicationClass($medClass);
//
//// Output the JSON without database
//echo "=== JSON Output (Without Database) ===\n";
//echo $medication->toJson();
//
//// Optional: Test with database
//echo "\n\n=== Database Test ===\n";
//try {
//    $pdo = new PDO("mysql:host=localhost;dbname=MedicationsData", "root", "");
//    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//
//    $repository = new MedicationRepository($pdo);
//
//    // Save to database
//    $medicationId = $repository->saveMedication($medication);
//    echo "✓ Medication saved to database with ID: " . $medicationId . "\n";
//
//    // Retrieve from database
//    $retrievedMedication = $repository->getMedicationById($medicationId);
//    echo "✓ Medication retrieved from database\n";
//    echo "=== JSON Output (From Database) ===\n";
//    echo $retrievedMedication->toJson();
//
//} catch (PDOException $e) {
//    echo "⚠ Database not available: " . $e->getMessage() . "\n";
//    echo "⚠ The JSON above was generated without database.\n";
//}

// Include all required files
require_once 'MedicationOutput.php';
require_once 'MedicationRepository.php';

// Function to display pretty HTML sections
function prettyPrint($title, $content, $type = 'info') {
    $icons = [
        'info' => '📄',
        'success' => '✅',
        'error' => '❌',
        'warning' => '⚠️'
    ];

    $colors = [
        'info' => '#3498db',
        'success' => '#27ae60',
        'error' => '#e74c3c',
        'warning' => '#f39c12'
    ];

    echo "<div style='border: 2px solid {$colors[$type]}; border-radius: 10px; margin: 20px 0; overflow: hidden;'>";
    echo "<div style='background: {$colors[$type]}; color: white; padding: 15px; font-weight: bold; font-size: 18px;'>";
    echo "{$icons[$type]} {$title}";
    echo "</div>";
    echo "<div style='padding: 20px; background: #f8f9fa; font-family: monospace; white-space: pre-wrap;'>";
    echo htmlspecialchars($content);
    echo "</div>";
    echo "</div>";
}

// Function for status messages
function showStatus($message, $type = 'info') {
    $icons = [
        'info' => 'ℹ️',
        'success' => '✅',
        'error' => '❌',
        'warning' => '⚠️'
    ];

    $colors = [
        'info' => '#3498db',
        'success' => '#27ae60',
        'error' => '#e74c3c',
        'warning' => '#f39c12'
    ];

    echo "<div style='background: {$colors[$type]}; color: white; padding: 12px; border-radius: 5px; margin: 10px 0; font-weight: bold;'>";
    echo "{$icons[$type]} {$message}";
    echo "</div>";
}

// Start HTML output
echo "<!DOCTYPE html>
<html>
<head>
    <title>Medication JSON Generator</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 0; 
            padding: 20px; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px;
            background: #2c3e50;
            color: white;
            border-radius: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 2.5em;
        }
        .json-container {
            background: #1e1e1e;
            color: #d4d4d4;
            padding: 20px;
            border-radius: 5px;
            overflow-x: auto;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            line-height: 1.4;
        }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h1>💊 Medication JSON Generator</h1>
            <p>Test Results - " . date('Y-m-d H:i:s') . "</p>
        </div>";
?>

<?php
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
prettyPrint("JSON Output (Without Database)", $medication->toJson(), 'info');

// Optional: Test with database
showStatus("Database Test", 'info');

try {
    $pdo = new PDO("mysql:host=localhost;dbname=MedicationsData", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $repository = new MedicationRepository($pdo);

    // Save to database
    $medicationId = $repository->saveMedication($medication);
    showStatus("Medication saved to database with ID: " . $medicationId, 'success');

    // Retrieve from database
    $retrievedMedication = $repository->getMedicationById($medicationId);
    showStatus("Medication retrieved from database", 'success');

    prettyPrint("JSON Output (From Database)", $retrievedMedication->toJson(), 'success');

    // Show database statistics
    showStatus("Database Statistics", 'info');
    echo "<div style='background: #ecf0f1; padding: 15px; border-radius: 5px; margin: 10px 0;'>";

    $tables = ['medications', 'medication_classes', 'class_objects', 'associated_drugs'];
    foreach ($tables as $table) {
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM $table");
        $stmt->execute();
        $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        echo "<div style='margin: 5px 0; padding: 8px; background: white; border-radius: 3px;'>
                <strong>{$table}:</strong> {$count} records
              </div>";
    }
    echo "</div>";

} catch (PDOException $e) {
    showStatus("Database not available: " . $e->getMessage(), 'error');
    showStatus("The JSON above was generated without database", 'warning');
}

// Close HTML
echo "</div></body></html>";
?>