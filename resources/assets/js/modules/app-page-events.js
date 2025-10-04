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

$("#editServiceSelect").select2({
    dropdownParent: $('#editEventModal'),
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

const editButton = document.getElementById("editButton");
if (editButton) {
    editButton.addEventListener("click", (event) => {
        handleEventUpdate();
    });
}

// Detectar clic en botón Editar
document.addEventListener('click', (e) => {
    const btn = e.target.closest('.edit-btn'); // busca si se hizo clic en un botón con esa clase
    if (btn) {
        const id = btn.getAttribute('data-id'); // recupera el atributo
        getEventEdit(id);
    }
});

// Detectar clic en botón Eliminar
document.addEventListener('click', (e) => {
    const deleteButton = e.target.closest('.delete-btn'); // busca si se hizo clic en un botón con esa clase
    if (deleteButton) {
        const id = deleteButton.getAttribute('data-id'); // recupera el atributo
        // Lógica para eliminar usuario
        Swal.fire({
            title: '¿Estás seguro?',
            text: '¡No podrás revertir esto!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Si, eliminar!',
            customClass: {
                confirmButton: 'btn btn-primary me-2 waves-effect waves-light',
                cancelButton: 'btn btn-label-secondary waves-effect waves-light'
            },
            buttonsStyling: false,
            preConfirm: _ => {
                // Llamar al servicio de eliminación
                return fetch(`${API_BASE_URL}/event/${id}`, {
                    method: 'DELETE',
                    headers: {
                        "Content-Type": "application/json",
                        "Authorization": `Bearer ${localStorage.getItem("token")}`
                    }
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Error al eliminar el evento.');
                        }
                        return response.json();
                    })
                    .then(data => {
                        showMessage("Evento eliminado con éxito.", "success");
                        // Recargar la tabla
                        loadDataTable();
                    })
                    .catch(error => {
                        showMessage("Error al eliminar la categoría.", "error");
                    });
            }
        }).then(function (result) { });
    }
});

async function getEventEdit(id) {
    const token = localStorage.getItem("token");
    try {
        const response = await fetch(`${API_BASE_URL}/event/${id}`, {
            method: 'GET',
            headers: {
                "Content-Type": "application/json",
                "Authorization": `Bearer ${token}`
            }
        });

        const result = await response.json();

        if (!response.ok) {
            const message = result.message || "Error al obtener los datos del servicio.";
            showMessage(message, "error");
            return null;
        }

        populateEditModal(result.data);
        return null;
    } catch (error) {
        showMessage("Error de conexión con el servidor.", "error");
        return null;
    }
}

async function populateEditModal(data) {
    if (!data) return;
    document.getElementById("editId").value = data.id;
    document.getElementById("editTitle").value = data.title;
    document.getElementById("editDescription").value = data.description;
    document.getElementById("editEventDate").value = data.event_date;
    document.getElementById("editStartTime").value = data.start_time;
    document.getElementById("editEndTime").value = data.end_time;
    document.getElementById("editEventAddress").value = data.event_address;
    const editServiceSelect = document.getElementById("editServiceSelect");
    if (editServiceSelect) {
        // Limpia las opciones seleccionadas previamente
        $(editServiceSelect).val(null).trigger('change');
        // Selecciona las nuevas opciones basadas en los servicios del evento
        const serviceIds = data.services.map(service => service.id);
        $(editServiceSelect).val(serviceIds).trigger('change');
    }
    const editStatus = document.getElementById("editStatus");
    if (editStatus) {
        editStatus.value = data.status;
    }
    const editEventModal = document.getElementById('editEventModal');
    const modalInstance = new bootstrap.Modal(editEventModal);
    modalInstance.show();
}

async function handleEventUpdate() {
    const editId = document.getElementById("editId");
    const editTitle = document.getElementById("editTitle");
    const editDescription = document.getElementById("editDescription");
    const editEventDate = document.getElementById("editEventDate");
    const editStartTime = document.getElementById("editStartTime");
    const editEndTime = document.getElementById("editEndTime");
    const editEventAddress = document.getElementById("editEventAddress");
    const editServiceSelect = document.getElementById("editServiceSelect");
    const editStatus = document.getElementById("editStatus");
    if (!editId || !editTitle || !editDescription || !editEventDate || !editStartTime || !editEndTime || !editEventAddress || !editServiceSelect || !editStatus) {
        return showMessage("Formulario incompleto.", "error");
    }
    const eventData = {
        title: editTitle.value,
        description: editDescription.value,
        event_date: editEventDate.value,
        start_time: editStartTime.value,
        end_time: editEndTime.value,
        event_address: editEventAddress.value,
        services: Array.from(editServiceSelect.selectedOptions).map(option => option.value),
        status: editStatus.value
    };
    updateEvent(editId.value, eventData);
}

async function updateEvent(id, eventData) {
    const token = localStorage.getItem("token");
    try {
        const response = await fetch(`${API_BASE_URL}/event/${id}`, {
            method: 'PUT',
            headers: {
                "Content-Type": "application/json",
                "Authorization": `Bearer ${token}`
            },
            body: JSON.stringify(eventData)
        });
        const result = await response.json();
        if (!response.ok) {
            const message = result.message || "Error al actualizar el evento.";
            return showMessage(message, "error");
        }
        // Éxito
        showMessage("Evento actualizado con éxito.", "success");
        // Cerrar el modal
        const editModal = document.getElementById('editEventModal');
        const modal = bootstrap.Modal.getInstance(editModal);
        modal.hide();
        // Recargar la tabla
        loadDataTable();
    } catch (error) {
        console.error('Error updating event:', error);
        showMessage("Error de conexión con el servidor.", "error");
        return;
    }
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
                { data: 'status_description' },
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
        showMessage("Error de conexión con el servidor.", "error");
    }
}


// Función para cargar las categorías en el select del modal
async function getCategoriesServices() {
    const createServiceSelect = document.getElementById('createServiceSelect');
    const editServiceSelect = document.getElementById('editServiceSelect');
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
            const optgroup1 = document.createElement("optgroup");
            optgroup1.label = category.name;

            const optgroup2 = document.createElement("optgroup");
            optgroup2.label = category.name;

            // Recorrer servicios dentro de cada categoría
            category.services.forEach(service => {
                const option1 = document.createElement("option");
                option1.value = service.id;
                option1.textContent = `${service.title} - S/${service.price}`;

                const option2 = document.createElement("option");
                option2.value = service.id;
                option2.textContent = `${service.title} - S/${service.price}`;

                optgroup1.appendChild(option1);
                optgroup2.appendChild(option2);
            });

            createServiceSelect.appendChild(optgroup1);
            editServiceSelect.appendChild(optgroup2);
        });

        // Si usas Select2, refrescarlo
        if ($(createServiceSelect).data('select2')) {
            $(createServiceSelect).trigger('change');
        }

        // Si usas Select2, refrescarlo
        if ($(editServiceSelect).data('select2')) {
            $(editServiceSelect).trigger('change');
        }

    } catch (error) {
        console.error('Error fetching categories:', error);
        showMessage("Error de conexión con el servidor.", "error");
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
        showMessage("Error de conexión con el servidor.", "error");
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