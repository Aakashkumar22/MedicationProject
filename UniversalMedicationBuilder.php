<?php

require_once 'Medications.php';
require_once 'MedicationClass.php';
require_once 'ClassObject.php';
require_once 'AssociatedDrug.php';
require_once 'MedicationRepository.php';

// Handle form submission
if ($_POST) {
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=MedicationsData", "root", "");
        $repository = new MedicationRepository($pdo);

        // Create Medication object from simple form data
        $medication = new Medication();
        $medClass = new MedicationClass();

        // Get dynamic input from form
        $className = isset($_POST['class_name']) ? $_POST['class_name'] : 'DefaultClass';

        $classObj = new ClassObject();

        // Add drugs from form inputs
        if (!empty($_POST['drug_name'])) {
            $classObj->addAssociatedDrug(
                isset($_POST['drug_type']) ? $_POST['drug_type'] : 'primary',
                new AssociatedDrug(
                    $_POST['drug_name'],
                    isset($_POST['drug_strength']) ? $_POST['drug_strength'] : '',
                    isset($_POST['drug_dose']) ? $_POST['drug_dose'] : ''
                )
            );
        }

        // Add multiple drugs if provided
        if (isset($_POST['additional_drugs']) && is_array($_POST['additional_drugs'])) {
            foreach ($_POST['additional_drugs'] as $additionalDrug) {
                if (!empty($additionalDrug['name'])) {
                    $classObj->addAssociatedDrug(
                        isset($additionalDrug['type']) ? $additionalDrug['type'] : 'secondary',
                        new AssociatedDrug(
                            $additionalDrug['name'],
                            isset($additionalDrug['strength']) ? $additionalDrug['strength'] : '',
                            isset($additionalDrug['dose']) ? $additionalDrug['dose'] : ''
                        )
                    );
                }
            }
        }

        $medClass->addClassName($className, [$classObj]);
        $medication->addMedicationClass($medClass);

        // Save to database
        $medicationId = $repository->saveMedication($medication);
        //Retrieve from database
        $retrievedMedication = $repository->getMedicationById($medicationId);
         echo "✓ Medication retrieved from database\n";
        echo "=== JSON Output (From Database) ===\n";
         echo $retrievedMedication->toJson();

        echo "<h3>✅ Medication Saved with ID: $medicationId</h3>";
        echo "<pre>" . $medication->toJson() . "</pre>";


    } catch (Exception $e) {
        echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Simple Medication Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        .form-group {
            margin: 10px 0;
        }

        input, select {
            padding: 8px;
            margin: 5px;
            width: 200px;
        }

        button {
            padding: 10px 20px;
            background: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
<h2>Simple Medication Form</h2>
<form method="POST">
    <div class="form-group">
        <label>Class Name:</label>
        <input type="text" name="class_name" placeholder="e.g., PainManagement, Antibiotics" value="CustomClass">
    </div>

    <div class="form-group">
        <h3>Primary Drug:</h3>
        <input type="text" name="drug_type" placeholder="Drug Type" value="primary">
        <input type="text" name="drug_name" placeholder="Drug Name" value="CustomDrug" required>
        <input type="text" name="drug_strength" placeholder="Strength" value="500mg">
        <input type="text" name="drug_dose" placeholder="Dose" value="1 tablet">
    </div>

    <div id="additional-drugs">
        <h3>Additional Drugs:</h3>
        <!-- Additional drugs will be added here by JavaScript -->
    </div>

    <button type="button" onclick="addDrug()">Add Another Drug</button>
    <br><br>
    <button type="submit">Save Medication</button>
</form>

<script>
    let drugCounter = 0;

    function addDrug() {
        drugCounter++;
        const container = document.getElementById('additional-drugs');
        const drugHtml = `
                <div class="form-group">
                    <input type="text" name="additional_drugs[${drugCounter}][type]" placeholder="Drug Type" value="secondary">
                    <input type="text" name="additional_drugs[${drugCounter}][name]" placeholder="Drug Name" required>
                    <input type="text" name="additional_drugs[${drugCounter}][strength]" placeholder="Strength">
                    <input type="text" name="additional_drugs[${drugCounter}][dose]" placeholder="Dose">
                    <button type="button" onclick="this.parentElement.remove()">Remove</button>
                </div>
            `;
        container.insertAdjacentHTML('beforeend', drugHtml);
    }
</script>
</body>
</html>
