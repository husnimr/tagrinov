@extends('layouts.main')

@section('content')
    <!-- FullCalendar CSS -->
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.2/main.min.css' rel='stylesheet' />
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.2/theme-bootstrap.min.css' rel='stylesheet' />

    <!-- FullCalendar JS -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.2/main.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.2/theme-bootstrap.min.js'></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            color: #333;
            background-color: #f8f9fa;
        }

        .banner {
            background-color: #00452C;
            color: #fff;
            padding: 2rem;
            text-align: center;
            margin-bottom: 2rem;
        }

        h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: #fff;
        }

        #calendar {
            margin: 0 auto;
        }

        .fc-toolbar {
            background-color: #00452C;
            color: #fff;
            padding: 1rem;
        }

        button.fc-button {
            background-color: #00452C !important;
            color: #fff !important;
            border-radius: 5px;
            padding: 0.5rem 1rem;
            margin-right: 0.5rem;
        }

        .fc-toolbar-title {
            font-size: 1.5rem;
        }

        .fc-daygrid-day,
        .fc-timegrid-slot {
            border: 1px solid #ddd;
            padding: 5px;
        }

        .fc-event {
            background-color: rgb(11, 128, 67) !important;
            color: #fff !important;
            border-radius: 5px;
            border: none;
        }

        .fc-event:hover {
            background-color: #00342a;
        }
        .fc-button {
        background-color: #fff !important;
        color: #000 !important;
        border: 1px solid #ddd;
    }

    .fc-button:hover {
        background-color: #f1f1f1 !important;
        color: #000 !important;
    }
    /* Hilangkan underline dari tanggal */
    .fc-daygrid-day-number {
        text-decoration: none !important;
        color: inherit !important;
    }
            /* Sesuaikan ukuran elemen di mobile */
    @media (max-width: 768px) {
        h3 {
            font-size: 1.2rem;
        }
        #calendar {
            font-size: 0.8rem; /* Sesuaikan ukuran font pada tampilan kalender */
        }
        .fc-toolbar-title {
            font-size: 1rem; /* Ukuran title pada mobile */
        }
        .fc-button {
            font-size: 0.75rem; /* Sesuaikan ukuran tombol */
            padding: 0.25rem 0.5rem;
        }
    }

    </style>
<div class="container">
    <div style="margin-top: 100px;">
    <div id="calendar"></div>

    <!-- Modal -->
    <div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-3">
                <div class="modal-header bg-light border-bottom-0">
                    <h5 class="modal-title fw-semibold" id="eventModalLabel">
                        <i class="bi bi-calendar-event me-2 text-success"></i>Detail Kunjungan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body px-4 pt-3 pb-0">
                    <div class="mb-3 d-flex align-items-start">
                        <i class="bi bi-person-fill me-3 text-primary"></i>
                        <div>
                            <div class="text-muted small">Nama Lengkap</div>
                            <div id="modalNama" class="fw-semibold"></div>
                        </div>
                    </div>
                    <div class="mb-3 d-flex align-items-start">
                        <i class="bi bi-clock-fill me-3 text-warning"></i>
                        <div>
                            <div class="text-muted small">Tanggal & Jam</div>
                            <div id="modalTanggal" class="fw-semibold"></div>
                        </div>
                    </div>
                    <div class="mb-3 d-flex align-items-start">
                        <i class="bi bi-building me-3 text-info"></i>
                        <div>
                            <div class="text-muted small">Asal Instansi</div>
                            <div id="modalAsal" class="fw-semibold"></div>
                        </div>
                    </div>
                    <div class="mb-3 d-flex align-items-start">
                        <i class="bi bi-people-fill me-3 text-secondary"></i>
                        <div>
                            <div class="text-muted small">Jenis Pengunjung</div>
                            <div id="modalJenis" class="fw-semibold"></div>
                        </div>
                    </div>
                    <div id="modalJumlah" class="mb-3 d-flex align-items-start" style="display: none;">
                        <i class="bi bi-person-lines-fill me-3 text-danger"></i>
                        <div>
                            <div class="text-muted small">Jumlah Orang</div> <!-- ini akan ikut disembunyikan -->
                            <div id="modalJumlahOrang" class="fw-semibold"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i> Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            locale: 'id',
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next',
                center: 'title', 
                right: 'today'
            },
            themeSystem: 'bootstrap',
            events: @json($event_data),
            editable: false,
            eventStartEditable: false,
            droppable: false, // Nonaktifkan event dropping
            eventColor: '#FFA500',
            eventTextColor: '#fff',
            views: {
                dayGridMonth: {
                    titleFormat: { year: 'numeric', month: 'long' }
                }
            },
            buttonText: {
                today: 'Hari Ini',
                month: 'Bulan',
                week: 'Minggu',
                day: 'Hari'
            },
            eventClick: function(info) {
                document.getElementById('modalNama').textContent = info.event.title;
                document.getElementById('modalAsal').textContent = info.event.extendedProps.asal_instansi;
                document.getElementById('modalJenis').textContent = info.event.extendedProps.jenis_pengunjung;

                // Tangani jumlah orang
                const jumlahOrang = info.event.extendedProps.jumlah_orang;
                const jumlahWrapper = document.getElementById('modalJumlah');
                const jumlahText = document.getElementById('modalJumlahOrang');

                if (jumlahOrang && parseInt(jumlahOrang) > 0) {
                    jumlahWrapper.style.display = 'flex';
                    jumlahText.textContent = jumlahOrang;
                } else {
                    jumlahWrapper.style.display = 'none';
                    jumlahText.textContent = '';
                }

                // Tanggal & waktu
                document.getElementById('modalTanggal').textContent = info.event.start.toLocaleDateString('id-ID', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                }) + ', ' + info.event.start.toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit'
                });

                // Tampilkan modal
                var myModal = new bootstrap.Modal(document.getElementById('eventModal'));
                myModal.show();
            },

            // Sesuaikan ukuran tombol saat layar di-resize
            windowResize: function(view) {
                if (window.innerWidth < 768) {
                    calendar.setOption('headerToolbar', {
                        left: 'prev,next',
                        center: 'title',
                        right: 'today'
                    });
                } else {
                    calendar.setOption('headerToolbar', {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    });
                }
            }
        });
        calendar.render();
    });
</script>


@endsection
