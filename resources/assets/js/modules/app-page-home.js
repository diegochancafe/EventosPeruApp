/**
 *  Pages Home
 */
import { createDataTableLayout, applyCustomClasses } from '../utils/datatable-utils.js';
import { Notyf } from 'notyf';
import 'notyf/notyf.min.css'; // for React, Vue and Svelte

'use strict';

// VARIABLES
const notify = new Notyf();
const API_BASE_URL = "http://localhost:8000/api";


/* ---------------------------------------------------------
    FETCH DATA: Services Usage
--------------------------------------------------------- */
async function getServicesUsage() {
  const token = localStorage.getItem("token");

  try {
    const res = await fetch(API_BASE_URL + '/home/services-usage', {
      method: 'GET',
      headers: {
        "Content-Type": "application/json",
        "Authorization": `Bearer ${token}`
      }
    });

    if (!res.ok) throw new Error("Error al obtener servicios");

    const result = await res.json();
    console.log("Services Usage:", result);

    renderServicesUsageDonut(result.data);

  } catch (error) {
    console.error(error);
    notify.error("Error al cargar estadísticas.");
  }
}

/* ---------------------------------------------------------
    RENDER DONUT CHART
--------------------------------------------------------- */
async function renderServicesUsageDonut(services) {
  const chartEl = document.querySelector('#servicesUsageDonut');
  if (!chartEl) return;
  // Crear labels y series
  // Validación: acceso correcto según response
  const labels = services?.labels ?? [];
  const series = services?.series ?? [];

  console.log("Labels: ", labels);
  console.log("Series: ", series);

  const total = series.reduce((a, b) => a + b, 0);

  const chartConfig = {
    chart: {
      height: 420,
      parentHeightOffset: 0,
      type: 'donut'
    },

    labels: labels,
    series: series,

    colors: [
      '#4A90E2', '#50E3C2', '#F5A623', '#D0021B',
      '#7ED321', '#BD10E0', '#B8E986', '#417505',
      '#F8E71C', '#9013FE'
    ],

    stroke: { width: 0 },

    dataLabels: {
      enabled: false
    },

    legend: {
      show: true,
      position: 'bottom',
      itemMargin: { vertical: 5 },
      fontSize: '13px'
    },

    tooltip: {
      y: {
        formatter: val => `${val} usos`
      }
    },

    plotOptions: {
      pie: {
        donut: {
          size: '78%',
          labels: {
            show: true,
            total: {
              show: true,
              label: 'Total',
              formatter: () => total
            }
          }
        }
      }
    }
  };

  const chart = new ApexCharts(chartEl, chartConfig);
  chart.render();
}

async function getEventsTotalByStatus() {
  const token = localStorage.getItem("token");

  const COLORS = {
    pending: "bg-warning",
    confirmed: "bg-info",
    canceled: "bg-danger",
    finished: "bg-success"
  };

  const LABELS = {
    pending: "Pendiente",
    confirmed: "Confirmado",
    canceled: "Cancelado",
    finished: "Finalizado"
  };

  try {
    const response = await fetch(API_BASE_URL + '/home/events-total-by-status', {
      method: 'GET',
      headers: {
        "Content-Type": "application/json",
        "Authorization": `Bearer ${token}`
      }
    });

    if (!response.ok) throw new Error("Error en la petición");

    const { data, percentages } = await response.json();

    console.log("Eventos Totales:", data);
    console.log("Porcentajes:", percentages);

    // ---- DOM Elements
    const labelsContainer = document.getElementById("events-labels");
    const progressContainer = document.getElementById("events-progress");
    const tableBody = document.getElementById("events-table-body");

    labelsContainer.innerHTML = "";
    progressContainer.innerHTML = "";
    tableBody.innerHTML = "";

    // ----  Generate UI
    Object.keys(data).forEach(status => {

      // Definir un mínimo visual
      const MIN_WIDTH = 2; // puedes poner 2, 3, 5... lo que prefieras

      const barWidth = percentages[status] === 0
        ? MIN_WIDTH
        : percentages[status];

      // PROGRESS BAR
      progressContainer.innerHTML += `
        <div class="progress-bar ${COLORS[status]}" 
            role="progressbar" 
            style="width: ${barWidth}%">
          ${percentages[status]}%
        </div>
      `;

      // TABLE ROWS
      tableBody.innerHTML += `
        <tr>
          <td class="w-50">
            <div class="d-flex align-items-center">
              <span class="status-dot ${COLORS[status]} me-2"></span>
              <h6 class="mb-0 fw-normal">${LABELS[status]}</h6>
            </div>
          </td>

          <td class="text-end">
            <h6 class="mb-0">S/ ${data[status].toFixed(2)}</h6>
          </td>

          <td class="text-end">
            <span>${percentages[status]}%</span>
          </td>
        </tr>
      `;

    });

  } catch (error) {
    console.error("Error cargando eventos:", error);
    notify.error("Error cargando estadísticas");
  }
}


// Call the function to fetch events total by status
getEventsTotalByStatus();

// Call the function to fetch services usage data
getServicesUsage();