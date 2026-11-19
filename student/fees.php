<?php
/**
 * Student Fees & Financial Ledger
 * Apex Imperial University Portal
 */

include("../config/auth.php");
include("../config/db.php");
check_role('student');

$page_title = "Tuition & Academic Fees";
$user_id = $_SESSION['user_id'];

// Get student details
$st_q = mysqli_query($con, "
    SELECT s.*, u.name, u.email 
    FROM students s
    JOIN users u ON s.user_id = u.id
    WHERE s.user_id='$user_id'
    LIMIT 1
");
$student = ($st_q && mysqli_num_rows($st_q) > 0) ? mysqli_fetch_assoc($st_q) : [];
$student_id = $student['student_id'] ?? 0;

// Fetch fee records
$fees_q = mysqli_query($con, "
    SELECT * FROM fees 
    WHERE student_id='$student_id' 
    ORDER BY semester DESC
");

// Total calculations
$total_billed = 0;
$total_paid = 0;
$total_due = 0;
$fees_list = [];

if ($fees_q) {
    while ($f = mysqli_fetch_assoc($fees_q)) {
        $fees_list[] = $f;
        $total_billed += (float)$f['total_amount'];
        $total_paid += (float)$f['paid_amount'];
    }
}
$total_due = max(0, $total_billed - $total_paid);

include("../includes/header.php");
include("../includes/sidebar_student.php");
include("../includes/navbar.php");
?>

<!-- Financial Summary KPIs -->
<div class="stats-grid">
    <div class="stat-card primary">
        <div class="stat-info">
            <div class="stat-label">Total Billed Tuition</div>
            <div class="stat-value">₹<?php echo number_format($total_billed); ?></div>
            <div class="stat-subtext">Cumulative academic fees</div>
        </div>
        <div class="stat-icon-wrapper" style="background:#e0e7ff; color:#4f46e5;">
            <i class="fa-solid fa-file-invoice-dollar"></i>
        </div>
    </div>

    <div class="stat-card success">
        <div class="stat-info">
            <div class="stat-label">Total Paid Amount</div>
            <div class="stat-value">₹<?php echo number_format($total_paid); ?></div>
            <div class="stat-subtext"><i class="fa-solid fa-check text-success"></i> Cleared & verified</div>
        </div>
        <div class="stat-icon-wrapper" style="background:#d1fae5; color:#10b981;">
            <i class="fa-solid fa-receipt"></i>
        </div>
    </div>

    <div class="stat-card <?php echo ($total_due > 0) ? 'danger' : 'info'; ?>">
        <div class="stat-info">
            <div class="stat-label">Outstanding Balance</div>
            <div class="stat-value">₹<?php echo number_format($total_due); ?></div>
            <div class="stat-subtext"><?php echo ($total_due == 0) ? 'No pending dues &bull; All clear' : 'Payment due'; ?></div>
        </div>
        <div class="stat-icon-wrapper" style="<?php echo ($total_due > 0) ? 'background:#fee2e2; color:#ef4444;' : 'background:#cffafe; color:#06b6d4;'; ?>">
            <i class="fa-solid fa-wallet"></i>
        </div>
    </div>

    <div class="stat-card warning">
        <div class="stat-info">
            <div class="stat-label">Fee Clearance Status</div>
            <div class="stat-value" style="font-size:22px;"><?php echo ($total_due == 0) ? 'CLEARED' : 'PENDING'; ?></div>
            <div class="stat-subtext">Semester 4 Clearance</div>
        </div>
        <div class="stat-icon-wrapper">
            <i class="fa-solid fa-stamp"></i>
        </div>
    </div>
</div>

<!-- Fee Ledger Table -->
<div class="card">
    <div class="card-header">
        <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
            <h3><i class="fa-solid fa-receipt"></i> Academic Fee Ledger & Transaction History</h3>
            <span class="badge badge-primary"><?php echo count($fees_list); ?> Invoices</span>
        </div>
        <button type="button" onclick="window.print()" class="btn btn-outline btn-sm">
            <i class="fa-solid fa-print"></i> Print Statement
        </button>
    </div>

    <div class="card-body" style="padding:0;">
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Invoice / Ref</th>
                        <th>Semester & Fee Description</th>
                        <th>Billed Amount</th>
                        <th>Paid Amount</th>
                        <th>Due Date</th>
                        <th>Payment Status</th>
                        <th style="text-align:right;">Receipt</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($fees_list) > 0): ?>
                        <?php foreach ($fees_list as $fee): ?>
                            <tr>
                                <td>
                                    <strong>#FEE-<?php echo $fee['fee_id']; ?></strong>
                                    <?php if (!empty($fee['transaction_id'])): ?>
                                        <div style="font-size:11px; color:var(--text-muted); font-family:monospace;"><?php echo htmlspecialchars($fee['transaction_id']); ?></div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong>Semester <?php echo $fee['semester']; ?> Tuition</strong>
                                    <div style="font-size:12px; color:var(--text-muted);"><?php echo htmlspecialchars($fee['fee_type']); ?></div>
                                </td>
                                <td><strong>₹<?php echo number_format($fee['total_amount'], 2); ?></strong></td>
                                <td><span style="color:#10b981; font-weight:700;">₹<?php echo number_format($fee['paid_amount'], 2); ?></span></td>
                                <td><?php echo date('d M Y', strtotime($fee['due_date'])); ?></td>
                                <td>
                                    <?php if ($fee['status'] === 'Paid'): ?>
                                        <span class="badge badge-success"><i class="fa-solid fa-check"></i> Paid Full</span>
                                    <?php elseif ($fee['status'] === 'Overdue'): ?>
                                        <span class="badge badge-danger"><i class="fa-solid fa-triangle-exclamation"></i> Overdue</span>
                                    <?php else: ?>
                                        <span class="badge badge-warning"><i class="fa-solid fa-clock"></i> Pending</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align:right;">
                                    <button type="button" onclick="window.print()" class="btn btn-outline btn-sm" title="Print Fee Receipt">
                                        <i class="fa-solid fa-receipt"></i> Receipt
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="7" class="empty-state">No fee records found for this student.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include("../includes/footer.php"); ?>
