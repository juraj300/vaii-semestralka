<?php
/** @var \Framework\Support\LinkGenerator $link */
?>

<div class="fade-in">
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h1>Follow-up Calendar</h1>
            <p class="text-muted">Manage your appointments and stay aware of public holidays.</p>
        </div>
    </div>

    <div class="card p-3 shadow-sm bg-dark border-secondary">
        <div id="calendar"></div>
    </div>
</div>

<!-- Appointment Detail Modal -->
<div class="modal fade" id="appointmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-dark border-secondary">
            <div class="modal-header border-secondary">
                <h5 class="modal-title" id="appTitle">Appointment Info</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="appDescription" class="text-light mb-3"></p>
                <div class="small text-muted">
                    <i class="bi bi-calendar-check me-2"></i> <span id="appTime"></span>
                </div>
            </div>
            <div class="modal-footer border-secondary">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- FullCalendar Library -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>

<style>
    :root {
        --fc-border-color: rgba(255,255,255,0.1);
        --fc-daygrid-event-dot-width: 8px;
        --fc-page-bg-color: transparent;
    }
    .fc {
        color: #fff;
        background: transparent;
    }
    .fc .fc-toolbar-title {
        font-size: 1.5rem;
        font-weight: 700;
        background: linear-gradient(to bottom right, #fff, #94a3b8);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .fc .fc-button-primary {
        background-color: var(--primary-color);
        border-color: transparent;
        transition: all 0.2s;
    }
    .fc .fc-button-primary:hover {
        background-color: var(--primary-hover);
        transform: translateY(-1px);
    }
    .fc .fc-button-primary:disabled {
        background-color: #334155;
    }
    .fc .fc-col-header-cell-cushion {
        color: #94a3b8;
        padding: 10px 0;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.05em;
    }
    .fc td, .fc th {
        border-color: var(--fc-border-color) !important;
    }
    .fc-theme-standard td, .fc-theme-standard th {
        border: 1px solid var(--fc-border-color);
    }
    .fc .fc-day-today {
        background: rgba(59, 130, 246, 0.05) !important;
    }
    .fc-event {
        cursor: pointer;
        padding: 2px 5px;
        border-radius: 4px;
        font-size: 0.85rem;
        border: none;
    }
    .fc-event:hover {
        filter: brightness(1.2);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var appModal = new bootstrap.Modal(document.getElementById('appointmentModal'));
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            themeSystem: 'standard',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,listWeek'
            },
            events: '<?= $link->url('calendar.events') ?>',
            eventClick: function(info) {
                document.getElementById('appTitle').innerText = info.event.title;
                document.getElementById('appDescription').innerText = info.event.extendedProps.description || 'No additional details provided.';
                document.getElementById('appTime').innerText = info.event.start ? info.event.start.toLocaleString() : 'N/A';
                appModal.show();
            },
            firstDay: 1, // Week starts on Monday
            height: 'auto'
        });
        calendar.render();
    });
</script>
