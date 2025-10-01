<?php

require_once 'Medications.php';
require_once 'MedicationClass.php';
require_once 'ClassObject.php';
require_once 'AssociatedDrug.php';
require_once 'MedicationRepository.php';

// Get ID from URL parameter dynamically
$requestedId = isset($_GET['id']) ? (int)$_GET['id'] : null;

try {
    $pdo = new PDO("mysql:host=localhost;dbname=MedicationsData", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $repository = new MedicationRepository($pdo);

    if ($requestedId) {
        // Fetch specific ID from URL
        $retrievedMedication = $repository->getMedicationById($requestedId);

        if ($retrievedMedication) {
            echo "<h2>✅ Medication ID $requestedId</h2>";
            echo "<pre>" . $retrievedMedication->toJson() . "</pre>";
        } else {
            echo "<h2>❌ Medication ID $requestedId not found</h2>";
        }
    } else {
        // Show all available IDs as links
        $stmt = $pdo->query("SELECT id FROM medications ORDER BY id");
        $allIds = $stmt->fetchAll(PDO::FETCH_COLUMN);

        echo "<h2>📋 Available Medications</h2>";
        echo "<p>Click any ID to fetch:</p>";
        foreach ($allIds as $id) {
            echo "<a href='?id=$id' style='display: inline-block; padding: 10px; margin: 5px; background: #007bff; color: white; text-decoration: none;'>ID: $id</a> ";
        }
    }

} catch (PDOException $e) {
    echo "⚠ Database not available: " . $e->getMessage();
}

