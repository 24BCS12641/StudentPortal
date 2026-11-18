<?php
/**
 * Admin - Manage Events
 * Apex Imperial University Portal
 */

include("../config/auth.php");
include("../config/db.php");
check_role('admin');

$page_title = "Manage Campus Events & Summits";

// Add or Edit Event
if (isset($_POST['save_event'])) {
    $event_id = (int)($_POST['event_id'] ?? 0);
    $title = escape($con, $_POST['title']);
    $description = escape($con, $_POST['description']);
    $event_date = escape($con, $_POST['event_date']);
    $event_time = escape($con, $_POST['event_time']);
    $location = escape($con, $_POST['location']);
    $organizer = escape($con, $_POST['organizer']);

    if (empty($title) || empty($event_date)) {
        set_flash("danger", "Event title and date are required.");
    } else {
        if ($event_id > 0) {
            mysqli_query($con, "
                UPDATE events 
                SET title='$title', description='$description', event_date='$event_date',
                    event_time='$event_time', location='$location', organizer='$organizer'
                WHERE event_id='$event_id'
            ");
            set_flash("success", "Event '$title' updated successfully!");
        } else {
            mysqli_query($con, "
                INSERT INTO events (title, description, event_date, event_time, location, organizer)
                VALUES ('$title', '$description', '$event_date', '$event_time', '$location', '$organizer')
            ");
            set_flash("success", "Campus event published successfully!");
        }
        header("Location: manage_events.php");
        exit();
    }
}

// Delete Event
if (isset($_GET['delete'])) {
    $del_id = (int)$_GET['delete'];
    mysqli_query($con, "DELETE FROM events WHERE event_id='$del_id'");
    set_flash("success", "Event deleted successfully.");
    header("Location: manage_events.php");
    exit();
}

$events = mysqli_query($con, "SELECT * FROM events ORDER BY event_date ASC");

include("../includes/header.php");
include("../includes/sidebar_admin.php");
include("../includes/navbar.php");
?>

<div class="card">
    <div class="card-header">
        <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
            <h3><i class="fa-solid fa-calendar-check"></i> Campus Events, Hackathons & Convocations</h3>
            <span class="badge badge-primary"><?php echo mysqli_num_rows($events); ?> Scheduled</span>
        </div>
        <button type="button" class="btn btn-primary btn-sm" onclick="openEventModal()">
            <i class="fa-solid fa-plus"></i> Schedule New Event
        </button>
    </div>

    <div class="card-body" style="padding:0;">
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Date & Time</th>
                        <th>Event Title</th>
                        <th>Location</th>
                        <th>Organizer</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($events && mysqli_num_rows($events) > 0): ?>
                        <?php while ($e = mysqli_fetch_assoc($events)): ?>
                            <tr>
                                <td>
                                    <strong><i class="fa-regular fa-calendar text-primary me-1"></i> <?php echo date('d M Y', strtotime($e['event_date'])); ?></strong>
                                    <div style="font-size:12px; color:var(--text-muted);"><?php echo htmlspecialchars($e['event_time']); ?></div>
                                </td>
                                <td>
                                    <div style="font-weight:700; color:var(--text-primary);"><?php echo htmlspecialchars($e['title']); ?></div>
                                    <div style="font-size:13px; color:var(--text-secondary); max-width:350px;">
                                        <?php echo htmlspecialchars(substr($e['description'], 0, 100)) . '...'; ?>
                                    </div>
                                </td>
                                <td><i class="fa-solid fa-location-dot text-danger me-1"></i> <?php echo htmlspecialchars($e['location']); ?></td>
                                <td><span class="badge badge-dark"><?php echo htmlspecialchars($e['organizer']); ?></span></td>
                                <td style="text-align:right;">
                                    <button type="button" class="btn btn-outline btn-sm" onclick='editEventModal(<?php echo json_encode($e); ?>)'>
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <a href="manage_events.php?delete=<?php echo $e['event_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this event?');">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="empty-state">No scheduled events found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Event Modal -->
<div id="eventModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.6); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:var(--radius-lg); padding:30px; max-width:600px; width:90%; box-shadow:var(--shadow-xl);">
        <h3 id="eModalTitle" style="font-weight:700; margin-bottom:15px; color:var(--text-primary);">Event Details</h3>
        <form method="POST">
            <input type="hidden" name="event_id" id="eId" value="0">
            <div class="form-group">
                <label class="form-label">Event Title <span class="required">*</span></label>
                <input type="text" name="title" id="eTitle" class="form-control" placeholder="e.g. Annual Tech Summit 2026" required>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Date <span class="required">*</span></label>
                    <input type="date" name="event_date" id="eDate" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Time Slot</label>
                    <input type="text" name="event_time" id="eTime" class="form-control" placeholder="10:00 AM - 04:00 PM" value="10:00 AM - 04:00 PM">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Location / Hall <span class="required">*</span></label>
                    <input type="text" name="location" id="eLocation" class="form-control" placeholder="Main University Auditorium" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Organizer / Club</label>
                    <input type="text" name="organizer" id="eOrganizer" class="form-control" placeholder="Student Council / GDSC" value="Student Council">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Event Description</label>
                <textarea name="description" id="eDesc" rows="3" class="form-control" placeholder="Brief outline, schedule, and guest speakers"></textarea>
            </div>
            <div style="display:flex; justify-content:flex-end; gap:12px; margin-top:20px;">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('eventModal').style.display='none'">Cancel</button>
                <button type="submit" name="save_event" class="btn btn-primary">Save Event</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEventModal() {
    document.getElementById('eId').value = '0';
    document.getElementById('eTitle').value = '';
    document.getElementById('eDate').value = '<?php echo date("Y-m-d", strtotime("+7 days")); ?>';
    document.getElementById('eTime').value = '10:00 AM - 04:00 PM';
    document.getElementById('eLocation').value = 'Main University Auditorium';
    document.getElementById('eOrganizer').value = 'Student Welfare Cell';
    document.getElementById('eDesc').value = '';
    document.getElementById('eModalTitle').textContent = 'Schedule New Event';
    document.getElementById('eventModal').style.display = 'flex';
}
function editEventModal(e) {
    document.getElementById('eId').value = e.event_id;
    document.getElementById('eTitle').value = e.title;
    document.getElementById('eDate').value = e.event_date;
    document.getElementById('eTime').value = e.event_time;
    document.getElementById('eLocation').value = e.location;
    document.getElementById('eOrganizer').value = e.organizer;
    document.getElementById('eDesc').value = e.description || '';
    document.getElementById('eModalTitle').textContent = 'Edit Event';
    document.getElementById('eventModal').style.display = 'flex';
}
</script>

<?php include("../includes/footer.php"); ?>
