@extends('layouts.petugas')

@section('title', 'Jadwal & Riwayat - RisingCare')

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Jadwal & Riwayat</h1>
        <p class="text-gray-600">Kalender jadwal kunjungan dan riwayat tugas Anda</p>
    </div>
    <div class="flex flex-wrap items-center gap-3 text-xs font-medium bg-white px-4 py-2 rounded-xl shadow-sm border border-gray-100">
        <div class="flex items-center gap-1.5">
            <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span> Terjadwal
        </div>
        <div class="flex items-center gap-1.5">
            <span class="w-2.5 h-2.5 rounded-full bg-purple-600"></span> Proses
        </div>
        <div class="flex items-center gap-1.5">
            <span class="w-2.5 h-2.5 rounded-full bg-green-500"></span> Selesai
        </div>
        <div class="flex items-center gap-1.5">
            <span class="w-2.5 h-2.5 rounded-full bg-red-500"></span> Batal
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 md:p-6">
    <div id="calendar" class="custom-calendar"></div>
</div>

<!-- FullCalendar CSS & JS -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,listMonth'
            },
            views: {
                listMonth: { buttonText: 'List' }
            },
            locale: 'id',
            buttonText: {
                today: 'Hari Ini',
                month: 'Bulan',
                week: 'Minggu',
                day: 'Hari',
                list: 'List'
            },
            events: '{{ route("petugas.calendar.events") }}',
            eventClick: function(info) {
                if (info.event.url) {
                    window.location.href = info.event.url;
                    info.jsEvent.preventDefault();
                }
            },
            eventDidMount: function(info) {
                // Enhanced Tooltip
                let props = info.event.extendedProps;
                let tooltipContent = `Customer: ${props.customer_name}\nLayanan: ${props.service}\nStatus: ${props.status}\nWaktu: ${info.event.start.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}`;
                info.el.title = tooltipContent;
            },
            eventContent: function(arg) {
                let status = arg.event.extendedProps.status;
                let customerName = arg.event.extendedProps.customer_name;
                let icon = '';
                let statusClass = '';
                
                // Determine Icon & Class based on status
                if (status.toLowerCase().includes('completed') || status.toLowerCase().includes('selesai')) {
                    icon = '<i class="fas fa-check-circle"></i>';
                    statusClass = 'status-completed';
                } else if (status.toLowerCase().includes('cancelled') || status.toLowerCase().includes('dibatalkan')) {
                    icon = '<i class="fas fa-times-circle"></i>';
                    statusClass = 'status-cancelled';
                } else if (status.toLowerCase().includes('progress') || status.toLowerCase().includes('proses')) {
                    icon = '<i class="fas fa-spinner fa-spin"></i>';
                    statusClass = 'status-progress';
                } else if (status.toLowerCase().includes('pending')) {
                    icon = '<i class="fas fa-clock"></i>';
                    statusClass = 'status-pending';
                } else {
                    icon = '<i class="fas fa-calendar-alt"></i>';
                    statusClass = 'status-scheduled';
                }

                let time = arg.timeText;
                
                // Simplified content: Time + Icon + Customer Name (Truncated)
                let html = `
                    <div class="fc-event-custom-content ${statusClass}">
                        <div class="flex items-center gap-1 overflow-hidden">
                            <span class="text-[10px] font-bold opacity-75 whitespace-nowrap">${time}</span>
                            <span class="fc-event-icon text-[10px]">${icon}</span>
                            <span class="fc-event-title text-xs font-medium truncate" title="${customerName}">${customerName}</span>
                        </div>
                    </div>
                `;
                
                return { html: html };
            },
            height: 'auto',
        });
        calendar.render();
    });
</script>

<style>
    /* Custom Calendar Styling */
    .custom-calendar {
        font-family: 'Inter', sans-serif;
    }

    /* Header Toolbar */
    .fc-toolbar-title {
        font-size: 1.25rem !important;
        font-weight: 700;
        color: #1F2937;
    }
    
    .fc-button {
        border-radius: 0.5rem !important;
        font-weight: 500 !important;
        text-transform: capitalize !important;
        padding: 0.5rem 1rem !important;
        box-shadow: none !important;
    }

    .fc-button-primary {
        background-color: white !important;
        border-color: #E5E7EB !important;
        color: #374151 !important;
    }
    
    .fc-button-primary:hover {
        background-color: #F9FAFB !important;
        border-color: #D1D5DB !important;
        color: #111827 !important;
    }

    .fc-button-active {
        background-color: #0D9488 !important; /* Teal-600 */
        border-color: #0D9488 !important;
        color: white !important;
    }

    .fc-today-button {
        background-color: #F0FDFA !important;
        border-color: #CCFBF1 !important;
        color: #0F766E !important;
    }

    /* Calendar Grid */
    .fc-theme-standard td, .fc-theme-standard th {
        border-color: #F3F4F6;
    }

    .fc-col-header-cell {
        padding: 10px 0;
        background-color: #F9FAFB;
        color: #6B7280;
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .fc-daygrid-day-number {
        color: #374151;
        font-weight: 500;
        padding: 8px 12px !important;
    }

    .fc-day-today {
        background-color: #F0FDFA !important; /* Teal-50 */
    }

    /* Events */
    .fc-event {
        border: none !important;
        background: transparent !important;
        box-shadow: none !important;
        margin-bottom: 4px !important;
    }

    .fc-event-custom-content {
        padding: 2px 6px;
        border-radius: 4px;
        border-left: 3px solid;
        transition: all 0.2s;
        overflow: hidden;
    }

    .fc-event-custom-content:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    /* Remove old styles */
    .fc-event-time { display: none; }
    .fc-event-title-row { display: none; }

    /* Status Colors */
    .status-scheduled {
        background-color: #EFF6FF; /* Blue-50 */
        border-left-color: #3B82F6; /* Blue-500 */
        color: #1E40AF; /* Blue-800 */
    }

    .status-checked-in {
        background-color: #DBEAFE; /* Blue-100 */
        border-left-color: #2563EB; /* Blue-600 */
        color: #1E3A8A; /* Blue-900 */
    }

    .status-progress {
        background-color: #F3E8FF; /* Purple-100 */
        border-left-color: #9333EA; /* Purple-600 */
        color: #6B21A8; /* Purple-800 */
    }

    .status-pending {
        background-color: #FFF7ED; /* Orange-50 */
        border-left-color: #F59E0B; /* Orange-500 */
        color: #9A3412; /* Orange-800 */
    }

    .status-completed {
        background-color: #ECFDF5; /* Green-50 */
        border-left-color: #10B981; /* Green-500 */
        color: #065F46; /* Green-800 */
    }

    .status-cancelled {
        background-color: #FEF2F2; /* Red-50 */
        border-left-color: #EF4444; /* Red-500 */
        color: #991B1B; /* Red-800 */
        text-decoration: line-through;
        opacity: 0.7;
    }

    /* Mobile Adjustments */
    @media (max-width: 640px) {
        .fc-toolbar {
            flex-direction: column;
            gap: 1rem;
        }
        .fc-toolbar-title {
            font-size: 1rem !important;
        }
    }
</style>
@endsection
