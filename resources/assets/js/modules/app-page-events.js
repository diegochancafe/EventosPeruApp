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

$("#createServiceSelect").select2({
    dropdownParent: $('#createEventModal'),
    placeholder: "Selecciona tus servicios...",
    allowClear: true
});
// -- EVENTS
const createButton = document.getElementById("createButton");
if (createButton) {
    createButton.addEventListener("click", (event) => {
        handleEventCreation();
    });
}

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
            layout: createDataTableLayout('createEventModal', 'Nuevo', [0, 1, 2])
        });

        // Estilos aplicados solo cuando ya existe la tabla
        applyCustomClasses();

    } catch (error) {
        console.log(error);
        showMessage("Error de conexión con el servidor ❌", "error");
    }
}


// Función para cargar las categorías en el select del modal
async function getCategoriesServices() {
    const createServiceSelect = document.getElementById('createServiceSelect');
    const token = localStorage.getItem("token");
    if (!createServiceSelect) return;

    try {
        // Primero traes la data
        const response = await fetch(API_BASE_URL + '/categories/services', {
            method: 'GET',
            headers: {
                "Content-Type": "application/json",
                "Authorization": `Bearer ${token}`
            }
        });

        if (!response.ok) { return showMessage("Error al cargar las categorías.", "error"); }

        const result = await response.json();
        // Limpiar el select antes de llenarlo
        createServiceSelect.innerHTML = "";

        // Recorrer categorías
        result.data.forEach(category => {
            const optgroup = document.createElement("optgroup");
            optgroup.label = category.name;

            // Recorrer servicios dentro de cada categoría
            category.services.forEach(service => {
                const option = document.createElement("option");
                option.value = service.id;
                option.textContent = `${service.title} - S/${service.price}`;
                optgroup.appendChild(option);
            });

            createServiceSelect.appendChild(optgroup);
        });

        // Si usas Select2, refrescarlo
        if ($(createServiceSelect).data('select2')) {
            $(createServiceSelect).trigger('change');
        }

    } catch (error) {
        console.error('Error fetching categories:', error);
        showMessage("Error de conexión con el servidor ❌", "error");
    }
}

async function handleEventCreation() {
    const createTitle = document.getElementById("createTitle");
    const createDescription = document.getElementById("createDescription");
    const createEventDate = document.getElementById("createEventDate");
    const createStartTime = document.getElementById("createStartTime");
    const createEndTime = document.getElementById("createEndTime");
    const createEventAddress = document.getElementById("createEventAddress");
    const createServiceSelect = document.getElementById("createServiceSelect");

    if (!createTitle || !createDescription || !createEventDate || !createStartTime || !createEndTime || !createEventAddress || !createServiceSelect) {
        return showMessage("Formulario incompleto.", "error");
    }

    const token = localStorage.getItem("token");

    try {
        const eventData = {
            title: createTitle.value,
            description: createDescription.value,
            event_date: createEventDate.value,
            start_time: createStartTime.value,
            end_time: createEndTime.value,
            event_address: createEventAddress.value,
            services: Array.from(createServiceSelect.selectedOptions).map(option => option.value)
        };
        const response = await fetch(API_BASE_URL + '/event', {
            method: 'POST',
            headers: {
                "Content-Type": "application/json",
                "Authorization": `Bearer ${token}`
            },
            body: JSON.stringify(eventData)
        });

        const result = await response.json();

        if (!response.ok) {
            const errorMessage = result.message || "Error al crear la categoría.";
            return showMessage(errorMessage, "error");
        }

        // Si todo va bien
        showMessage("Categoría creada con éxito.", "success");

        // Cerrar modal
        const createEventModal = document.getElementById('createEventModal');
        const modalInstance = bootstrap.Modal.getInstance(createEventModal);
        modalInstance.hide();

        // Recargar tabla
        loadDataTable();

    } catch (error) {
        console.error('Error creating event:', error);
        showMessage("Error de conexión con el servidor ❌", "error");
    }

}

// Función para mostrar mensajes en pantalla
function showMessage(message, type) {
    if (type === "success") {
        notify.success(message);
    } else if (type === "error") {
        notify.error(message);
    }
}

// Carga inicial
getCategoriesServices();
loadDataTable();