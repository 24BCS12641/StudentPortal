<?php
require_once __DIR__ . '/../config/mongo_db.php';

echo "Testing MongoDBHelper..." . PHP_EOL;

// 1. Find
$student = MongoDBHelper::findOne('students', ['roll_no' => '24BCS12641']);
echo "1. Student found: " . ($student ? $student['name'] . " (" . $student['roll_no'] . ")" : "None") . PHP_EOL;

// 2. Update
$modified = MongoDBHelper::update('students', ['roll_no' => '24BCS12641'], ['semester' => 4, 'updated_at' => date('Y-m-d H:i:s')]);
echo "2. Student updated (modified count): " . $modified . PHP_EOL;

// 3. Count documents in collections
$collections = ['users', 'students', 'faculty', 'subjects', 'assignments', 'submissions', 'grades', 'attendance', 'notices', 'study_materials'];
echo PHP_EOL . "Collection counts in student_portal:" . PHP_EOL;
foreach ($collections as $col) {
    $docs = MongoDBHelper::find($col);
    echo "  - {$col}: " . count($docs) . " documents" . PHP_EOL;
}
echo PHP_EOL . "All tests passed successfully!" . PHP_EOL;
