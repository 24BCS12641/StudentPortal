<?php
/**
 * Modern University Portal - Contact Us
 * Apex Imperial University
 */

include("config/db.php");

$page_title = "Contact Us | Campus Map & Inquiries - Apex Imperial University";

$success = "";
$error = "";

// Handle Contact Form Submission
if (isset($_POST['send_message'])) {
    $name = escape($con, $_POST['name']);
    $email = escape($con, $_POST['email']);
    $phone = escape($con, $_POST['phone']);
    $subject = escape($con, $_POST['subject']);
    $message = escape($con, $_POST['message']);

    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error = "Please fill in all mandatory fields marked with *.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please provide a valid email address.";
    } else {
        $sql = "INSERT INTO contact_messages (name, email, phone, subject, message, status)
                VALUES ('$name', '$email', '$phone', '$subject', '$message', 'Unread')";
        
        if (mysqli_query($con, $sql)) {
            $success = "Thank you, <strong>" . htmlspecialchars($name) . "</strong>! Your message has been received by our University Administration. A representative will contact you within 24 hours.";
        } else {
            $error = "Failed to submit enquiry: " . mysqli_error($con);
        }
    }
}

include("includes/public_header.php");
include("includes/public_navbar.php");
?>

<!-- Breadcrumb Strip -->
<div class="bg-navy py-5 text-white position-relative" style="border-bottom: 2px solid var(--gold);">
    <div class="container py-3">
        <span class="badge bg-gold text-dark text-uppercase fw-bold px-3 py-2 mb-2">Campus Helpdesk & Inquiries</span>
        <h1 class="display-4 fw-bold font-serif mb-2">Contact & Campus Visit</h1>
        <p class="text-light-muted fs-6 mb-0">Get in touch with our academic admissions office, research cell, or schedule a guided campus tour.</p>
    </div>
</div>

<!-- 1. Contact Information & Enquiry Form -->
<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="row g-5">
            <!-- Left: Contact Details & Department Directory -->
            <div class="col-lg-5" data-aos="fade-right">
                <span class="section-tag">Direct Lines</span>
                <h2 class="section-title mb-4">We're Here to Help</h2>

                <div class="d-flex flex-column gap-3 mb-4">
                    <div class="p-3 rounded-3 bg-light d-flex align-items-start gap-3 border">
                        <div class="univ-crest" style="width:44px; height:44px; font-size:18px;">
                            <i class="fa-solid fa-location-dot"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-navy mb-1">Main University Campus</h6>
                            <p class="text-secondary small mb-0">Knowledge Boulevard, Institutional Area, Sector 62, New Delhi - 110025, India.</p>
                        </div>
                    </div>

                    <div class="p-3 rounded-3 bg-light d-flex align-items-start gap-3 border">
                        <div class="univ-crest" style="width:44px; height:44px; font-size:18px;">
                            <i class="fa-solid fa-phone"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-navy mb-1">Telephone & Helpdesk</h6>
                            <p class="text-secondary small mb-0">+91 (011) 2876-5400 (Admissions)</p>
                            <p class="text-secondary small mb-0">+91 (011) 2876-5401 (Registrar Office)</p>
                        </div>
                    </div>

                    <div class="p-3 rounded-3 bg-light d-flex align-items-start gap-3 border">
                        <div class="univ-crest" style="width:44px; height:44px; font-size:18px;">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-navy mb-1">Official Email Addresses</h6>
                            <p class="text-secondary small mb-0">admissions@apexuniversity.edu.in</p>
                            <p class="text-secondary small mb-0">registrar@apexuniversity.edu.in</p>
                        </div>
                    </div>

                    <div class="p-3 rounded-3 bg-light d-flex align-items-start gap-3 border">
                        <div class="univ-crest" style="width:44px; height:44px; font-size:18px;">
                            <i class="fa-solid fa-clock"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-navy mb-1">Office Visiting Hours</h6>
                            <p class="text-secondary small mb-0">Monday – Saturday: 09:00 AM – 05:00 PM</p>
                            <p class="text-secondary small mb-0">Closed on Sundays & Gazetted Public Holidays</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Interactive Contact Form -->
            <div class="col-lg-7" data-aos="fade-left">
                <div class="univ-card p-4 p-md-5">
                    <span class="section-tag mb-2">Send an Enquiry</span>
                    <h3 class="fw-bold text-navy mb-4 font-serif">Leave Us a Message</h3>

                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success d-flex align-items-center gap-3 p-3 rounded-3 mb-4">
                            <i class="fa-solid fa-circle-check fs-2 text-success"></i>
                            <div><?php echo $success; ?></div>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger d-flex align-items-center gap-3 p-3 rounded-3 mb-4">
                            <i class="fa-solid fa-circle-exclamation fs-3 text-danger"></i>
                            <div><?php echo $error; ?></div>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="contact.php">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-navy">Your Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" placeholder="e.g. Sunny Kumar" required value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-navy">Email Address <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" placeholder="e.g. sunny@gmail.com" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-navy">Contact Phone Number</label>
                                <input type="tel" name="phone" class="form-control" placeholder="e.g. +91 98765 43210" value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-navy">Subject / Nature of Inquiry <span class="text-danger">*</span></label>
                                <input type="text" name="subject" class="form-control" placeholder="e.g. BCA Admission Eligibility 2026" required value="<?php echo isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : ''; ?>">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold text-navy">Detailed Message / Question <span class="text-danger">*</span></label>
                                <textarea name="message" rows="4" class="form-control" placeholder="Please elaborate your query or requirements..." required><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                            </div>
                            <div class="col-12 text-end mt-4">
                                <button type="submit" name="send_message" class="btn btn-gold btn-lg px-5">
                                    <i class="fa-solid fa-paper-plane me-2"></i> Submit Inquiry Message
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 2. Google Maps Location Section -->
<section class="py-5 bg-light">
    <div class="container py-2 text-center" data-aos="fade-up">
        <span class="section-tag">Campus Navigation</span>
        <h2 class="section-title mb-4">Locate Our Campus on Google Maps</h2>
        <div class="rounded-4 overflow-hidden shadow-lg border" style="height: 450px;">
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d112061.76174828135!2d77.2089851!3d28.6324291!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390cfd5b347eb62d%3A0x52c2b7494e204dce!2sNew%20Delhi%2C%20Delhi!5e0!3m2!1sen!2sin!4v1700000000000!5m2!1sen!2sin" 
                width="100%" 
                height="100%" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy" 
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </div>
</section>

<?php include("includes/public_footer.php"); ?>
