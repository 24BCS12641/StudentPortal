<?php
/**
 * Admin - Manage Public Contact Inquiries
 * Apex Imperial University Portal
 */

include("../config/auth.php");
include("../config/db.php");
check_role('admin');

$page_title = "Manage Public Inquiries";

// Mark as Replied
if (isset($_GET['mark_replied'])) {
    $msg_id = (int)$_GET['mark_replied'];
    mysqli_query($con, "UPDATE contact_messages SET status='Replied' WHERE message_id='$msg_id'");
    set_flash("success", "Inquiry #$msg_id marked as Replied.");
    header("Location: manage_contacts.php");
    exit();
}

// Delete Message
if (isset($_GET['delete'])) {
    $del_id = (int)$_GET['delete'];
    mysqli_query($con, "DELETE FROM contact_messages WHERE message_id='$del_id'");
    set_flash("success", "Inquiry message deleted.");
    header("Location: manage_contacts.php");
    exit();
}

$messages = mysqli_query($con, "SELECT * FROM contact_messages ORDER BY message_id DESC");

include("../includes/header.php");
include("../includes/sidebar_admin.php");
include("../includes/navbar.php");
?>

<div class="card">
    <div class="card-header">
        <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
            <h3><i class="fa-solid fa-envelope-open-text"></i> Public Contact Inquiries & Feedback</h3>
            <span class="badge badge-primary"><?php echo safe_num_rows($messages); ?> Messages</span>
        </div>
    </div>

    <div class="card-body" style="padding:0;">
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Sender Name & Contact</th>
                        <th>Subject</th>
                        <th>Message Content</th>
                        <th>Status</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($messages && safe_num_rows($messages) > 0): ?>
                        <?php while ($m = safe_fetch_assoc($messages)): ?>
                            <tr>
                                <td><?php echo date('d M Y, h:i A', strtotime($m['submitted_at'])); ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($m['name']); ?></strong>
                                    <div style="font-size:12px; color:var(--text-muted);">
                                        <i class="fa-solid fa-envelope"></i> <?php echo htmlspecialchars($m['email']); ?>
                                        <?php if (!empty($m['phone'])): ?>
                                            &bull; <i class="fa-solid fa-phone"></i> <?php echo htmlspecialchars($m['phone']); ?>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td><span class="badge badge-primary"><?php echo htmlspecialchars($m['subject']); ?></span></td>
                                <td>
                                    <div style="font-size:13px; color:var(--text-secondary); max-width:320px; line-height:1.4;">
                                        <?php echo nl2br(htmlspecialchars($m['message'])); ?>
                                    </div>
                                </td>
                                <td>
                                    <?php if ($m['status'] === 'Replied'): ?>
                                        <span class="badge badge-success"><i class="fa-solid fa-check"></i> Replied</span>
                                    <?php else: ?>
                                        <span class="badge badge-warning"><i class="fa-solid fa-envelope"></i> Unread</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align:right;">
                                    <?php if ($m['status'] !== 'Replied'): ?>
                                        <a href="manage_contacts.php?mark_replied=<?php echo $m['message_id']; ?>" class="btn btn-outline btn-sm" title="Mark Replied">
                                            <i class="fa-solid fa-check"></i> Done
                                        </a>
                                    <?php endif; ?>
                                    <a href="mailto:<?php echo urlencode($m['email']); ?>?subject=Re:%20<?php echo urlencode($m['subject']); ?>" class="btn btn-primary btn-sm" title="Reply via Email">
                                        <i class="fa-solid fa-reply"></i>
                                    </a>
                                    <a href="manage_contacts.php?delete=<?php echo $m['message_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this inquiry?');">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="empty-state">No contact messages received.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include("../includes/footer.php"); ?>
