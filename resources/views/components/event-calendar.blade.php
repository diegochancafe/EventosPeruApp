{{-- Event Calendar Component using FullCalendar --}}
<div class="col-12" id="event-calendar-container">
  <div class="card h-100">
    <div class="card-header d-flex align-items-center justify-content-between">
      <div class="card-title mb-0">
        <h5 class="m-0 me-2">Calendario de Eventos</h5>
      </div>
    </div>

    <div class="card-body">
      <div id="event-calendar" style="min-height: 400px;"></div>
    </div>
  </div>

  <!-- Modal for event details -->
  <div class="modal fade" id="eventDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="eventDetailTitle">Evento</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p id="eventDetailDescription"></p>
          <p><strong>Cliente:</strong> <span id="eventDetailClient"></span></p>
          <p><strong>Estado:</strong> <span id="eventDetailStatus"></span></p>
          <div id="eventDetailServices"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

  {{-- FullCalendar CDN CSS/JS (v5.x bundle) --}}
  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
  {{-- FullCalendar locales (all locales) --}}
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales-all.min.js"></script>

  <script>
    ;(function () {
      // Use dynamic origin so it works with different hosts (dev or production)
      const API_BASE_URL = window.location.origin + '/api';

      function initCalendar() {
        const token = localStorage.getItem('token');
        const calendarEl = document.getElementById('event-calendar');
        if (!calendarEl) return;

        // Build calendar options. Use default bundle views (dayGrid) provided by main.min.js
        const calendarOpts = {
          // Set Spanish locale
          locale: 'es',
          headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
          },
          initialView: 'dayGridMonth',
          navLinks: true,
          selectable: true,
          height: 'auto',
        };

        // Event click handler (shows modal with details)
        calendarOpts.eventClick = function(info) {
          try {
            info.jsEvent.preventDefault();
            const ev = info.event;
            const props = ev.extendedProps || {};

            document.getElementById('eventDetailTitle').textContent = ev.title;
            document.getElementById('eventDetailDescription').textContent = props.description || '';
            document.getElementById('eventDetailClient').textContent = props.client || '';
            document.getElementById('eventDetailStatus').textContent = props.status || '';

            const servicesEl = document.getElementById('eventDetailServices');
            servicesEl.innerHTML = '';
            if (props.services && props.services.length) {
              const ul = document.createElement('ul');
              props.services.forEach(function(s) {
                const li = document.createElement('li');
                li.textContent = s.title + (s.category ? (' — ' + s.category) : '');
                ul.appendChild(li);
              });
              servicesEl.appendChild(ul);
            }

            const modalEl = document.getElementById('eventDetailModal');
            const bsModal = new bootstrap.Modal(modalEl);
            bsModal.show();
          } catch (e) {
            console.error('eventClick handler error:', e);
          }
        };

        // fetch events
        calendarOpts.events = function(fetchInfo, successCallback, failureCallback) {
          // Prefer protected endpoint if token exists
          // Note: /events-calendar is public in our routes
          const endpoint = API_BASE_URL + '/events-calendar';
          const headers = { 'Content-Type': 'application/json' };
          if (token) headers['Authorization'] = `Bearer ${token}`;

          fetch(endpoint, { method: 'GET', headers }).then(function(res) {
            if (!res.ok) {
              throw new Error('Error cargando eventos: ' + res.status);
            }
            return res.json();
          }).then(function(data) {
            console.log('Events fetched:', data);
            if (!Array.isArray(data)) {
              console.error('Expecting array from /events-calendar, got:', data);
              failureCallback(new Error('Invalid data format'));
              calendarEl.innerHTML = '<div class="text-center text-muted">No se pudieron cargar los eventos.</div>';
              return;
            }

            if (data.length === 0) {
              // show message but still render empty calendar
              if (!document.getElementById('events-empty-msg')) {
                calendarEl.insertAdjacentHTML('beforebegin', '<div id="events-empty-msg" class="mb-2">No hay eventos para mostrar.</div>');
              }
            }

            successCallback(data);
          }).catch(function(err) {
            console.error('Fetch calendar error:', err);
            calendarEl.innerHTML = '<div class="text-center text-danger">Error cargando calendario. Revisa la consola.</div>';
            failureCallback(err);
          });
        };

        // Initialize calendar
        try {
          const calendar = new FullCalendar.Calendar(calendarEl, calendarOpts);
          calendar.render();
        } catch (e) {
          console.error('FullCalendar init error:', e);
          calendarEl.innerHTML = '<div class="text-center text-danger">Error inicializando calendario. Revisa la consola.</div>';
        }
      }

      // wait for DOM ready
      document.addEventListener('DOMContentLoaded', initCalendar);
    })();
  </script>
</div>
