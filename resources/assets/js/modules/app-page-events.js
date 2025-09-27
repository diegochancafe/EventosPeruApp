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


// Función para cargar y mostrar la tabla de usuarios
async function loadDataTable() {
  const dataTableEvents = document.querySelector('.datatable-events');
  const token = localStorage.getItem("token");

  if (!dataTableEvents) return;

  try {
    // Primero traes la data
    const response = await fetch(API_BASE_URL + '/events', {
      method: 'GET',
      headers: {
        "Content-Type": "application/json",
        "Authorization": `Bearer ${token}`
      }
    });

    if (!response.ok) { return showMessage("Error al cargar los datos de usuarios.", "error"); }

    const result = await response.json();

    // Ahora sí inicializas el DataTable con la data
    new DataTable(dataTableEvents, {
      destroy: true, // Destruye cualquier instancia previa
      data: result.data ?? result, // depende de cómo te devuelve tu API
      columns: [
        { data: 'id' },
        { data: 'client.name' },
        { data: 'title' },
        { data: 'event_date' },
        // { data: 'start_time' },
        // { data: 'end_time' },
        { data: 'event_address' },
        { data: 'status' },
        {
          className: 'text-center',
          render: function (data, type, row) {
            return `
                            <button type="button" class="btn rounded-pill me-2 btn-primary edit-btn" data-id="${row.id}"><i class="icon-base ti tabler-pencil icon-22px"></i></button>
                            <button type="button" class="btn rounded-pill me-2 btn-danger delete-btn" data-id="${row.id}"><i class="icon-base ti tabler-trash icon-22px"></i></button>
                        `;
          }
        }
      ],
      pageLength: 10,
      layout: createDataTableLayout('createCategoryModal', 'Nuevo', [0, 1, 2])
    });

    // Estilos aplicados solo cuando ya existe la tabla
    applyCustomClasses();

  } catch (error) {
    console.log(error);
    showMessage("Error de conexión con el servidor ❌", "error");
  }
}


loadDataTable()