<?php
require_once 'Medications.php';
require_once 'MedicationClass.php';
require_once 'ClassObject.php';
require_once 'AssociatedDrug.php';
require_once 'MedicationRepository.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate_json'])) {
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=MedicationsData", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $repository = new MedicationRepository($pdo);

        // Create Medication object that matches the exact JSON structure
        $medication = new Medication();
        $medClass = new MedicationClass();

        // Process className
        if (!empty($_POST['class_name'])) {
            $classObj1 = new ClassObject();

            // Process associatedDrug for className
            if (!empty($_POST['class1_drug1_name'])) {
                $classObj1->addAssociatedDrug(
                    "associatedDrug",
                    new AssociatedDrug(
                        $_POST['class1_drug1_name'],
                        isset($_POST['class1_drug1_strength']) ? $_POST['class1_drug1_strength'] : '',
                        isset($_POST['class1_drug1_dose']) ? $_POST['class1_drug1_dose'] : ''
                    )
                );
            }

            // Process associatedDrug#2 for className
            if (!empty($_POST['class1_drug2_name'])) {
                $classObj1->addAssociatedDrug(
                    "associatedDrug#2",
                    new AssociatedDrug(
                        $_POST['class1_drug2_name'],
                        isset($_POST['class1_drug2_strength']) ? $_POST['class1_drug2_strength'] : '',
                        isset($_POST['class1_drug2_dose']) ? $_POST['class1_drug2_dose'] : ''
                    )
                );
            }

            $medClass->addClassName($_POST['class_name'], [$classObj1]);
        }

        // Process className2
        if (!empty($_POST['class_name2'])) {
            $classObj2 = new ClassObject();

            // Process associatedDrug for className2
            if (!empty($_POST['class2_drug1_name'])) {
                $classObj2->addAssociatedDrug(
                    "associatedDrug",
                    new AssociatedDrug(
                        $_POST['class2_drug1_name'],
                        isset($_POST['class2_drug1_strength']) ? $_POST['class2_drug1_strength'] : '',
                        isset($_POST['class2_drug1_dose']) ? $_POST['class2_drug1_dose'] : ''
                    )
                );
            }

            // Process associatedDrug#2 for className2
            if (!empty($_POST['class2_drug2_name'])) {
                $classObj2->addAssociatedDrug(
                    "associatedDrug#2",
                    new AssociatedDrug(
                        $_POST['class2_drug2_name'],
                        isset($_POST['class2_drug2_strength']) ? $_POST['class2_drug2_strength'] : '',
                        isset($_POST['class2_drug2_dose']) ? $_POST['class2_drug2_dose'] : ''
                    )
                );
            }

            $medClass->addClassName($_POST['class_name2'], [$classObj2]);
        }

        $medication->addMedicationClass($medClass);

        // Save to database
        $medicationId = $repository->saveMedication($medication);

        $success = true;
        $message = "✅ Medication saved to database with ID: " . $medicationId;
        $jsonOutput = $medication->toJson();

    } catch (Exception $e) {
        $success = false;
        $message = "❌ Error: " . $e->getMessage();
        $jsonOutput = '';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Exact JSON Structure Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px;
            background: #2c3e50;
            color: white;
            border-radius: 10px;
        }
        .class-group {
            margin: 20px 0;
            padding: 20px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            background: #f8f9fa;
        }
        .drug-group {
            margin: 15px 0;
            padding: 15px;
            border: 1px dashed #adb5bd;
            border-radius: 5px;
            background: white;
        }
        .form-row {
            display: flex;
            gap: 15px;
            margin-bottom: 10px;
            flex-wrap: wrap;
            align-items: center;
        }
        .form-row input {
            flex: 1;
            min-width: 200px;
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            font-size: 14px;
        }
        label {
            font-weight: bold;
            min-width: 120px;
        }
        .btn {
            background: #007bff;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin: 5px;
        }
        .btn-success { background: #28a745; }
        .json-container {
            background: #1e1e1e;
            color: #d4d4d4;
            padding: 20px;
            border-radius: 5px;
            font-family: 'Consolas', 'Monaco', monospace;
            font-size: 12px;
            line-height: 1.4;
            white-space: pre-wrap;
            margin: 20px 0;
        }
        .success { color: #28a745; background: #d4edda; padding: 15px; border-radius: 5px; }
        .error { color: #dc3545; background: #f8d7da; padding: 15px; border-radius: 5px; }
        .structure-preview {
            background: #e7f3ff;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
            border-left: 4px solid #007bff;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>💊 Exact JSON Structure Generator</h1>
        <p>Create medications with the exact structure you need</p>
    </div>

    <div class="structure-preview">
        <h3>🎯 Target JSON Structure:</h3>
        <pre style="background: #2d2d2d; color: white; padding: 15px; border-radius: 5px; font-size: 11px;">
{
    "medications": [
        {
            "medicationsClasses": [
                {
                    "className": [
                        {
                            "associatedDrug": [
                                {
                                    "dose": "",
                                    "name": "asprin",
                                    "strength": "500 mg"
                                }
                            ],
                            "associatedDrug#2": [
                                {
                                    "dose": "",
                                    "name": "somethingElse",
                                    "strength": "500 mg"
                                }
                            ]
                        }
                    ],
                    "className2": [
                        {
                            "associatedDrug": [
                                {
                                    "dose": "",
                                    "name": "asprin",
                                    "strength": "500 mg"
                                }
                            ],
                            "associatedDrug#2": [
                                {
                                    "dose": "",
                                    "name": "somethingElse",
                                    "strength": "500 mg"
                                }
                            ]
                        }
                    ]
                }
            ]
        }
    ]
}</pre>
    </div>

    <?php if (isset($success)): ?>
        <div class="<?php echo $success ? 'success' : 'error'; ?>">
            <?php echo $message; ?>
        </div>

        <?php if ($success && !empty($jsonOutput)): ?>
            <h3>Generated JSON:</h3>
            <div class="json-container"><?php echo $jsonOutput; ?></div>
        <?php endif; ?>

        <hr>
    <?php endif; ?>

    <form method="POST">
        <!-- Class 1 Section -->
        <div class="class-group">
            <h2>🏥 Class 1 (className)</h2>

            <div class="form-row">
                <label>Class Name:</label>
                <input type="text" name="class_name" value="className" required>
            </div>

            <!-- Drug 1 for Class 1 -->
            <div class="drug-group">
                <h4>💊 associatedDrug</h4>
                <div class="form-row">
                    <label>Drug Name:</label>
                    <input type="text" name="class1_drug1_name" value="asprin" required>
                </div>
                <div class="form-row">
                    <label>Strength:</label>
                    <input type="text" name="class1_drug1_strength" value="500 mg" required>
                </div>
                <div class="form-row">
                    <label>Dose:</label>
                    <input type="text" name="class1_drug1_dose" value="">
                </div>
            </div>

            <!-- Drug 2 for Class 1 -->
            <div class="drug-group">
                <h4>💊 associatedDrug#2</h4>
                <div class="form-row">
                    <label>Drug Name:</label>
                    <input type="text" name="class1_drug2_name" value="somethingElse" required>
                </div>
                <div class="form-row">
                    <label>Strength:</label>
                    <input type="text" name="class1_drug2_strength" value="500 mg" required>
                </div>
                <div class="form-row">
                    <label>Dose:</label>
                    <input type="text" name="class1_drug2_dose" value="">
                </div>
            </div>
        </div>

        <!-- Class 2 Section -->
        <div class="class-group">
            <h2>🏥 Class 2 (className2)</h2>

            <div class="form-row">
                <label>Class Name:</label>
                <input type="text" name="class_name2" value="className2" required>
            </div>

            <!-- Drug 1 for Class 2 -->
            <div class="drug-group">
                <h4>💊 associatedDrug</h4>
                <div class="form-row">
                    <label>Drug Name:</label>
                    <input type="text" name="class2_drug1_name" value="asprin" required>
                </div>
                <div class="form-row">
                    <label>Strength:</label>
                    <input type="text" name="class2_drug1_strength" value="500 mg" required>
                </div>
                <div class="form-row">
                    <label>Dose:</label>
                    <input type="text" name="class2_drug1_dose" value="">
                </div>
            </div>

            <!-- Drug 2 for Class 2 -->
            <div class="drug-group">
                <h4>💊 associatedDrug#2</h4>
                <div class="form-row">
                    <label>Drug Name:</label>
                    <input type="text" name="class2_drug2_name" value="somethingElse" required>
                </div>
                <div class="form-row">
                    <label>Strength:</label>
                    <input type="text" name="class2_drug2_strength" value="500 mg" required>
                </div>
                <div class="form-row">
                    <label>Dose:</label>
                    <input type="text" name="class2_drug2_dose" value="">
                </div>
            </div>
        </div>

        <div style="text-align: center; margin-top: 30px;">
            <button type="submit" name="generate_json" class="btn btn-success">
                🚀 Generate Exact JSON & Save to Database
            </button>
        </div>
    </form>

    <div style="margin-top: 40px; padding: 20px; background: #fff3cd; border-radius: 5px;">
        <h3>💡 How it works:</h3>
        <ul>
            <li>This form creates the <strong>exact JSON structure</strong> you specified</li>
            <li>Each class has exactly 2 drugs: <code>associatedDrug</code> and <code>associatedDrug#2</code></li>
            <li>Data is saved to database and can be retrieved later</li>
            <li>You can modify the values but the structure remains the same</li>
        </ul>
    </div>
</div>
</body>
</html>
