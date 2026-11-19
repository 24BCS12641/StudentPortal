<?php
/**
 * Modern University Portal - Admissions & Online Application
 * Apex Imperial University
 */

include("config/db.php");

$page_title = "Admissions 2026–2027 | Online Application & Status Tracking - Apex Imperial University";

$success_msg = "";
$error_msg = "";
$status_result = null;

// Handle Online Admission Application Form Submission
if (isset($_POST['submit_application'])) {
    $student_name = escape($con, $_POST['student_name']);
    $email = escape($con, $_POST['email']);
    $phone = escape($con, $_POST['phone']);
    $course_id = (int)$_POST['course_id'];
    $marks_12th = (float)$_POST['marks_12th'];
    $prev_school = escape($con, $_POST['prev_school']);
    $address = escape($con, $_POST['address']);

    if (empty($student_name) || empty($email) || empty($phone) || empty($course_id)) {
        $error_msg = "Please fill in all mandatory application fields marked with *.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_msg = "Please enter a valid email address.";
    } else {
        // Generate Unique Application Number
        $app_no = "ADM-2026-" . rand(1000, 9999);
        
        $sql = "INSERT INTO admissions (application_no, student_name, email, phone, course_id, marks_12th, prev_school, address, status)
                VALUES ('$app_no', '$student_name', '$email', '$phone', '$course_id', '$marks_12th', '$prev_school', '$address', 'Pending')";
        
        if (mysqli_query($con, $sql)) {
            $success_msg = "Your admission application has been registered successfully! Your Application Number is <strong>$app_no</strong>. Please save this number to track your admission status.";
        } else {
            $error_msg = "Failed to submit application: " . mysqli_error($con);
        }
    }
}

// Handle Application Status Tracking
if (isset($_GET['track_app'])) {
    $track_query = escape($con, $_GET['app_query']);
    if (!empty($track_query)) {
        $check_q = mysqli_query($con, "
            SELECT a.*, c.name AS course_name, c.code AS course_code
            FROM admissions a
            JOIN courses c ON a.course_id = c.course_id
            WHERE a.application_no='$track_query' OR a.email='$track_query'
            LIMIT 1
        ");
        if ($check_q && safe_num_rows($check_q) > 0) {
            $status_result = safe_fetch_assoc($check_q);
        } else {
            $error_msg = "No admission record found matching '<strong>" . htmlspecialchars($track_query) . "</strong>'. Please double-check your Application Number or Email.";
        }
    }
}

// Fetch available courses for application dropdown
$courses_list = mysqli_query($con, "SELECT * FROM courses ORDER BY degree_level DESC, name ASC");

include("includes/public_header.php");
include("includes/public_navbar.php");
?>

<!-- Breadcrumb Strip -->
<div class="bg-navy py-5 text-white position-relative" style="border-bottom: 2px solid var(--gold);">
    <div class="container py-3">
        <span class="badge bg-gold text-dark text-uppercase fw-bold px-3 py-2 mb-2">Admissions Cell 2026–27</span>
        <h1 class="display-4 fw-bold font-serif mb-2">Admissions & Online Application</h1>
        <p class="text-light-muted fs-6 mb-0">Apply online for undergraduate and postgraduate degree programs or track your live application review status.</p>
    </div>
</div>

<!-- 1. Admission Process Steps -->
<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="section-tag">Step-by-Step Guide</span>
            <h2 class="section-title">Streamlined 4-Step Admission Journey</h2>
            <p class="section-subtitle">Our transparent, paperless admissions system ensures rapid verification and merit-based seat allocation.</p>
        </div>

        <div class="row g-4 text-center">
            <div class="col-lg-3 col-sm-6" data-aos="fade-up">
                <div class="p-4 rounded-4 bg-light border h-100">
                    <div class="univ-crest mx-auto mb-3" style="width:50px; height:50px; font-size:20px;">1</div>
                    <h5 class="fw-bold text-navy mb-2">Submit Online Form</h5>
                    <p class="text-secondary small mb-0">Fill in your academic profile, 12th/Graduation marks, and choose your preferred degree program below.</p>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6" data-aos="fade-up" data-aos-delay="100">
                <div class="p-4 rounded-4 bg-light border h-100">
                    <div class="univ-crest mx-auto mb-3" style="width:50px; height:50px; font-size:20px;">2</div>
                    <h5 class="fw-bold text-navy mb-2">Document Verification</h5>
                    <p class="text-secondary small mb-0">Our admissions committee verifies eligibility criteria, certificates, and merit scholarship entitlements.</p>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6" data-aos="fade-up" data-aos-delay="200">
                <div class="p-4 rounded-4 bg-light border h-100">
                    <div class="univ-crest mx-auto mb-3" style="width:50px; height:50px; font-size:20px;">3</div>
                    <h5 class="fw-bold text-navy mb-2">Offer of Admission</h5>
                    <p class="text-secondary small mb-0">Provisional letter of admission issued via email and portal tracking with scholarship grant details.</p>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6" data-aos="fade-up" data-aos-delay="300">
                <div class="p-4 rounded-4 bg-light border h-100">
                    <div class="univ-crest mx-auto mb-3" style="width:50px; height:50px; font-size:20px;">4</div>
                    <h5 class="fw-bold text-navy mb-2">Enrollment & Portal ID</h5>
                    <p class="text-secondary small mb-0">Pay semester fee online to lock your admission seat and receive your Student Portal credentials.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Interactive Merit Scholarship & Fee Estimator -->
<section class="py-5 bg-light" id="scholarshipEstimator">
    <div class="container py-4">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="section-tag">Empowering Talent</span>
            <h2 class="section-title">Merit Scholarship & Fee Estimator</h2>
            <p class="section-subtitle">Calculate your qualifying tuition fee waiver based on 10+2 / Graduation academic percentage.</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-9" data-aos="zoom-in">
                <div class="scholarship-widget">
                    <div class="row align-items-center g-4">
                        <div class="col-md-7">
                            <div class="d-flex justify-content-between align-items-center">
                                <label class="fw-bold text-navy mb-0">Your Qualifying Score (12th / Grad):</label>
                                <span class="badge bg-gold text-dark fs-5 fw-bold px-3 py-1" id="scholarshipPctDisplay">92%</span>
                            </div>

                            <input type="range" class="custom-range-slider" id="scholarshipSlider" min="50" max="100" value="92" oninput="updateScholarship(this.value)">

                            <div class="d-flex justify-content-between text-muted small fw-semibold">
                                <span>50%</span>
                                <span>65%</span>
                                <span>75%</span>
                                <span>85%</span>
                                <span>95%+</span>
                            </div>

                            <div class="mt-4 p-3 rounded-3 bg-white border small">
                                <div class="text-muted mb-1"><i class="fa-solid fa-circle-info text-gold me-1"></i> <strong>Automatic Scholarship Evaluation:</strong></div>
                                Merit scholarships apply automatically to your first-year tuition fee upon successful online document verification.
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="scholarship-result-box text-center">
                                <span class="badge bg-gold text-dark text-uppercase fw-bold px-3 py-1 mb-2">Award Category</span>
                                <h5 class="fw-bold mb-2 font-serif text-white" id="scholarshipTier">Dean's Gold Merit Scholarship</h5>
                                <div class="fs-4 fw-bold text-gold mb-3" id="scholarshipDiscount">50% Tuition Waiver</div>
                                
                                <div class="p-3 rounded-3 bg-white bg-opacity-10 mb-3 text-start small">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-light-muted">Standard Annual Fee:</span>
                                        <del class="text-light-muted">₹1,60,000</del>
                                    </div>
                                    <div class="d-flex justify-content-between fw-bold">
                                        <span class="text-white">Net Estimated Fee:</span>
                                        <span class="text-gold fs-6" id="scholarshipNetFee">₹80,000 / Year</span>
                                    </div>
                                </div>

                                <a href="#applyNow" class="btn btn-gold btn-sm w-100 py-2">
                                    <i class="fa-solid fa-lock me-1"></i> Lock Scholarship & Apply
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 2. Application Status Tracker Widget -->
<section class="py-5 bg-navy text-white" id="trackStatus">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8" data-aos="zoom-in">
                <div class="p-4 p-md-5 rounded-4 bg-navy-light border border-secondary border-opacity-25 shadow-lg">
                    <div class="text-center mb-4">
                        <span class="badge bg-gold text-dark fw-bold px-3 py-2 text-uppercase mb-2">Live Application Tracker</span>
                        <h3 class="fw-bold font-serif text-white mb-1">Track Your Admission Status</h3>
                        <p class="text-light-muted small">Enter your Application Number (e.g. ADM-2026-8910) or registered Email Address.</p>
                    </div>

                    <form method="GET" action="admissions.php#trackStatus" class="d-flex gap-2 mb-3">
                        <input type="text" name="app_query" class="form-control form-control-lg" placeholder="Enter Application No. or Email" required value="<?php echo isset($_GET['app_query']) ? htmlspecialchars($_GET['app_query']) : ''; ?>">
                        <button type="submit" name="track_app" class="btn btn-gold btn-lg px-4 flex-shrink-0">
                            <i class="fa-solid fa-magnifying-glass me-1"></i> Track Status
                        </button>
                    </form>

                    <!-- Status Result Display -->
                    <?php if ($status_result): ?>
                        <div class="mt-4 p-4 rounded-3 bg-white text-navy border-start border-4 <?php echo ($status_result['status'] == 'Approved') ? 'border-success' : (($status_result['status'] == 'Rejected') ? 'border-danger' : 'border-warning'); ?>">
                            <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                                <h5 class="fw-bold mb-0 text-navy"><?php echo htmlspecialchars($status_result['student_name']); ?></h5>
                                <?php if ($status_result['status'] == 'Approved'): ?>
                                    <span class="badge bg-success fs-6"><i class="fa-solid fa-circle-check me-1"></i> Admission Approved</span>
                                <?php elseif ($status_result['status'] == 'Under Review'): ?>
                                    <span class="badge bg-primary fs-6"><i class="fa-solid fa-spinner me-1"></i> Under Review</span>
                                <?php elseif ($status_result['status'] == 'Rejected'): ?>
                                    <span class="badge bg-danger fs-6"><i class="fa-solid fa-circle-xmark me-1"></i> Application Rejected</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark fs-6"><i class="fa-solid fa-clock me-1"></i> Pending Verification</span>
                                <?php endif; ?>
                            </div>
                            <div class="row g-2 text-secondary small">
                                <div class="col-sm-6"><strong>Application No:</strong> <?php echo htmlspecialchars($status_result['application_no']); ?></div>
                                <div class="col-sm-6"><strong>Program Applied:</strong> <?php echo htmlspecialchars($status_result['course_name']); ?></div>
                                <div class="col-sm-6"><strong>12th Marks:</strong> <?php echo htmlspecialchars($status_result['marks_12th']); ?>%</div>
                                <div class="col-sm-6"><strong>Applied On:</strong> <?php echo date('d M Y, h:i A', strtotime($status_result['applied_at'])); ?></div>
                                <?php if (!empty($status_result['notes'])): ?>
                                    <div class="col-12 mt-2 pt-2 border-top">
                                        <strong>Committee Remarks:</strong> <?php echo htmlspecialchars($status_result['notes']); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 3. Online Admission Application Form -->
<section class="py-5 bg-light" id="applyNow">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-9" data-aos="fade-up">
                <div class="univ-card p-4 p-md-5">
                    <div class="text-center mb-4">
                        <span class="section-tag">Academic Session 2026–27</span>
                        <h2 class="section-title mb-2">Online Admission Application Form</h2>
                        <p class="section-subtitle">Please ensure the contact details and academic percentages match your official mark sheets.</p>
                    </div>

                    <?php if (!empty($success_msg)): ?>
                        <div class="alert alert-success d-flex align-items-center gap-3 p-3 rounded-3 mb-4">
                            <i class="fa-solid fa-circle-check fs-2 text-success"></i>
                            <div><?php echo $success_msg; ?></div>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($error_msg)): ?>
                        <div class="alert alert-danger d-flex align-items-center gap-3 p-3 rounded-3 mb-4">
                            <i class="fa-solid fa-circle-exclamation fs-3 text-danger"></i>
                            <div><?php echo $error_msg; ?></div>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="admissions.php#applyNow">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-navy">Full Legal Name <span class="text-danger">*</span></label>
                                <input type="text" name="student_name" class="form-control" placeholder="e.g. Sunny Kumar" required value="<?php echo isset($_POST['student_name']) ? htmlspecialchars($_POST['student_name']) : ''; ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-navy">Email Address <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" placeholder="e.g. sunny@gmail.com" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-navy">Mobile Number <span class="text-danger">*</span></label>
                                <input type="tel" name="phone" class="form-control" placeholder="e.g. +91 98765 43210" required value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-navy">Target Degree Program <span class="text-danger">*</span></label>
                                <select name="course_id" class="form-select" required>
                                    <option value="">-- Choose Course --</option>
                                    <?php if ($courses_list && safe_num_rows($courses_list) > 0): ?>
                                        <?php 
                                        $pre_sel = isset($_GET['course_id']) ? (int)$_GET['course_id'] : 0;
                                        while ($crs = safe_fetch_assoc($courses_list)): 
                                        ?>
                                            <option value="<?php echo $crs['course_id']; ?>" <?php echo ($pre_sel == $crs['course_id']) ? 'selected' : ''; ?>>
                                                [<?php echo $crs['degree_level']; ?>] <?php echo htmlspecialchars($crs['name']); ?> (<?php echo $crs['code']; ?>)
                                            </option>
                                        <?php endwhile; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-navy">12th / Qualifying Exam Aggregate (%) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" min="40" max="100" name="marks_12th" class="form-control" placeholder="e.g. 88.5" required value="<?php echo isset($_POST['marks_12th']) ? htmlspecialchars($_POST['marks_12th']) : ''; ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-navy">Previous School / College / Board</label>
                                <input type="text" name="prev_school" class="form-control" placeholder="e.g. CBSE / Delhi Public School" value="<?php echo isset($_POST['prev_school']) ? htmlspecialchars($_POST['prev_school']) : ''; ?>">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold text-navy">Permanent Postal Address</label>
                                <textarea name="address" rows="3" class="form-control" placeholder="Street address, City, State, PIN Code"><?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?></textarea>
                            </div>
                            <div class="col-12 text-end mt-4">
                                <button type="submit" name="submit_application" class="btn btn-gold btn-lg px-5">
                                    <i class="fa-solid fa-paper-plane me-2"></i> Submit Official Application
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include("includes/public_footer.php"); ?>
