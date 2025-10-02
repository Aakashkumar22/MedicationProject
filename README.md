# Medications App

A PHP and MySQL application that stores and retrieves medication data in a normalized database structure, producing exact JSON output as specified.

## 🗄️ Database Schema UML Representation

```
                                ┌──────────────────┐        ┌─────────────────────┐        ┌──────────────────┐
                                │   medications    │        │ medication_classes  │        │   class_objects  │
                                ├──────────────────┤        ├─────────────────────┤        ├──────────────────┤
                                │ □ id (PK)        │1     * │ □ id (PK)           │1     * │ □ id (PK)        │
                                │ □ created_at     │━━━━━━━▶│ □ medication_id (FK)│━━━━━━━▶│ □ class_id (FK)  │
                                │ □ updated_at     │        │                     │        │ □ object_name    │
                                └──────────────────┘        └─────────────────────┘        └──────────────────┘
                                         │                          │                              │
                                         │ COMPOSITION              │ COMPOSITION                  │ COMPOSITION
                                         │ (CASCADE DELETE)         │ (CASCADE DELETE)             │ (CASCADE DELETE)
                                         ▽                          ▽                              ▽
                                    Parent Table                Child Table                    Child Table
                                                                                                │
                                                                                                │ 1
                                                                                                │
                                                                                              ▽
                                                                                  ┌────────────────────────┐
                                                                                  │   associated_drugs     │
                                                                                  ├────────────────────────┤
                                                                                  │ □ id (PK)              │
                                                                                  │ □ class_object_id (FK) │
                                                                                  │ □ drug_type            │
                                                                                  │ □ name                 │
                                                                                  │ □ dose                 │
                                                                                  │ □ strength             │
                                                                                  └────────────────────────┘
```

## 📸 Output Verification

### OUTPUT
<img width="1861" height="940" alt="img_6" src="https://github.com/user-attachments/assets/6e9d3aaa-5ddc-477f-b8e6-76999631cbd3" />
<img width="1363" height="943" alt="img_4" src="https://github.com/user-attachments/assets/4ecb4a3f-450d-496f-8e9b-843c685bd5fd" />
<img width="1491" height="933" alt="img_1" src="https://github.com/user-attachments/assets/5dfcf5ac-070e-4961-a2da-6a98c7c1df24" />





## 🚀 Features

- **Dynamic Form Creation** - Create medications with any structure
- **Exact JSON Generation** - Produce specific JSON structures
- **MySQL Database** - Normalized schema with proper relationships
- **Optimized Repository** - Single-query data fetching
- **PHP OOP Design** - Clean, maintainable code structure
- **RESTful Output** - Proper JSON API responses

## 📋 Prerequisites

- XAMPP (Apache + MySQL + PHP)
- Web Browser
- Git (optional)

## 🛠 Installation & Setup

### 1. Clone Repository
```bash
git clone https://github.com/Aakashkumar22/MedicationProject.git
cd MedicationProject
```

### 2. Start XAMPP Services
- Open XAMPP Control Panel
- Start **Apache** and **MySQL**

### 3. Access Application
```
http://localhost/MedicationProject/
```

## 🗄 Database Schema

### Tables Structure
1. **medications** - Main medication records with timestamps
2. **medication_classes** - Medication classification levels  
3. **class_objects** - Class names (className, className2, etc.)
4. **associated_drugs** - Drug details with types and properties

### Relationships
- `medications` 1:N `medication_classes`
- `medication_classes` 1:N `class_objects`  
- `class_objects` 1:N `associated_drugs`

## 🎯 Core Components

### PHP Classes
- **`Medication`** - Main container class
- **`MedicationClass`** - Handles medication classes
- **`ClassObject`** - Manages class objects and drugs
- **`AssociatedDrug`** - Individual drug entries
- **`MedicationRepository`** - Basic database operations
- **`OptimizedMedicationRepository`** - Enhanced with optimized queries

### Key Files
- **`MedicationOutput.php`** - Core entity classes
- **`OptimizedMedicationRepository.php`** - Database operations
- **`exact-structure-form.php`** - Hardcoded form for specific JSON
- **`dynamic-form.php`** - Fully dynamic form creation
- **`test.php`** - Comprehensive testing script

## 🎮 Usage

### 1. Dynamic Form Creation
Access: `http://localhost/MedicationAssignment/UniversalMedicationBuilder.php
- Create medications with any structure
- Add/remove classes and drugs dynamically
- Real-time JSON preview

### 2. Exact Structure Generation  
Access: `http://localhost/MedicationAssignment/MedicationOutput.php
- Generate specific JSON structure
- Pre-filled with required format
- Perfect for testing and validation

### 3. Programmatic Usage
```php
require_once 'MedicationOutput.php';
require_once 'MedicationRepository.php';

// Create medication dynamically
$medication = new Medication();
$medClass = new MedicationClass();

$classObj = new ClassObject();
$classObj->addAssociatedDrug("primary", new AssociatedDrug("Ibuprofen", "400mg", "1 tablet"));

$medClass->addClassName("PainManagement", [$classObj]);
$medication->addMedicationClass($medClass);

// Save to database
$pdo = new PDO("mysql:host=localhost;dbname=MedicationsData", "root", "");
$repository = new OptimizedMedicationRepository($pdo);
$medicationId = $repository->saveMedication($medication);

// Retrieve and display JSON
$retrieved = $repository->getMedicationByIdOptimized($medicationId);
echo $retrieved->toJson();
```

## 📊 Expected JSON Output

```json
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
}
```

## ⚡ Performance Features

- **Single Query Fetching** - Replaces N+1 queries with optimized JOINs
- **Eager Loading** - Loads all related data in one database call
- **Pagination Support** - `getMedicationsPaginated()` method
- **Search Functionality** - Find medications by drug name
- **Memory Efficient** - Processes data in batches

## 🔧 Technical Implementation

### Database Optimization
```sql
-- Single optimized query for data fetching
SELECT 
    m.id as medication_id,
    mc.id as class_id,
    co.id as object_id,
    co.object_name as class_name,
    ad.drug_type,
    ad.name as drug_name,
    ad.strength,
    ad.dose
FROM medications m
LEFT JOIN medication_classes mc ON m.id = mc.medication_id
LEFT JOIN class_objects co ON mc.id = co.medication_class_id
LEFT JOIN associated_drugs ad ON co.id = ad.class_object_id
WHERE m.id = ?
```

### Dynamic vs Hardcoded Approaches

| Approach | Use Case | Flexibility |
|----------|----------|-------------|
| **Dynamic Form** | User-generated structures | High - any structure |
| **Exact Structure** | Testing/validation | Low - fixed format |
| **Programmatic** | API integration | Medium - code controlled |

## ✅ Verification Process

1. **Database Setup** - Verify table creation and relationships
2. **Data Insertion** - Confirm proper data persistence
3. **JSON Output** - Validate exact structure match
4. **Performance** - Check optimized query execution

## 🐛 Troubleshooting

### Common Issues

1. **Database Connection Failed**
   ```bash
   # Check MySQL service
   sudo service mysql start
   # Or via XAMPP control panel
   ```

2. **File Not Found**
   ```bash
   # Ensure files are in correct directory
   C:\xampp\htdocs\MedicationProject\
   ```

3. **JSON Output Mismatch**
   - Verify database data matches expected structure
   - Check `class_objects.object_name` values
   - Confirm `associated_drugs.drug_type` values

### Error Handling
- Comprehensive try-catch blocks
- User-friendly error messages
- Database transaction rollback on failure

## 📁 Complete File Structure

```
MedicationProject/
├── src/
│   ├── MedicationAssignment.php
│   ├── MedicationClass.php
│   ├── Medications.php
│   ├── Output/
│   │   └── MedicationOutput.php
│   ├── Repository/
│   │   └── MedicationRepository.php
│   └── Builders/
│       └── UniversalMedicationBuilder.php
├── scripts/
│   ├── FetchById.php
│   ├── makeSameTypeDrug.php
│   ├── TestOp.php
│   └── cli-dynamic-fetch.php
├── models/
│   ├── AssociatedDrug.php
│   └── ClassObject.php
├── forms/
│   ├── exact-structure-form.php
│   └── dynamic-form.php
├── tests/
│   └── test.php
├── utilities/
│   └── fetch-medications.php
├── External Libraries/
├── Scratches and Consoles/


```

## 🎨 Frontend Features

- **Responsive Design** - Works on all devices
- **Real-time Updates** - Dynamic form field management
- **Visual Feedback** - Success/error messages
- **JSON Preview** - Live structure visualization
- **User-friendly Interface** - Intuitive form controls

## 🔄 Data Flow

```
User Input/Form Data / Another DBValue
     ↓
Medication Object Creation
     ↓ 
Database Persistence (MySQL)
     ↓
Optimized Data Retrieval
     ↓
Exact JSON Output Generation
     ↓
Validation & Verification
```

## 📝 License

This project is open source and available under the [MIT License](LICENSE).

## 👥 Author

**Aakash Kumar**  
- GitHub: [Aakashkumar22](https://github.com/Aakashkumar22)
- Project: [MedicationProject](https://github.com/Aakashkumar22/MedicationProject)

## 🔗 Quick Links

- **Live Demo**:http://localhost/MedicationAssignment/
- **FetchByID**:http://localhost/MedicationAssignment/FetchById.php?id=
- **ExactMatchOutput**:http://localhost/MedicationAssignment/MedicationOutput.php
- **Testing**: `http://localhost/MedicationAssignment/TestOp.php
- 

---

**Built with modern PHP practices, proper database design, and optimized for performance** 🚀
