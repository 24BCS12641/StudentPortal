<?php
/**
 * MongoDB Seed & Migration Script for Student Portal
 * Automatically populates the 'student_portal' database in MongoDB with 30+ records.
 */

$uri = "mongodb://127.0.0.1:27017";
$dbName = "student_portal";

echo "Connecting to MongoDB at {$uri}..." . PHP_EOL;

try {
    $manager = new MongoDB\Driver\Manager($uri);
    
    // Ping MongoDB
    $pingCommand = new MongoDB\Driver\Command(['ping' => 1]);
    $manager->executeCommand('admin', $pingCommand);
    echo "✓ Connected to MongoDB successfully!" . PHP_EOL;

    function insertCollection($manager, $dbName, $collectionName, $documents) {
        try {
            $dropCommand = new MongoDB\Driver\Command(['drop' => $collectionName]);
            $manager->executeCommand($dbName, $dropCommand);
        } catch (Exception $e) {}

        $bulk = new MongoDB\Driver\BulkWrite();
        foreach ($documents as $doc) {
            $bulk->insert($doc);
        }
        $result = $manager->executeBulkWrite("{$dbName}.{$collectionName}", $bulk);
        echo "✓ [{$collectionName}] Inserted " . $result->getInsertedCount() . " documents." . PHP_EOL;
    }

    $data = json_decode(file_get_contents(__DIR__ . '/../scratch/portal_dataset.json'), true);

    insertCollection($manager, $dbName, 'departments', array (
  0 => 
  array (
    'departmentId' => 1,
    'name' => 'School of Computer Science & Engineering',
    'code' => 'CSE',
    'description' => 'Pioneering cutting-edge computer systems, software engineering, AI, and cybersecurity.',
    'head_of_dept' => 'Dr. Rajesh Sharma',
    'icon' => 'fa-laptop-code',
  ),
  1 => 
  array (
    'departmentId' => 2,
    'name' => 'Department of Business Administration & Management',
    'code' => 'BBA',
    'description' => 'Fostering future corporate leaders, entrepreneurs, and global management thinkers.',
    'head_of_dept' => 'Dr. Sunita Deshmukh',
    'icon' => 'fa-briefcase',
  ),
  2 => 
  array (
    'departmentId' => 3,
    'name' => 'School of Information Technology & Data Science',
    'code' => 'IT',
    'description' => 'Specializing in cloud computing, big data analytics, and internet systems.',
    'head_of_dept' => 'Prof. Ananya Verma',
    'icon' => 'fa-database',
  ),
  3 => 
  array (
    'departmentId' => 4,
    'name' => 'Department of Mechanical & Robotics Engineering',
    'code' => 'MECH',
    'description' => 'Advanced thermodynamics, robotics, CAD/CAM, and automated manufacturing.',
    'head_of_dept' => 'Dr. Vikram Malhotra',
    'icon' => 'fa-gears',
  ),
  4 => 
  array (
    'departmentId' => 5,
    'name' => 'Department of Humanities, Arts & Journalism',
    'code' => 'HAJ',
    'description' => 'Cultivating creative expressions, media ethics, communication, and social studies.',
    'head_of_dept' => 'Prof. Neha Sen',
    'icon' => 'fa-feather-pointed',
  ),
  5 => 
  array (
    'departmentId' => 6,
    'name' => 'Department of Electronics & Communication Engineering',
    'code' => 'ECE',
    'description' => 'Next-generation VLSI, signal processing, embedded systems, and wireless networks.',
    'head_of_dept' => 'Dr. Arvind Swaminathan',
    'icon' => 'fa-microchip',
  ),
  6 => 
  array (
    'departmentId' => 7,
    'name' => 'Department of Electrical & Electronics Engineering',
    'code' => 'EEE',
    'description' => 'Power grids, renewable energy, smart systems, and high-voltage transmission.',
    'head_of_dept' => 'Dr. Meenakshi Sundaram',
    'icon' => 'fa-bolt',
  ),
  7 => 
  array (
    'departmentId' => 8,
    'name' => 'Department of Civil & Structural Engineering',
    'code' => 'CIVIL',
    'description' => 'Smart infrastructure, sustainable construction, bridges, and environmental geotechnics.',
    'head_of_dept' => 'Dr. Harish Chandra',
    'icon' => 'fa-bridge',
  ),
  8 => 
  array (
    'departmentId' => 9,
    'name' => 'Department of Artificial Intelligence & Machine Learning',
    'code' => 'AIDS',
    'description' => 'Deep learning architectures, neural networks, natural language processing, and computer vision.',
    'head_of_dept' => 'Dr. Siddharth Nair',
    'icon' => 'fa-brain',
  ),
  9 => 
  array (
    'departmentId' => 10,
    'name' => 'Department of Cybersecurity & Digital Forensics',
    'code' => 'CYBER',
    'description' => 'Information assurance, ethical hacking, malware analysis, cryptography, and network defense.',
    'head_of_dept' => 'Dr. Kavita Raman',
    'icon' => 'fa-shield-halved',
  ),
  10 => 
  array (
    'departmentId' => 11,
    'name' => 'Department of Aerospace & Aeronautical Engineering',
    'code' => 'AERO',
    'description' => 'Aerodynamics, propulsion systems, satellite launch vehicles, and avionics technology.',
    'head_of_dept' => 'Dr. K. Radhakrishnan',
    'icon' => 'fa-plane-up',
  ),
  11 => 
  array (
    'departmentId' => 12,
    'name' => 'Department of Biotechnology & Genetic Engineering',
    'code' => 'BIOTECH',
    'description' => 'Molecular biology, genomic editing, biopharmaceutical manufacturing, and bioinformatics.',
    'head_of_dept' => 'Dr. Shalini Mukherjee',
    'icon' => 'fa-dna',
  ),
  12 => 
  array (
    'departmentId' => 13,
    'name' => 'Department of Chemical & Petroleum Engineering',
    'code' => 'CHE',
    'description' => 'Petrochemical processes, polymers, industrial synthesis, and green chemistry systems.',
    'head_of_dept' => 'Dr. Pradeep Joshi',
    'icon' => 'fa-flask-vial',
  ),
  13 => 
  array (
    'departmentId' => 14,
    'name' => 'Department of Commerce, Banking & Fintech',
    'code' => 'COMMERCE',
    'description' => 'Corporate finance, algorithmic trading, global banking regulations, and auditing standards.',
    'head_of_dept' => 'Prof. Rakesh Jhunjhunwala',
    'icon' => 'fa-coins',
  ),
  14 => 
  array (
    'departmentId' => 15,
    'name' => 'Department of Economics & Global Public Policy',
    'code' => 'ECON',
    'description' => 'Macroeconomic modeling, development economics, monetary policy, and econometrics.',
    'head_of_dept' => 'Dr. Amartya Banerjee',
    'icon' => 'fa-chart-line',
  ),
  15 => 
  array (
    'departmentId' => 16,
    'name' => 'School of Legal Studies & Constitutional Jurisprudence',
    'code' => 'LAW',
    'description' => 'Corporate law, cyber jurisprudence, international arbitration, and human rights advocacy.',
    'head_of_dept' => 'Prof. Fali Nariman',
    'icon' => 'fa-scale-balanced',
  ),
  16 => 
  array (
    'departmentId' => 17,
    'name' => 'School of Pharmaceutical Sciences & Drug Discovery',
    'code' => 'PHARM',
    'description' => 'Pharmacology, clinical trials, novel drug delivery formulations, and medicinal chemistry.',
    'head_of_dept' => 'Dr. Swati Piramal',
    'icon' => 'fa-capsules',
  ),
  17 => 
  array (
    'departmentId' => 18,
    'name' => 'School of Architecture, Urban Design & Planning',
    'code' => 'ARCH',
    'description' => 'Sustainable habitats, landscape architecture, BIM modeling, and urban master planning.',
    'head_of_dept' => 'Prof. B.V. Doshi',
    'icon' => 'fa-compass-drafting',
  ),
  18 => 
  array (
    'departmentId' => 19,
    'name' => 'Department of Psychology & Cognitive Neuroscience',
    'code' => 'PSYCH',
    'description' => 'Clinical behavioral therapies, neuro-imaging, organizational psychology, and counseling.',
    'head_of_dept' => 'Dr. Vandana Shiva',
    'icon' => 'fa-head-side-virus',
  ),
  19 => 
  array (
    'departmentId' => 20,
    'name' => 'Department of Mathematics & Computational Statistics',
    'code' => 'MATH',
    'description' => 'Number theory, applied probability, stochastic calculus, and mathematical modeling.',
    'head_of_dept' => 'Dr. Manjul Bhargava',
    'icon' => 'fa-square-root-variable',
  ),
  20 => 
  array (
    'departmentId' => 21,
    'name' => 'Department of Applied Physics & Quantum Nanotechnology',
    'code' => 'PHY',
    'description' => 'Quantum optics, condensed matter physics, superconducting devices, and nanostructures.',
    'head_of_dept' => 'Dr. Ashoke Sen',
    'icon' => 'fa-atom',
  ),
  21 => 
  array (
    'departmentId' => 22,
    'name' => 'Department of Chemistry & Materials Science',
    'code' => 'CHEM',
    'description' => 'Supramolecular catalysts, smart nanomaterials, crystallographic analysis, and green polymers.',
    'head_of_dept' => 'Dr. C.N.R. Rao',
    'icon' => 'fa-vial',
  ),
  22 => 
  array (
    'departmentId' => 23,
    'name' => 'School of Agricultural Sciences & Agritech Innovation',
    'code' => 'AGRI',
    'description' => 'Precision agronomy, soil health science, drone telemetry, and sustainable food security.',
    'head_of_dept' => 'Dr. M.S. Swaminathan',
    'icon' => 'fa-wheat-awn',
  ),
  23 => 
  array (
    'departmentId' => 24,
    'name' => 'Department of Environmental Sciences & Sustainability',
    'code' => 'ENV',
    'description' => 'Climate resilience, carbon sequestration, renewable hydrology, and ecological conservation.',
    'head_of_dept' => 'Dr. Sunita Narain',
    'icon' => 'fa-leaf',
  ),
  24 => 
  array (
    'departmentId' => 25,
    'name' => 'School of Media Studies, Film & Digital Broadcasting',
    'code' => 'MEDIA',
    'description' => 'Digital film production, investigative journalism, podcasting, and streaming media tech.',
    'head_of_dept' => 'Prof. Prannoy Roy',
    'icon' => 'fa-video',
  ),
  25 => 
  array (
    'departmentId' => 26,
    'name' => 'Department of Animation, VFX & Interactive Game Design',
    'code' => 'DESIGN',
    'description' => '3D asset modeling, procedural shaders, VR environments, and Unreal Engine development.',
    'head_of_dept' => 'Prof. Satyajit Sen',
    'icon' => 'fa-palette',
  ),
  26 => 
  array (
    'departmentId' => 27,
    'name' => 'Department of Hospitality, Culinary Arts & Tourism',
    'code' => 'HOSP',
    'description' => 'Luxury hotel operations, international gourmet culinary techniques, and resort management.',
    'head_of_dept' => 'Chef Sanjeev Oberoi',
    'icon' => 'fa-utensils',
  ),
  27 => 
  array (
    'departmentId' => 28,
    'name' => 'School of Nursing & Healthcare Management',
    'code' => 'NURSE',
    'description' => 'Advanced acute clinical care, epidemiology, patient safety protocols, and health informatics.',
    'head_of_dept' => 'Dr. Florence Thomas',
    'icon' => 'fa-user-nurse',
  ),
  28 => 
  array (
    'departmentId' => 29,
    'name' => 'Department of Sports Science, Kinesiology & Athletics',
    'code' => 'SPORTS',
    'description' => 'Athletic biomechanics, exercise physiology, sports rehabilitation, and peak fitness analytics.',
    'head_of_dept' => 'Coach Pullela Gopichand',
    'icon' => 'fa-person-running',
  ),
  29 => 
  array (
    'departmentId' => 30,
    'name' => 'Department of English & World Literature',
    'code' => 'ENG',
    'description' => 'Post-colonial narratives, critical literary theory, linguistics, and global dramatic arts.',
    'head_of_dept' => 'Dr. Gayatri Spivak',
    'icon' => 'fa-book-bookmark',
  ),
  30 => 
  array (
    'departmentId' => 31,
    'name' => 'School of Educational Sciences & Modern Pedagogy',
    'code' => 'EDU',
    'description' => 'Instructional design, blended learning psychology, STEM curricula, and educational leadership.',
    'head_of_dept' => 'Dr. Krishna Kumar',
    'icon' => 'fa-chalkboard',
  ),
  31 => 
  array (
    'departmentId' => 32,
    'name' => 'Department of Performing Arts, Indian Classical & World Music',
    'code' => 'MUSIC',
    'description' => 'Classical acoustic theory, ethnomusicology, audio engineering, and orchestration.',
    'head_of_dept' => 'Ustad Zakir Hussain',
    'icon' => 'fa-music',
  ),
));
    insertCollection($manager, $dbName, 'users', array (
  0 => 
  array (
    'userId' => 1,
    'name' => 'System Administrator',
    'email' => 'admin@gmail.com',
    'password' => 'admin123',
    'role' => 'admin',
    'profile_picture' => 'default.png',
    'phone' => '+91 98765 43210',
    'created_at' => 
    \DateTime::__set_state(array(
       'date' => '2026-11-19 13:34:44.156034',
       'timezone_type' => 3,
       'timezone' => 'Europe/Berlin',
    )),
  ),
  1 => 
  array (
    'userId' => 2,
    'name' => 'Dr. Rajesh Sharma',
    'email' => 'faculty@gmail.com',
    'password' => 'faculty123',
    'role' => 'faculty',
    'profile_picture' => 'default.png',
    'phone' => '+91 98111 22334',
    'created_at' => 
    \DateTime::__set_state(array(
       'date' => '2026-11-19 13:34:44.156045',
       'timezone_type' => 3,
       'timezone' => 'Europe/Berlin',
    )),
  ),
  2 => 
  array (
    'userId' => 3,
    'name' => 'Prof. Ananya Verma',
    'email' => 'ananya.verma@college.edu',
    'password' => 'faculty123',
    'role' => 'faculty',
    'profile_picture' => 'default.png',
    'phone' => '+91 98222 33445',
    'created_at' => 
    \DateTime::__set_state(array(
       'date' => '2026-11-19 13:34:44.156046',
       'timezone_type' => 3,
       'timezone' => 'Europe/Berlin',
    )),
  ),
  3 => 
  array (
    'userId' => 4,
    'name' => 'Dr. Sunita Deshmukh',
    'email' => 'sunita.deshmukh@college.edu',
    'password' => 'faculty123',
    'role' => 'faculty',
    'profile_picture' => 'default.png',
    'phone' => '+91 98333 44556',
    'created_at' => 
    \DateTime::__set_state(array(
       'date' => '2026-11-19 13:34:44.156047',
       'timezone_type' => 3,
       'timezone' => 'Europe/Berlin',
    )),
  ),
  4 => 
  array (
    'userId' => 5,
    'name' => 'Sunny Kumar',
    'email' => 'student@gmail.com',
    'password' => 'student123',
    'role' => 'student',
    'profile_picture' => 'default.png',
    'phone' => '+91 91234 56789',
    'created_at' => 
    \DateTime::__set_state(array(
       'date' => '2026-11-19 13:34:44.156048',
       'timezone_type' => 3,
       'timezone' => 'Europe/Berlin',
    )),
  ),
  5 => 
  array (
    'userId' => 6,
    'name' => 'Priya Singh',
    'email' => 'priya.singh@student.edu',
    'password' => 'student123',
    'role' => 'student',
    'profile_picture' => 'default.png',
    'phone' => '+91 92345 67890',
    'created_at' => 
    \DateTime::__set_state(array(
       'date' => '2026-11-19 13:34:44.156049',
       'timezone_type' => 3,
       'timezone' => 'Europe/Berlin',
    )),
  ),
  6 => 
  array (
    'userId' => 7,
    'name' => 'Rahul Patel',
    'email' => 'rahul.patel@student.edu',
    'password' => 'student123',
    'role' => 'student',
    'profile_picture' => 'default.png',
    'phone' => '+91 93456 78901',
    'created_at' => 
    \DateTime::__set_state(array(
       'date' => '2026-11-19 13:34:44.156049',
       'timezone_type' => 3,
       'timezone' => 'Europe/Berlin',
    )),
  ),
  7 => 
  array (
    'userId' => 8,
    'name' => 'Sneha Kapoor',
    'email' => 'sneha.kapoor@student.edu',
    'password' => 'student123',
    'role' => 'student',
    'profile_picture' => 'default.png',
    'phone' => '+91 94567 89012',
    'created_at' => 
    \DateTime::__set_state(array(
       'date' => '2026-11-19 13:34:44.156050',
       'timezone_type' => 3,
       'timezone' => 'Europe/Berlin',
    )),
  ),
  8 => 
  array (
    'userId' => 9,
    'name' => 'Dr. Vikram Malhotra',
    'email' => 'vikram.malhotra@college.edu',
    'password' => 'faculty123',
    'role' => 'faculty',
    'profile_picture' => 'default.png',
    'phone' => '+91 98101 10001',
    'created_at' => 
    \DateTime::__set_state(array(
       'date' => '2026-11-19 13:34:44.156051',
       'timezone_type' => 3,
       'timezone' => 'Europe/Berlin',
    )),
  ),
  9 => 
  array (
    'userId' => 10,
    'name' => 'Prof. Neha Sen',
    'email' => 'neha.sen@college.edu',
    'password' => 'faculty123',
    'role' => 'faculty',
    'profile_picture' => 'default.png',
    'phone' => '+91 98101 10002',
    'created_at' => 
    \DateTime::__set_state(array(
       'date' => '2026-11-19 13:34:44.156052',
       'timezone_type' => 3,
       'timezone' => 'Europe/Berlin',
    )),
  ),
  10 => 
  array (
    'userId' => 11,
    'name' => 'Dr. Arvind Swaminathan',
    'email' => 'arvind.swaminathan@college.edu',
    'password' => 'faculty123',
    'role' => 'faculty',
    'profile_picture' => 'default.png',
    'phone' => '+91 98101 10003',
    'created_at' => 
    \DateTime::__set_state(array(
       'date' => '2026-11-19 13:34:44.156052',
       'timezone_type' => 3,
       'timezone' => 'Europe/Berlin',
    )),
  ),
  11 => 
  array (
    'userId' => 12,
    'name' => 'Dr. Meenakshi Sundaram',
    'email' => 'meenakshi.sundaram@college.edu',
    'password' => 'faculty123',
    'role' => 'faculty',
    'profile_picture' => 'default.png',
    'phone' => '+91 98101 10004',
    'created_at' => 
    \DateTime::__set_state(array(
       'date' => '2026-11-19 13:34:44.156053',
       'timezone_type' => 3,
       'timezone' => 'Europe/Berlin',
    )),
  ),
  12 => 
  array (
    'userId' => 13,
    'name' => 'Dr. Harish Chandra',
    'email' => 'harish.chandra@college.edu',
    'password' => 'faculty123',
    'role' => 'faculty',
    'profile_picture' => 'default.png',
    'phone' => '+91 98101 10005',
    'created_at' => 
    \DateTime::__set_state(array(
       'date' => '2026-11-19 13:34:44.156054',
       'timezone_type' => 3,
       'timezone' => 'Europe/Berlin',
    )),
  ),
  13 => 
  array (
    'userId' => 14,
    'name' => 'Dr. Siddharth Nair',
    'email' => 'siddharth.nair@college.edu',
    'password' => 'faculty123',
    'role' => 'faculty',
    'profile_picture' => 'default.png',
    'phone' => '+91 98101 10006',
    'created_at' => 
    \DateTime::__set_state(array(
       'date' => '2026-11-19 13:34:44.156055',
       'timezone_type' => 3,
       'timezone' => 'Europe/Berlin',
    )),
  ),
  14 => 
  array (
    'userId' => 15,
    'name' => 'Dr. Kavita Raman',
    'email' => 'kavita.raman@college.edu',
    'password' => 'faculty123',
    'role' => 'faculty',
    'profile_picture' => 'default.png',
    'phone' => '+91 98101 10007',
    'created_at' => 
    \DateTime::__set_state(array(
       'date' => '2026-11-19 13:34:44.156055',
       'timezone_type' => 3,
       'timezone' => 'Europe/Berlin',
    )),
  ),
  15 => 
  array (
    'userId' => 16,
    'name' => 'Dr. K. Radhakrishnan',
    'email' => 'radhakrishnan@college.edu',
    'password' => 'faculty123',
    'role' => 'faculty',
    'profile_picture' => 'default.png',
    'phone' => '+91 98101 10008',
    'created_at' => 
    \DateTime::__set_state(array(
       'date' => '2026-11-19 13:34:44.156056',
       'timezone_type' => 3,
       'timezone' => 'Europe/Berlin',
    )),
  ),
  16 => 
  array (
    'userId' => 17,
    'name' => 'Dr. Shalini Mukherjee',
    'email' => 'shalini.mukherjee@college.edu',
    'password' => 'faculty123',
    'role' => 'faculty',
    'profile_picture' => 'default.png',
    'phone' => '+91 98101 10009',
    'created_at' => 
    \DateTime::__set_state(array(
       'date' => '2026-11-19 13:34:44.156057',
       'timezone_type' => 3,
       'timezone' => 'Europe/Berlin',
    )),
  ),
  17 => 
  array (
    'userId' => 18,
    'name' => 'Dr. Pradeep Joshi',
    'email' => 'pradeep.joshi@college.edu',
    'password' => 'faculty123',
    'role' => 'faculty',
    'profile_picture' => 'default.png',
    'phone' => '+91 98101 10010',
    'created_at' => 
    \DateTime::__set_state(array(
       'date' => '2026-11-19 13:34:44.156057',
       'timezone_type' => 3,
       'timezone' => 'Europe/Berlin',
    )),
  ),
  18 => 
  array (
    'userId' => 19,
    'name' => 'Prof. Rakesh Jhunjhunwala',
    'email' => 'rakesh.finance@college.edu',
    'password' => 'faculty123',
    'role' => 'faculty',
    'profile_picture' => 'default.png',
    'phone' => '+91 98101 10011',
    'created_at' => 
    \DateTime::__set_state(array(
       'date' => '2026-11-19 13:34:44.156058',
       'timezone_type' => 3,
       'timezone' => 'Europe/Berlin',
    )),
  ),
  19 => 
  array (
    'userId' => 20,
    'name' => 'Dr. Amartya Banerjee',
    'email' => 'amartya.banerjee@college.edu',
    'password' => 'faculty123',
    'role' => 'faculty',
    'profile_picture' => 'default.png',
    'phone' => '+91 98101 10012',
    'created_at' => 
    \DateTime::__set_state(array(
       'date' => '2026-11-19 13:34:44.156059',
       'timezone_type' => 3,
       'timezone' => 'Europe/Berlin',
    )),
  ),
  20 => 
  array (
    'userId' => 21,
    'name' => 'Prof. Fali Nariman',
    'email' => 'fali.nariman@college.edu',
    'password' => 'faculty123',
    'role' => 'faculty',
    'profile_picture' => 'default.png',
    'phone' => '+91 98101 10013',
    'created_at' => 
    \DateTime::__set_state(array(
       'date' => '2026-11-19 13:34:44.156059',
       'timezone_type' => 3,
       'timezone' => 'Europe/Berlin',
    )),
  ),
  21 => 
  array (
    'userId' => 22,
    'name' => 'Dr. Swati Piramal',
    'email' => 'swati.piramal@college.edu',
    'password' => 'faculty123',
    'role' => 'faculty',
    'profile_picture' => 'default.png',
    'phone' => '+91 98101 10014',
    'created_at' => 
    \DateTime::__set_state(array(
       'date' => '2026-11-19 13:34:44.156060',
       'timezone_type' => 3,
       'timezone' => 'Europe/Berlin',
    )),
  ),
  22 => 
  array (
    'userId' => 23,
    'name' => 'Prof. B.V. Doshi',
    'email' => 'bv.doshi@college.edu',
    'password' => 'faculty123',
    'role' => 'faculty',
    'profile_picture' => 'default.png',
    'phone' => '+91 98101 10015',
    'created_at' => 
    \DateTime::__set_state(array(
       'date' => '2026-11-19 13:34:44.156061',
       'timezone_type' => 3,
       'timezone' => 'Europe/Berlin',
    )),
  ),
  23 => 
  array (
    'userId' => 24,
    'name' => 'Dr. Vandana Shiva',
    'email' => 'vandana.shiva@college.edu',
    'password' => 'faculty123',
    'role' => 'faculty',
    'profile_picture' => 'default.png',
    'phone' => '+91 98101 10016',
    'created_at' => 
    \DateTime::__set_state(array(
       'date' => '2026-11-19 13:34:44.156061',
       'timezone_type' => 3,
       'timezone' => 'Europe/Berlin',
    )),
  ),
  24 => 
  array (
    'userId' => 25,
    'name' => 'Dr. Manjul Bhargava',
    'email' => 'manjul.bhargava@college.edu',
    'password' => 'faculty123',
    'role' => 'faculty',
    'profile_picture' => 'default.png',
    'phone' => '+91 98101 10017',
    'created_at' => 
    \DateTime::__set_state(array(
       'date' => '2026-11-19 13:34:44.156062',
       'timezone_type' => 3,
       'timezone' => 'Europe/Berlin',
    )),
  ),
  25 => 
  array (
    'userId' => 26,
    'name' => 'Dr. Ashoke Sen',
    'email' => 'ashoke.sen@college.edu',
    'password' => 'faculty123',
    'role' => 'faculty',
    'profile_picture' => 'default.png',
    'phone' => '+91 98101 10018',
    'created_at' => 
    \DateTime::__set_state(array(
       'date' => '2026-11-19 13:34:44.156063',
       'timezone_type' => 3,
       'timezone' => 'Europe/Berlin',
    )),
  ),
  26 => 
  array (
    'userId' => 27,
    'name' => 'Dr. C.N.R. Rao',
    'email' => 'cnr.rao@college.edu',
    'password' => 'faculty123',
    'role' => 'faculty',
    'profile_picture' => 'default.png',
    'phone' => '+91 98101 10019',
    'created_at' => 
    \DateTime::__set_state(array(
       'date' => '2026-11-19 13:34:44.156063',
       'timezone_type' => 3,
       'timezone' => 'Europe/Berlin',
    )),
  ),
  27 => 
  array (
    'userId' => 28,
    'name' => 'Dr. M.S. Swaminathan',
    'email' => 'ms.swaminathan@college.edu',
    'password' => 'faculty123',
    'role' => 'faculty',
    'profile_picture' => 'default.png',
    'phone' => '+91 98101 10020',
    'created_at' => 
    \DateTime::__set_state(array(
       'date' => '2026-11-19 13:34:44.156064',
       'timezone_type' => 3,
       'timezone' => 'Europe/Berlin',
    )),
  ),
  28 => 
  array (
    'userId' => 29,
    'name' => 'Dr. Sunita Narain',
    'email' => 'sunita.narain@college.edu',
    'password' => 'faculty123',
    'role' => 'faculty',
    'profile_picture' => 'default.png',
    'phone' => '+91 98101 10021',
    'created_at' => 
    \DateTime::__set_state(array(
       'date' => '2026-11-19 13:34:44.156065',
       'timezone_type' => 3,
       'timezone' => 'Europe/Berlin',
    )),
  ),
  29 => 
  array (
    'userId' => 30,
    'name' => 'Prof. Prannoy Roy',
    'email' => 'prannoy.roy@college.edu',
    'password' => 'faculty123',
    'role' => 'faculty',
    'profile_picture' => 'default.png',
    'phone' => '+91 98101 10022',
    'created_at' => 
    \DateTime::__set_state(array(
       'date' => '2026-11-19 13:34:44.156065',
       'timezone_type' => 3,
       'timezone' => 'Europe/Berlin',
    )),
  ),
  30 => 
  array (
    'userId' => 31,
    'name' => 'Prof. Satyajit Sen',
    'email' => 'satyajit.sen@college.edu',
    'password' => 'faculty123',
    'role' => 'faculty',
    'profile_picture' => 'default.png',
    'phone' => '+91 98101 10023',
    'created_at' => 
    \DateTime::__set_state(array(
       'date' => '2026-11-19 13:34:44.156066',
       'timezone_type' => 3,
       'timezone' => 'Europe/Berlin',
    )),
  ),
  31 => 
  array (
    'userId' => 32,
    'name' => 'Chef Sanjeev Oberoi',
    'email' => 'sanjeev.oberoi@college.edu',
    'password' => 'faculty123',
    'role' => 'faculty',
    'profile_picture' => 'default.png',
    'phone' => '+91 98101 10024',
    'created_at' => 
    \DateTime::__set_state(array(
       'date' => '2026-11-19 13:34:44.156067',
       'timezone_type' => 3,
       'timezone' => 'Europe/Berlin',
    )),
  ),
  32 => 
  array (
    'userId' => 33,
    'name' => 'Dr. Florence Thomas',
    'email' => 'florence.thomas@college.edu',
    'password' => 'faculty123',
    'role' => 'faculty',
    'profile_picture' => 'default.png',
    'phone' => '+91 98101 10025',
    'created_at' => 
    \DateTime::__set_state(array(
       'date' => '2026-11-19 13:34:44.156067',
       'timezone_type' => 3,
       'timezone' => 'Europe/Berlin',
    )),
  ),
  33 => 
  array (
    'userId' => 34,
    'name' => 'Coach Pullela Gopichand',
    'email' => 'pullela.gopichand@college.edu',
    'password' => 'faculty123',
    'role' => 'faculty',
    'profile_picture' => 'default.png',
    'phone' => '+91 98101 10026',
    'created_at' => 
    \DateTime::__set_state(array(
       'date' => '2026-11-19 13:34:44.156068',
       'timezone_type' => 3,
       'timezone' => 'Europe/Berlin',
    )),
  ),
  34 => 
  array (
    'userId' => 35,
    'name' => 'Dr. Gayatri Spivak',
    'email' => 'gayatri.spivak@college.edu',
    'password' => 'faculty123',
    'role' => 'faculty',
    'profile_picture' => 'default.png',
    'phone' => '+91 98101 10027',
    'created_at' => 
    \DateTime::__set_state(array(
       'date' => '2026-11-19 13:34:44.156069',
       'timezone_type' => 3,
       'timezone' => 'Europe/Berlin',
    )),
  ),
  35 => 
  array (
    'userId' => 36,
    'name' => 'Dr. Krishna Kumar',
    'email' => 'krishna.kumar@college.edu',
    'password' => 'faculty123',
    'role' => 'faculty',
    'profile_picture' => 'default.png',
    'phone' => '+91 98101 10028',
    'created_at' => 
    \DateTime::__set_state(array(
       'date' => '2026-11-19 13:34:44.156070',
       'timezone_type' => 3,
       'timezone' => 'Europe/Berlin',
    )),
  ),
  36 => 
  array (
    'userId' => 37,
    'name' => 'Ustad Zakir Hussain',
    'email' => 'zakir.hussain@college.edu',
    'password' => 'faculty123',
    'role' => 'faculty',
    'profile_picture' => 'default.png',
    'phone' => '+91 98101 10029',
    'created_at' => 
    \DateTime::__set_state(array(
       'date' => '2026-11-19 13:34:44.156070',
       'timezone_type' => 3,
       'timezone' => 'Europe/Berlin',
    )),
  ),
  37 => 
  array (
    'userId' => 38,
    'name' => 'Rohan Sharma',
    'email' => 'rohan.sharma@student.edu',
    'password' => 'student123',
    'role' => 'student',
    'profile_picture' => 'default.png',
    'phone' => '+91 97100 20001',
    'created_at' => 
    \DateTime::__set_state(array(
       'date' => '2026-11-19 13:34:44.156071',
       'timezone_type' => 3,
       'timezone' => 'Europe/Berlin',
    )),
  ),
  38 => 
  array (
    'userId' => 39,
    'name' => 'Ananya Gupta',
    'email' => 'ananya.gupta@student.edu',
    'password' => 'student123',
    'role' => 'student',
    'profile_picture' => 'default.png',
    'phone' => '+91 97100 20002',
    'created_at' => 
    \DateTime::__set_state(array(
       'date' => '2026-11-19 13:34:44.156071',
       'timezone_type' => 3,
       'timezone' => 'Europe/Berlin',
    )),
  ),
  39 => 
  array (
    'userId' => 40,
    'name' => 'Vikram Singh',
    'email' => 'vikram.singh@student.edu',
    'password' => 'student123',
    'role' => 'student',
    'profile_picture' => 'default.png',
    'phone' => '+91 97100 20003',
    'created_at' => 
    \DateTime::__set_state(array(
       'date' => '2026-11-19 13:34:44.156072',
       'timezone_type' => 3,
       'timezone' => 'Europe/Berlin',
    )),
  ),
  40 => 
  array (
    'userId' => 41,
    'name' => 'Neha Patel',
    'email' => 'neha.patel@student.edu',
    'password' => 'student123',
    'role' => 'student',
    'profile_picture' => 'default.png',
    'phone' => '+91 97100 20004',
    'created_at' => 
    \DateTime::__set_state(array(
       'date' => '2026-11-19 13:34:44.156073',
       'timezone_type' => 3,
       'timezone' => 'Europe/Berlin',
    )),
  ),
  41 => 
  array (
    'userId' => 42,
    'name' => 'Amit Joshi',
    'email' => 'amit.joshi@student.edu',
    'password' => 'student123',
    'role' => 'student',
    'profile_picture' => 'default.png',
    'phone' => '+91 97100 20005',
    'created_at' => 
    \DateTime::__set_state(array(
       'date' => '2026-11-19 13:34:44.156073',
       'timezone_type' => 3,
       'timezone' => 'Europe/Berlin',
    )),
  ),
  42 => 
  array (
    'userId' => 43,
    'name' => 'Pooja Reddy',
    'email' => 'pooja.reddy@student.edu',
    'password' => 'student123',
    'role' => 'student',
    'profile_picture' => 'default.png',
    'phone' => '+91 97100 20006',
    'created_at' => 
    \DateTime::__set_state(array(
       'date' => '2026-11-19 13:34:44.156074',
       'timezone_type' => 3,
       'timezone' => 'Europe/Berlin',
    )),
  ),
  43 => 
  array (
    'userId' => 44,
    'name' => 'Karan Verma',
    'email' => 'karan.verma@student.edu',
    'password' => 'student123',
    'role' => 'student',
    'profile_picture' => 'default.png',
    'phone' => '+91 97100 20007',
    'created_at' => 
    \DateTime::__set_state(array(
       'date' => '2026-11-19 13:34:44.156075',
       'timezone_type' => 3,
       'timezone' => 'Europe/Berlin',
    )),
  ),
  44 => 
  array (
    'userId' => 45,
    'name' => 'Meera Iyer',
    'email' => 'meera.iyer@student.edu',
    'password' => 'student123',
    'role' => 'student',
    'profile_picture' => 'default.png',
    'phone' => '+91 97100 20008',
    'created_at' => 
    \DateTime::__set_state(array(
       'date' => '2026-11-19 13:34:44.156075',
       'timezone_type' => 3,
       'timezone' => 'Europe/Berlin',
    )),
  ),
  45 => 
  array (
    'userId' => 46,
    'name' => 'Aditya Saxena',
    'email' => 'aditya.saxena@student.edu',
    'password' => 'student123',
    'role' => 'student',
    'profile_picture' => 'default.png',
    'phone' => '+91 97100 20009',
    'created_at' => 
    \DateTime::__set_state(array(
       'date' => '2026-11-19 13:34:44.156076',
       'timezone_type' => 3,
       'timezone' => 'Europe/Berlin',
    )),
  ),
  46 => 
  array (
    'userId' => 47,
    'name' => 'Divya Nair',
    'email' => 'divya.nair@student.edu',
    'password' => 'student123',
    'role' => 'student',
    'profile_picture' => 'default.png',
    'phone' => '+91 97100 20010',
    'created_at' => 
    \DateTime::__set_state(array(
       'date' => '2026-11-19 13:34:44.156077',
       'timezone_type' => 3,
       'timezone' => 'Europe/Berlin',
    )),
  ),
  47 => 
  array (
    'userId' => 48,
    'name' => 'Harsh Vardhan',
    'email' => 'harsh.vardhan@student.edu',
    'password' => 'student123',
    'role' => 'student',
    'profile_picture' => 'default.png',
    'phone' => '+91 97100 20011',
    'created_at' => 
    \DateTime::__set_state(array(
       'date' => '2026-11-19 13:34:44.156077',
       'timezone_type' => 3,
       'timezone' => 'Europe/Berlin',
    )),
  ),
  48 => 
  array (
    'userId' => 49,
    'name' => 'Shreya Ghoshal',
    'email' => 'shreya.ghoshal@student.edu',
    'password' => 'student123',
    'role' => 'student',
    'profile_picture' => 'default.png',
    'phone' => '+91 97100 20012',
    'created_at' => 
    \DateTime::__set_state(array(
       'date' => '2026-11-19 13:34:44.156078',
       'timezone_type' => 3,
       'timezone' => 'Europe/Berlin',
    )),
  ),
  49 => 
  array (
    'userId' => 50,
    'name' => 'Kunal Bhatia',
    'email' => 'kunal.bhatia@student.edu',
    'password' => 'student123',
    'role' => 'student',
    'profile_picture' => 'default.png',
    'phone' => '+91 97100 20013',
    'created_at' => 
    \DateTime::__set_state(array(
       'date' => '2026-11-19 13:34:44.156079',
       'timezone_type' => 3,
       'timezone' => 'Europe/Berlin',
    )),
  ),
  50 => 
  array (
    'userId' => 51,
    'name' => 'Tanvi Desai',
    'email' => 'tanvi.desai@student.edu',
    'password' => 'student123',
    'role' => 'student',
    'profile_picture' => 'default.png',
    'phone' => '+91 97100 20014',
    'created_at' => 
    \DateTime::__set_state(array(
       'date' => '2026-11-19 13:34:44.156080',
       'timezone_type' => 3,
       'timezone' => 'Europe/Berlin',
    )),
  ),
  51 => 
  array (
    'userId' => 52,
    'name' => 'Varun Kashyap',
    'email' => 'varun.kashyap@student.edu',
    'password' => 'student123',
    'role' => 'student',
    'profile_picture' => 'default.png',
    'phone' => '+91 97100 20015',
    'created_at' => 
    \DateTime::__set_state(array(
       'date' => '2026-11-19 13:34:44.156080',
       'timezone_type' => 3,
       'timezone' => 'Europe/Berlin',
    )),
  ),
  52 => 
  array (
    'userId' => 53,
    'name' => 'Ritu Choudhary',
    'email' => 'ritu.choudhary@student.edu',
    'password' => 'student123',
    'role' => 'student',
    'profile_picture' => 'default.png',
    'phone' => '+91 97100 20016',
    'created_at' => 
    \DateTime::__set_state(array(
       'date' => '2026-11-19 13:34:44.156081',
       'timezone_type' => 3,
       'timezone' => 'Europe/Berlin',
    )),
  ),
  53 => 
  array (
    'userId' => 54,
    'name' => 'Abhishek Roy',
    'email' => 'abhishek.roy@student.edu',
    'password' => 'student123',
    'role' => 'student',
    'profile_picture' => 'default.png',
    'phone' => '+91 97100 20017',
    'created_at' => 
    \DateTime::__set_state(array(
       'date' => '2026-11-19 13:34:44.156082',
       'timezone_type' => 3,
       'timezone' => 'Europe/Berlin',
    )),
  ),
  54 => 
  array (
    'userId' => 55,
    'name' => 'Simran Kaur',
    'email' => 'simran.kaur@student.edu',
    'password' => 'student123',
    'role' => 'student',
    'profile_picture' => 'default.png',
    'phone' => '+91 97100 20018',
    'created_at' => 
    \DateTime::__set_state(array(
       'date' => '2026-11-19 13:34:44.156082',
       'timezone_type' => 3,
       'timezone' => 'Europe/Berlin',
    )),
  ),
  55 => 
  array (
    'userId' => 56,
    'name' => 'Manav Trivedi',
    'email' => 'manav.trivedi@student.edu',
    'password' => 'student123',
    'role' => 'student',
    'profile_picture' => 'default.png',
    'phone' => '+91 97100 20019',
    'created_at' => 
    \DateTime::__set_state(array(
       'date' => '2026-11-19 13:34:44.156083',
       'timezone_type' => 3,
       'timezone' => 'Europe/Berlin',
    )),
  ),
  56 => 
  array (
    'userId' => 57,
    'name' => 'Ayesha Siddiqui',
    'email' => 'ayesha.siddiqui@student.edu',
    'password' => 'student123',
    'role' => 'student',
    'profile_picture' => 'default.png',
    'phone' => '+91 97100 20020',
    'created_at' => 
    \DateTime::__set_state(array(
       'date' => '2026-11-19 13:34:44.156084',
       'timezone_type' => 3,
       'timezone' => 'Europe/Berlin',
    )),
  ),
  57 => 
  array (
    'userId' => 58,
    'name' => 'Nikhil Kulkarni',
    'email' => 'nikhil.kulkarni@student.edu',
    'password' => 'student123',
    'role' => 'student',
    'profile_picture' => 'default.png',
    'phone' => '+91 97100 20021',
    'created_at' => 
    \DateTime::__set_state(array(
       'date' => '2026-11-19 13:34:44.156084',
       'timezone_type' => 3,
       'timezone' => 'Europe/Berlin',
    )),
  ),
  58 => 
  array (
    'userId' => 59,
    'name' => 'Pallavi Sen',
    'email' => 'pallavi.sen@student.edu',
    'password' => 'student123',
    'role' => 'student',
    'profile_picture' => 'default.png',
    'phone' => '+91 97100 20022',
    'created_at' => 
    \DateTime::__set_state(array(
       'date' => '2026-11-19 13:34:44.156085',
       'timezone_type' => 3,
       'timezone' => 'Europe/Berlin',
    )),
  ),
  59 => 
  array (
    'userId' => 60,
    'name' => 'Gaurav Mathur',
    'email' => 'gaurav.mathur@student.edu',
    'password' => 'student123',
    'role' => 'student',
    'profile_picture' => 'default.png',
    'phone' => '+91 97100 20023',
    'created_at' => 
    \DateTime::__set_state(array(
       'date' => '2026-11-19 13:34:44.156085',
       'timezone_type' => 3,
       'timezone' => 'Europe/Berlin',
    )),
  ),
  60 => 
  array (
    'userId' => 61,
    'name' => 'Nandini Menon',
    'email' => 'nandini.menon@student.edu',
    'password' => 'student123',
    'role' => 'student',
    'profile_picture' => 'default.png',
    'phone' => '+91 97100 20024',
    'created_at' => 
    \DateTime::__set_state(array(
       'date' => '2026-11-19 13:34:44.156086',
       'timezone_type' => 3,
       'timezone' => 'Europe/Berlin',
    )),
  ),
  61 => 
  array (
    'userId' => 62,
    'name' => 'Rohit Pandey',
    'email' => 'rohit.pandey@student.edu',
    'password' => 'student123',
    'role' => 'student',
    'profile_picture' => 'default.png',
    'phone' => '+91 97100 20025',
    'created_at' => 
    \DateTime::__set_state(array(
       'date' => '2026-11-19 13:34:44.156087',
       'timezone_type' => 3,
       'timezone' => 'Europe/Berlin',
    )),
  ),
  62 => 
  array (
    'userId' => 63,
    'name' => 'Deepa Pillai',
    'email' => 'deepa.pillai@student.edu',
    'password' => 'student123',
    'role' => 'student',
    'profile_picture' => 'default.png',
    'phone' => '+91 97100 20026',
    'created_at' => 
    \DateTime::__set_state(array(
       'date' => '2026-11-19 13:34:44.156087',
       'timezone_type' => 3,
       'timezone' => 'Europe/Berlin',
    )),
  ),
  63 => 
  array (
    'userId' => 64,
    'name' => 'Mayank Agarwal',
    'email' => 'mayank.agarwal@student.edu',
    'password' => 'student123',
    'role' => 'student',
    'profile_picture' => 'default.png',
    'phone' => '+91 97100 20027',
    'created_at' => 
    \DateTime::__set_state(array(
       'date' => '2026-11-19 13:34:44.156088',
       'timezone_type' => 3,
       'timezone' => 'Europe/Berlin',
    )),
  ),
  64 => 
  array (
    'userId' => 65,
    'name' => 'Natasha Das',
    'email' => 'natasha.das@student.edu',
    'password' => 'student123',
    'role' => 'student',
    'profile_picture' => 'default.png',
    'phone' => '+91 97100 20028',
    'created_at' => 
    \DateTime::__set_state(array(
       'date' => '2026-11-19 13:34:44.156089',
       'timezone_type' => 3,
       'timezone' => 'Europe/Berlin',
    )),
  ),
  65 => 
  array (
    'userId' => 66,
    'name' => 'Chetan Anand',
    'email' => 'chetan.anand@student.edu',
    'password' => 'student123',
    'role' => 'student',
    'profile_picture' => 'default.png',
    'phone' => '+91 97100 20029',
    'created_at' => 
    \DateTime::__set_state(array(
       'date' => '2026-11-19 13:34:44.156090',
       'timezone_type' => 3,
       'timezone' => 'Europe/Berlin',
    )),
  ),
  66 => 
  array (
    'userId' => 67,
    'name' => 'Alok Mishra',
    'email' => 'alok.mishra@student.edu',
    'password' => 'student123',
    'role' => 'student',
    'profile_picture' => 'default.png',
    'phone' => '+91 97100 20030',
    'created_at' => 
    \DateTime::__set_state(array(
       'date' => '2026-11-19 13:34:44.156090',
       'timezone_type' => 3,
       'timezone' => 'Europe/Berlin',
    )),
  ),
  67 => 
  array (
    'userId' => 68,
    'name' => 'Barkha Dutt',
    'email' => 'barkha.dutt@student.edu',
    'password' => 'student123',
    'role' => 'student',
    'profile_picture' => 'default.png',
    'phone' => '+91 97100 20031',
    'created_at' => 
    \DateTime::__set_state(array(
       'date' => '2026-11-19 13:34:44.156091',
       'timezone_type' => 3,
       'timezone' => 'Europe/Berlin',
    )),
  ),
));
    insertCollection($manager, $dbName, 'faculty', array (
  0 => 
  array (
    'facultyId' => 1,
    'userId' => 2,
    'departmentId' => 1,
    'department' => 'Computer Science',
    'designation' => 'Professor & Dean Academics',
    'qualification' => 'Ph.D. in Computer Science (IIT Delhi)',
    'specialization' => 'Relational Databases, Distributed Systems & AI',
    'office' => 'Academic Block A, Suite 401',
  ),
  1 => 
  array (
    'facultyId' => 2,
    'userId' => 3,
    'departmentId' => 3,
    'department' => 'Information Technology',
    'designation' => 'Associate Professor',
    'qualification' => 'Ph.D. in Information Systems',
    'specialization' => 'Cloud Computing, Web Services & Big Data',
    'office' => 'IT Tower, Room 210',
  ),
  2 => 
  array (
    'facultyId' => 3,
    'userId' => 4,
    'departmentId' => 2,
    'department' => 'Business Administration',
    'designation' => 'Professor & HOD',
    'qualification' => 'Ph.D. in Management (IIM Ahmedabad)',
    'specialization' => 'Strategic Leadership & Corporate Governance',
    'office' => 'Management Wing, Room 102',
  ),
  3 => 
  array (
    'facultyId' => 4,
    'userId' => 9,
    'departmentId' => 4,
    'department' => 'Mechanical Engineering',
    'designation' => 'Professor & HOD',
    'qualification' => 'Ph.D. in Robotics (IIT Bombay)',
    'specialization' => 'Mechatronics, Electric Vehicles & CAD/CAM',
    'office' => 'Engineering Workshop Block, Room 105',
  ),
  4 => 
  array (
    'facultyId' => 5,
    'userId' => 10,
    'departmentId' => 5,
    'department' => 'Humanities & Journalism',
    'designation' => 'Associate Professor',
    'qualification' => 'Ph.D. in Mass Communication (JNU)',
    'specialization' => 'Media Ethics, Investigative Journalism & Cultural Studies',
    'office' => 'Humanities Block, Room 204',
  ),
  5 => 
  array (
    'facultyId' => 6,
    'userId' => 11,
    'departmentId' => 6,
    'department' => 'Electronics Engineering',
    'designation' => 'Professor & HOD',
    'qualification' => 'Ph.D. in VLSI Design (IIT Madras)',
    'specialization' => 'Embedded Systems, 5G Wireless & Signal Processing',
    'office' => 'Technology Center, Room 312',
  ),
  6 => 
  array (
    'facultyId' => 7,
    'userId' => 12,
    'departmentId' => 7,
    'department' => 'Electrical Engineering',
    'designation' => 'Professor & HOD',
    'qualification' => 'Ph.D. in Power Systems (IIT Roorkee)',
    'specialization' => 'Smart Grid Architecture & Renewable Energy',
    'office' => 'Power Systems Wing, Room 201',
  ),
  7 => 
  array (
    'facultyId' => 8,
    'userId' => 13,
    'departmentId' => 8,
    'department' => 'Civil Engineering',
    'designation' => 'Associate Professor',
    'qualification' => 'Ph.D. in Structural Engineering (IIT Kharagpur)',
    'specialization' => 'Earthquake-Resistant Structures & Concrete Tech',
    'office' => 'Civil Engineering Block, Room 118',
  ),
  8 => 
  array (
    'facultyId' => 9,
    'userId' => 14,
    'departmentId' => 9,
    'department' => 'Artificial Intelligence',
    'designation' => 'Professor & HOD',
    'qualification' => 'Ph.D. in Machine Learning (IISc Bangalore)',
    'specialization' => 'Deep Learning, Natural Language Processing & LLMs',
    'office' => 'AI Research Lab, Room 402',
  ),
  9 => 
  array (
    'facultyId' => 10,
    'userId' => 15,
    'departmentId' => 10,
    'department' => 'Cybersecurity',
    'designation' => 'Associate Professor',
    'qualification' => 'Ph.D. in Cyber Defense (Carnegie Mellon)',
    'specialization' => 'Ethical Hacking, Cryptography & Threat Hunting',
    'office' => 'Cyber Security Operations Center, Room 205',
  ),
  10 => 
  array (
    'facultyId' => 11,
    'userId' => 16,
    'departmentId' => 11,
    'department' => 'Aerospace Engineering',
    'designation' => 'Distinguished Professor',
    'qualification' => 'Ph.D. in Aeronautics (ISRO / MIT)',
    'specialization' => 'Rocket Propulsion, Flight Dynamics & Hypersonic Flow',
    'office' => 'Aeronautics Hangar, Suite 101',
  ),
  11 => 
  array (
    'facultyId' => 12,
    'userId' => 17,
    'departmentId' => 12,
    'department' => 'Biotechnology',
    'designation' => 'Professor & HOD',
    'qualification' => 'Ph.D. in Molecular Genetics (Oxford)',
    'specialization' => 'CRISPR Gene Editing, Bioinformatics & Biopharma',
    'office' => 'Bio-Research Complex, Room 303',
  ),
  12 => 
  array (
    'facultyId' => 13,
    'userId' => 18,
    'departmentId' => 13,
    'department' => 'Chemical Engineering',
    'designation' => 'Associate Professor',
    'qualification' => 'Ph.D. in Chemical Process Engineering (IIT Kanpur)',
    'specialization' => 'Petrochemical Refining, Polymers & Catalysis',
    'office' => 'Chemical Labs, Room 112',
  ),
  13 => 
  array (
    'facultyId' => 14,
    'userId' => 19,
    'departmentId' => 14,
    'department' => 'Commerce & Finance',
    'designation' => 'Professor of Practice',
    'qualification' => 'FCA, Ph.D. in FinTech & Derivatives',
    'specialization' => 'Algorithmic Trading, Corporate Taxation & Mergers',
    'office' => 'Finance Lab, Room 215',
  ),
  14 => 
  array (
    'facultyId' => 15,
    'userId' => 20,
    'departmentId' => 15,
    'department' => 'Economics',
    'designation' => 'Professor & HOD',
    'qualification' => 'Ph.D. in Development Economics (LSE)',
    'specialization' => 'Econometrics, Game Theory & Public Policy Design',
    'office' => 'Social Sciences Tower, Room 305',
  ),
  15 => 
  array (
    'facultyId' => 16,
    'userId' => 21,
    'departmentId' => 16,
    'department' => 'Legal Studies',
    'designation' => 'Senior Professor & Dean',
    'qualification' => 'LL.D. in Constitutional Jurisprudence (Harvard)',
    'specialization' => 'Constitutional Law, Corporate Litigation & Human Rights',
    'office' => 'Law Moot Court Building, Room 101',
  ),
  16 => 
  array (
    'facultyId' => 17,
    'userId' => 22,
    'departmentId' => 17,
    'department' => 'Pharmacy',
    'designation' => 'Professor & Dean',
    'qualification' => 'Ph.D. in Pharmacology (NIPER)',
    'specialization' => 'Novel Drug Delivery Systems & Clinical Trials',
    'office' => 'Pharmacy Complex, Room 202',
  ),
  17 => 
  array (
    'facultyId' => 18,
    'userId' => 23,
    'departmentId' => 18,
    'department' => 'Architecture',
    'designation' => 'Chief Architecture Fellow',
    'qualification' => 'M.Arch, Pritzker Fellow (CEPT)',
    'specialization' => 'Urban Master Planning & Biophilic Architecture',
    'office' => 'Design Studios, Room 401',
  ),
  18 => 
  array (
    'facultyId' => 19,
    'userId' => 24,
    'departmentId' => 19,
    'department' => 'Psychology',
    'designation' => 'Professor & HOD',
    'qualification' => 'Ph.D. in Cognitive Neuropsychology (NIMHANS)',
    'specialization' => 'Behavioral Therapy, Neuro-Imaging & Mental Health',
    'office' => 'Psychology Clinic, Room 108',
  ),
  19 => 
  array (
    'facultyId' => 20,
    'userId' => 25,
    'departmentId' => 20,
    'department' => 'Mathematics',
    'designation' => 'Visiting Professor',
    'qualification' => 'Ph.D. in Number Theory (Princeton)',
    'specialization' => 'Algebraic Geometry, Probability & Cryptography',
    'office' => 'Ramanujan Mathematical Center, Room 310',
  ),
  20 => 
  array (
    'facultyId' => 21,
    'userId' => 26,
    'departmentId' => 21,
    'department' => 'Applied Physics',
    'designation' => 'Professor of Physics',
    'qualification' => 'Ph.D. in Quantum Mechanics (TIFR)',
    'specialization' => 'Superconductivity, Quantum Computing & Photonics',
    'office' => 'C.V. Raman Physics Lab, Room 208',
  ),
  21 => 
  array (
    'facultyId' => 22,
    'userId' => 27,
    'departmentId' => 22,
    'department' => 'Chemistry',
    'designation' => 'Honorary Chair Professor',
    'qualification' => 'Ph.D. in Solid State Chemistry (UC Berkeley)',
    'specialization' => 'Nanomaterials, Green Catalysis & Polymer Science',
    'office' => 'Chemistry Research Center, Room 104',
  ),
  22 => 
  array (
    'facultyId' => 23,
    'userId' => 28,
    'departmentId' => 23,
    'department' => 'Agricultural Sciences',
    'designation' => 'Professor & HOD',
    'qualification' => 'Ph.D. in Plant Genetics (IARI)',
    'specialization' => 'Agrigenomics, Precision Farming & Soil Health',
    'office' => 'Agronomy Experimental Field Office 1',
  ),
  23 => 
  array (
    'facultyId' => 24,
    'userId' => 29,
    'departmentId' => 24,
    'department' => 'Environmental Science',
    'designation' => 'Professor & HOD',
    'qualification' => 'Ph.D. in Climate Dynamics (Cambridge)',
    'specialization' => 'Carbon Sequestration, Hydrology & Ecological Policy',
    'office' => 'Ecology Center, Room 201',
  ),
  24 => 
  array (
    'facultyId' => 25,
    'userId' => 30,
    'departmentId' => 25,
    'department' => 'Media Studies',
    'designation' => 'Professor of Broadcasting',
    'qualification' => 'M.A. in Visual Journalism (Columbia)',
    'specialization' => 'Documentary Production, News Broadcasting & Podcasting',
    'office' => 'University Media Studio, Room 103',
  ),
  25 => 
  array (
    'facultyId' => 26,
    'userId' => 31,
    'departmentId' => 26,
    'department' => 'Animation & Design',
    'designation' => 'Associate Professor',
    'qualification' => 'M.Des in Animation Art (NID)',
    'specialization' => 'Virtual Production, Unreal Engine & 3D Character Art',
    'office' => 'Animation Studio B, Room 220',
  ),
  26 => 
  array (
    'facultyId' => 27,
    'userId' => 32,
    'departmentId' => 27,
    'department' => 'Hospitality & Tourism',
    'designation' => 'Master Professor of Culinary',
    'qualification' => 'Master Chef Distinction (Le Cordon Bleu)',
    'specialization' => 'International Cuisine, Hotel Administration & Oenology',
    'office' => 'Culinary Arts Kitchen 1',
  ),
  27 => 
  array (
    'facultyId' => 28,
    'userId' => 33,
    'departmentId' => 28,
    'department' => 'Nursing & Health',
    'designation' => 'Clinical Professor & HOD',
    'qualification' => 'Ph.D. in Nursing Practice (Johns Hopkins)',
    'specialization' => 'Critical ICU Care, Emergency Response & Hospital Safety',
    'office' => 'Clinical Simulation Center, Room 102',
  ),
  28 => 
  array (
    'facultyId' => 29,
    'userId' => 34,
    'departmentId' => 29,
    'department' => 'Sports Science',
    'designation' => 'Director of Athletics',
    'qualification' => 'M.P.Ed, Olympic Performance Coach',
    'specialization' => 'Kinesiology, Peak Conditioning & Athletic Physiology',
    'office' => 'University Sports Arena, Room 101',
  ),
  29 => 
  array (
    'facultyId' => 30,
    'userId' => 35,
    'departmentId' => 30,
    'department' => 'English Literature',
    'designation' => 'Professor of Humanities',
    'qualification' => 'Ph.D. in English Literature (Columbia)',
    'specialization' => 'Modernist Poetry, Comparative Rhetoric & World Drama',
    'office' => 'Literature Library, Room 207',
  ),
  30 => 
  array (
    'facultyId' => 31,
    'userId' => 36,
    'departmentId' => 31,
    'department' => 'Educational Sciences',
    'designation' => 'Professor & HOD',
    'qualification' => 'Ph.D. in Educational Pedagogy (Delhi University)',
    'specialization' => 'Cognitive Instructional Design & STEM Curriculum',
    'office' => 'Education Wing, Room 301',
  ),
  31 => 
  array (
    'facultyId' => 32,
    'userId' => 37,
    'departmentId' => 32,
    'department' => 'Music & Performing Arts',
    'designation' => 'Chair of Performing Arts',
    'qualification' => 'Sangeet Natak Akademi Fellow',
    'specialization' => 'Percussion Acoustics, Ethnomusicology & Orchestra',
    'office' => 'Performing Arts Auditorium Studio 1',
  ),
));
    insertCollection($manager, $dbName, 'students', array (
  0 => 
  array (
    'studentId' => 1,
    'userId' => 5,
    'roll_no' => '24BCS12641',
    'departmentId' => 1,
    'courseId' => 2,
    'course' => 'BCA',
    'semester' => 4,
    'admission_year' => 2024,
    'gender' => 'Male',
    'dob' => '2004-05-15',
    'address' => 'Flat 402, Royal Palms Enclave, Tech Park Road',
    'status' => 'Active',
  ),
  1 => 
  array (
    'studentId' => 2,
    'userId' => 6,
    'roll_no' => '24BCS12642',
    'departmentId' => 1,
    'courseId' => 2,
    'course' => 'BCA',
    'semester' => 4,
    'admission_year' => 2024,
    'gender' => 'Female',
    'dob' => '2004-08-20',
    'address' => 'House 12B, Green Valley Residency, Campus View',
    'status' => 'Active',
  ),
  2 => 
  array (
    'studentId' => 3,
    'userId' => 7,
    'roll_no' => '24BCS12643',
    'departmentId' => 1,
    'courseId' => 1,
    'course' => 'BTECH-CSE',
    'semester' => 4,
    'admission_year' => 2024,
    'gender' => 'Male',
    'dob' => '2003-11-10',
    'address' => 'Plot 88, Sunrise Apartments, Sector 14',
    'status' => 'Active',
  ),
  3 => 
  array (
    'studentId' => 4,
    'userId' => 8,
    'roll_no' => '24BCS12644',
    'departmentId' => 2,
    'courseId' => 3,
    'course' => 'BBA',
    'semester' => 2,
    'admission_year' => 2025,
    'gender' => 'Female',
    'dob' => '2005-02-28',
    'address' => 'Villa 5, Golden Orchid Heights, Civil Lines',
    'status' => 'Active',
  ),
  4 => 
  array (
    'studentId' => 5,
    'userId' => 38,
    'roll_no' => '24CSE101',
    'departmentId' => 1,
    'courseId' => 1,
    'course' => 'BTECH-CSE',
    'semester' => 3,
    'admission_year' => 2024,
    'gender' => 'Male',
    'dob' => '2004-04-12',
    'address' => 'Hostel Block A, Room 204, Campus',
    'status' => 'Active',
  ),
  5 => 
  array (
    'studentId' => 6,
    'userId' => 39,
    'roll_no' => '24CSE102',
    'departmentId' => 1,
    'courseId' => 1,
    'course' => 'BTECH-CSE',
    'semester' => 3,
    'admission_year' => 2024,
    'gender' => 'Female',
    'dob' => '2004-09-18',
    'address' => 'Tower 3, Cyber Greens, IT Highway',
    'status' => 'Active',
  ),
  6 => 
  array (
    'studentId' => 7,
    'userId' => 40,
    'roll_no' => '24BBA201',
    'departmentId' => 2,
    'courseId' => 3,
    'course' => 'BBA',
    'semester' => 4,
    'admission_year' => 2024,
    'gender' => 'Male',
    'dob' => '2003-12-05',
    'address' => '14 Rosewood Lane, Model Town',
    'status' => 'Active',
  ),
  7 => 
  array (
    'studentId' => 8,
    'userId' => 41,
    'roll_no' => '24MBA202',
    'departmentId' => 2,
    'courseId' => 4,
    'course' => 'MBA',
    'semester' => 2,
    'admission_year' => 2025,
    'gender' => 'Female',
    'dob' => '2002-07-22',
    'address' => 'Penthouse 11, Silver Oak Enclave',
    'status' => 'Active',
  ),
  8 => 
  array (
    'studentId' => 9,
    'userId' => 42,
    'roll_no' => '24MCA301',
    'departmentId' => 3,
    'courseId' => 5,
    'course' => 'MCA',
    'semester' => 2,
    'admission_year' => 2025,
    'gender' => 'Male',
    'dob' => '2003-03-30',
    'address' => 'Sector 21B, Urban Estate, Chandigarh',
    'status' => 'Active',
  ),
  9 => 
  array (
    'studentId' => 10,
    'userId' => 43,
    'roll_no' => '24MCA302',
    'departmentId' => 3,
    'courseId' => 5,
    'course' => 'MCA',
    'semester' => 4,
    'admission_year' => 2024,
    'gender' => 'Female',
    'dob' => '2002-11-14',
    'address' => 'House 45, DLF Phase 2, Gurgaon',
    'status' => 'Active',
  ),
  10 => 
  array (
    'studentId' => 11,
    'userId' => 44,
    'roll_no' => '24MEC401',
    'departmentId' => 4,
    'courseId' => 6,
    'course' => 'BTECH-MECH',
    'semester' => 6,
    'admission_year' => 2023,
    'gender' => 'Male',
    'dob' => '2003-01-25',
    'address' => 'Plot 102, Industrial Estate Road',
    'status' => 'Active',
  ),
  11 => 
  array (
    'studentId' => 12,
    'userId' => 45,
    'roll_no' => '24HAJ501',
    'departmentId' => 5,
    'courseId' => 7,
    'course' => 'BA-HAJ',
    'semester' => 2,
    'admission_year' => 2025,
    'gender' => 'Female',
    'dob' => '2005-06-19',
    'address' => 'Flat 304, Media Enclave, Sector 62',
    'status' => 'Active',
  ),
  12 => 
  array (
    'studentId' => 13,
    'userId' => 46,
    'roll_no' => '24ECE601',
    'departmentId' => 6,
    'courseId' => 8,
    'course' => 'BTECH-ECE',
    'semester' => 4,
    'admission_year' => 2024,
    'gender' => 'Male',
    'dob' => '2004-02-14',
    'address' => 'House 77, Circuit City, Bengaluru',
    'status' => 'Active',
  ),
  13 => 
  array (
    'studentId' => 14,
    'userId' => 47,
    'roll_no' => '24EEE701',
    'departmentId' => 7,
    'courseId' => 9,
    'course' => 'BTECH-EEE',
    'semester' => 4,
    'admission_year' => 2024,
    'gender' => 'Female',
    'dob' => '2004-10-08',
    'address' => '88 Power Grid Colony, Hyderabad',
    'status' => 'Active',
  ),
  14 => 
  array (
    'studentId' => 15,
    'userId' => 48,
    'roll_no' => '24CIV801',
    'departmentId' => 8,
    'courseId' => 10,
    'course' => 'BTECH-CIVIL',
    'semester' => 6,
    'admission_year' => 2023,
    'gender' => 'Male',
    'dob' => '2003-05-17',
    'address' => 'Civil Lines, Near University Gate 2',
    'status' => 'Active',
  ),
  15 => 
  array (
    'studentId' => 16,
    'userId' => 49,
    'roll_no' => '24AID901',
    'departmentId' => 9,
    'courseId' => 11,
    'course' => 'BTECH-AIDS',
    'semester' => 2,
    'admission_year' => 2025,
    'gender' => 'Female',
    'dob' => '2005-08-11',
    'address' => 'Hostel Block B, Room 112, Campus',
    'status' => 'Active',
  ),
  16 => 
  array (
    'studentId' => 17,
    'userId' => 50,
    'roll_no' => '24CYB101',
    'departmentId' => 10,
    'courseId' => 12,
    'course' => 'BTECH-CYBER',
    'semester' => 4,
    'admission_year' => 2024,
    'gender' => 'Male',
    'dob' => '2004-01-29',
    'address' => 'Silicon Towers, Phase 7, Mohali',
    'status' => 'Active',
  ),
  17 => 
  array (
    'studentId' => 18,
    'userId' => 51,
    'roll_no' => '24AER111',
    'departmentId' => 11,
    'courseId' => 13,
    'course' => 'BTECH-AERO',
    'semester' => 5,
    'admission_year' => 2023,
    'gender' => 'Female',
    'dob' => '2003-08-04',
    'address' => 'Airport Road, Viman Nagar, Pune',
    'status' => 'Active',
  ),
  18 => 
  array (
    'studentId' => 19,
    'userId' => 52,
    'roll_no' => '24BIO121',
    'departmentId' => 12,
    'courseId' => 14,
    'course' => 'BTECH-BIOTECH',
    'semester' => 3,
    'admission_year' => 2024,
    'gender' => 'Male',
    'dob' => '2004-12-16',
    'address' => 'Genome Valley, Block 4, Hyderabad',
    'status' => 'Active',
  ),
  19 => 
  array (
    'studentId' => 20,
    'userId' => 53,
    'roll_no' => '24CHE131',
    'departmentId' => 13,
    'courseId' => 15,
    'course' => 'BTECH-CHE',
    'semester' => 5,
    'admission_year' => 2023,
    'gender' => 'Female',
    'dob' => '2003-04-20',
    'address' => 'Refinery Township, Sector 8, Vadodara',
    'status' => 'Active',
  ),
  20 => 
  array (
    'studentId' => 21,
    'userId' => 54,
    'roll_no' => '24COM141',
    'departmentId' => 14,
    'courseId' => 16,
    'course' => 'BCOM-HONS',
    'semester' => 3,
    'admission_year' => 2024,
    'gender' => 'Male',
    'dob' => '2004-07-09',
    'address' => 'Dalal Street Residency, Mumbai',
    'status' => 'Active',
  ),
  21 => 
  array (
    'studentId' => 22,
    'userId' => 55,
    'roll_no' => '24ECO151',
    'departmentId' => 15,
    'courseId' => 17,
    'course' => 'BSC-ECON',
    'semester' => 2,
    'admission_year' => 2025,
    'gender' => 'Female',
    'dob' => '2005-03-14',
    'address' => 'Chanakyapuri, Diplomatic Enclave, Delhi',
    'status' => 'Active',
  ),
  22 => 
  array (
    'studentId' => 23,
    'userId' => 56,
    'roll_no' => '24LAW161',
    'departmentId' => 16,
    'courseId' => 18,
    'course' => 'BA-LLB',
    'semester' => 6,
    'admission_year' => 2023,
    'gender' => 'Male',
    'dob' => '2002-10-31',
    'address' => 'High Court Enclave, Sector 1, Chandigarh',
    'status' => 'Active',
  ),
  23 => 
  array (
    'studentId' => 24,
    'userId' => 57,
    'roll_no' => '24PHA171',
    'departmentId' => 17,
    'courseId' => 19,
    'course' => 'BPHARM',
    'semester' => 4,
    'admission_year' => 2024,
    'gender' => 'Female',
    'dob' => '2004-06-27',
    'address' => 'Pharma City, Baddi, Himachal Pradesh',
    'status' => 'Active',
  ),
  24 => 
  array (
    'studentId' => 25,
    'userId' => 58,
    'roll_no' => '24ARC181',
    'departmentId' => 18,
    'courseId' => 20,
    'course' => 'BARCH',
    'semester' => 6,
    'admission_year' => 2023,
    'gender' => 'Male',
    'dob' => '2003-09-03',
    'address' => 'Studio Quarter, Heritage Zone, Jaipur',
    'status' => 'Active',
  ),
  25 => 
  array (
    'studentId' => 26,
    'userId' => 59,
    'roll_no' => '24PSY191',
    'departmentId' => 19,
    'courseId' => 21,
    'course' => 'BSC-PSYCH',
    'semester' => 2,
    'admission_year' => 2025,
    'gender' => 'Female',
    'dob' => '2005-11-20',
    'address' => 'Lake View Gardens, Bhopal',
    'status' => 'Active',
  ),
  26 => 
  array (
    'studentId' => 27,
    'userId' => 60,
    'roll_no' => '24MAT201',
    'departmentId' => 20,
    'courseId' => 22,
    'course' => 'BSC-MATH',
    'semester' => 4,
    'admission_year' => 2024,
    'gender' => 'Male',
    'dob' => '2004-03-08',
    'address' => 'Ramanujan Hostel, Campus Block C',
    'status' => 'Active',
  ),
  27 => 
  array (
    'studentId' => 28,
    'userId' => 61,
    'roll_no' => '24PHY211',
    'departmentId' => 21,
    'courseId' => 23,
    'course' => 'BSC-PHY',
    'semester' => 4,
    'admission_year' => 2024,
    'gender' => 'Female',
    'dob' => '2004-05-24',
    'address' => 'Cosmic Heights, Observatory Hill, Nainital',
    'status' => 'Active',
  ),
  28 => 
  array (
    'studentId' => 29,
    'userId' => 62,
    'roll_no' => '24CHM221',
    'departmentId' => 22,
    'courseId' => 24,
    'course' => 'BSC-CHEM',
    'semester' => 2,
    'admission_year' => 2025,
    'gender' => 'Male',
    'dob' => '2005-01-19',
    'address' => 'Crystal Palace Apartments, Indore',
    'status' => 'Active',
  ),
  29 => 
  array (
    'studentId' => 30,
    'userId' => 63,
    'roll_no' => '24AGR231',
    'departmentId' => 23,
    'courseId' => 25,
    'course' => 'BSC-AGRI',
    'semester' => 4,
    'admission_year' => 2024,
    'gender' => 'Female',
    'dob' => '2004-08-02',
    'address' => 'Farm Research Hub, PAU Road, Ludhiana',
    'status' => 'Active',
  ),
  30 => 
  array (
    'studentId' => 31,
    'userId' => 64,
    'roll_no' => '24ENV241',
    'departmentId' => 24,
    'courseId' => 26,
    'course' => 'BSC-ENV',
    'semester' => 3,
    'admission_year' => 2024,
    'gender' => 'Male',
    'dob' => '2004-06-11',
    'address' => 'Eco Forest Valley, Dehradun',
    'status' => 'Active',
  ),
  31 => 
  array (
    'studentId' => 32,
    'userId' => 65,
    'roll_no' => '24MED251',
    'departmentId' => 25,
    'courseId' => 27,
    'course' => 'BJMC',
    'semester' => 2,
    'admission_year' => 2025,
    'gender' => 'Female',
    'dob' => '2005-04-07',
    'address' => 'Broadcasting Colony, Film City, Noida',
    'status' => 'Active',
  ),
  32 => 
  array (
    'studentId' => 33,
    'userId' => 66,
    'roll_no' => '24DES261',
    'departmentId' => 26,
    'courseId' => 28,
    'course' => 'BDES',
    'semester' => 4,
    'admission_year' => 2024,
    'gender' => 'Male',
    'dob' => '2004-02-22',
    'address' => 'Design District, Koramangala, Bengaluru',
    'status' => 'Active',
  ),
  33 => 
  array (
    'studentId' => 34,
    'userId' => 67,
    'roll_no' => '24HOS271',
    'departmentId' => 27,
    'courseId' => 29,
    'course' => 'BHM',
    'semester' => 3,
    'admission_year' => 2024,
    'gender' => 'Male',
    'dob' => '2004-07-17',
    'address' => 'Grand Heritage Boulevard, Udaipur',
    'status' => 'Active',
  ),
  34 => 
  array (
    'studentId' => 35,
    'userId' => 68,
    'roll_no' => '24NUR281',
    'departmentId' => 28,
    'courseId' => 30,
    'course' => 'BSC-NURSE',
    'semester' => 5,
    'admission_year' => 2023,
    'gender' => 'Female',
    'dob' => '2003-12-12',
    'address' => 'Medical Enclave, AIIMS Campus Road',
    'status' => 'Active',
  ),
));

    echo "SUCCESS: MongoDB 'student_portal' is fully seeded with 30+ records!" . PHP_EOL;
} catch (Exception $e) {
    echo "MongoDB Connection Note: " . $e->getMessage() . PHP_EOL;
}
