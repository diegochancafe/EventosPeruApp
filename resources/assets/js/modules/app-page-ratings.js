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

// -- EVENTS
const createButton = document.getElementById("createRatingButton");
if (createButton) {
    createButton.addEventListener("click", (event) => {
        handleRatingCreation();
    });
}

const editButton = document.getElementById("editRatingButton");
if (editButton) {
    editButton.addEventListener("click", (event) => {
        handleRatingUpdate();
    });
}

// Limpiar el modal al cerrarlo
document.addEventListener('hidden.bs.modal', function (event) {
    if (event.target.id === 'createRatingModal') {
        event.target.querySelectorAll('input, select').forEach(el => {
            el.value = "";
        });
        if (document.activeElement) {
            document.activeElement.blur();
        }
    }
});

// Detectar clic en botón Editar
document.addEventListener('click', (e) => {
    const btn = e.target.closest('.edit-btn');
    if (btn) {
        const id = btn.getAttribute('data-id');
        getRatingEdit(id);
    }
});

// Detectar clic en botón Eliminar
document.addEventListener('click', (e) => {
    const deleteButton = e.target.closest('.delete-btn');
    if (deleteButton) {
        const id = deleteButton.getAttribute('data-id');
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
                return fetch(`${API_BASE_URL}/rating/${id}`, {
                    method: 'DELETE',
                    headers: {
                        "Content-Type": "application/json",
                        "Authorization": `Bearer ${localStorage.getItem("token")}`
                    }
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Error al eliminar la calificación.');
                        }
                        return response.json();
                    })
                    .then(data => {
                        showMessage("Calificación eliminada con éxito.", "success");
                        loadRatingsTable();
                    })
                    .catch(error => {
                        showMessage("Error al eliminar la calificación.", "error");
                    });
            }
        }).then(function (result) { });
    }
});

function showMessage(message, type) {
    if (type === "success") {
        notify.success(message);
    } else if (type === "error") {
        notify.error(message);
    }
}

// Cargar y mostrar la tabla de calificaciones
async function loadRatingsTable() {
    const dataTableRatings = document.querySelector('.datatable-ratings');
    const token = localStorage.getItem("token");
    if (!dataTableRatings) return;
    try {
        const response = await fetch(API_BASE_URL + '/ratings', {
            method: 'GET',
            headers: {
                "Content-Type": "application/json",
                "Authorization": `Bearer ${token}`
            }
        });
        if (response.status === 403) {
            $("#content-not-authorized").css("display", "block");
            $("#content-authorized").css("display", "none");
            return;
        }
        if (!response.ok) { return showMessage("Error al cargar las calificaciones.", "error"); }
        const result = await response.json();
        // Mostrar contenido autorizado y ocultar no autorizado
        $("#content-not-authorized").css("display", "none");
        $("#content-authorized").css("display", "block");
        // Ahora sí inicializas el DataTable con la data
        new DataTable(dataTableRatings, {
            destroy: true, // Destruye cualquier instancia previa
            data: result.data ?? result, // depende de cómo te devuelve tu API
            columns: [
                { data: 'id' },
                { data: 'client.name', title: 'Usuario' },
                { data: 'event.title', title: 'Evento' },
                { data: 'score', title: 'Puntaje' },
                { data: 'comment', title: 'Comentario' },
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
            layout: createDataTableLayout('createRatingModal', 'Nuevo', [0, 1, 2, 3, 4, 5])
        });
        applyCustomClasses();
    } catch (error) {
        console.log(error);
        showMessage("Error de conexión con el servidor.", "error");
    }
}

// Crear nueva calificación
async function handleRatingCreation() {
    // Obtener valores del modal
    const eventId = document.getElementById('createEvent').value;
    const score = parseInt(document.getElementById('createScore').value, 10);
    const comment = document.getElementById('createComment').value.trim();
    const token = localStorage.getItem("token");

    // Validar campos obligatorios
    if (!eventId || !score) {
        return showMessage("Por favor, completa todos los campos obligatorios.", "error");
    }

    // Validar score entre 1 y 5
    if (score < 1 || score > 5) {
        return showMessage("El puntaje debe estar entre 1 y 5.", "error");
    }

    try {
        const response = await fetch(`${API_BASE_URL}/rating`, {
            method: 'POST',
            headers: {
                "Content-Type": "application/json",
                "Authorization": `Bearer ${token}`
            },
            body: JSON.stringify({
                event_id: eventId,
                score: score,
                comment: comment
            })
        });

        const result = await response.json();

        if (!response.ok) {
            const errorMessage = result.message || "Error al crear la calificación.";
            return showMessage(errorMessage, "error");
        }

        // Éxito
        showMessage("Calificación creada con éxito.", "success");

        // Cerrar modal
        const createRatingModal = document.getElementById('createRatingModal');
        const modalInstance = bootstrap.Modal.getInstance(createRatingModal);
        modalInstance.hide();

        // Limpiar inputs del modal
        ['createEventId', 'createScore', 'createComment'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.value = '';
        });

        // Recargar tabla
        loadRatingsTable();

    } catch (error) {
        console.error(error);
        showMessage("Error de conexión con el servidor.", "error");
    }
}


// Obtener datos de una calificación por ID y llenar el modal de edición
async function getRatingEdit(id) {
    const token = localStorage.getItem("token");
    try {
        const response = await fetch(`${API_BASE_URL}/rating/${id}`, {
            method: 'GET',
            headers: {
                "Content-Type": "application/json",
                "Authorization": `Bearer ${token}`
            }
        });
        const result = await response.json();
        if (!response.ok) {
            const message = result.message || "Error al obtener la calificación.";
            showMessage(message, "error");
            return null;
        }
        populateEditRatingModal(result.data);
        return null;
    } catch (error) {
        console.log(error);
        showMessage("Error de conexión con el servidor.", "error");
        return null;
    }
}

// Llenar el modal de edición con los datos de la calificación
async function populateEditRatingModal(rating) {
    if (!rating) return;
    console.log(rating);
    document.getElementById('editRatingId').value = rating.id || "";
    document.getElementById('editEvent').value = rating.event_id || "";
    document.getElementById('editScore').value = rating.score || "";
    document.getElementById('editComment').value = rating.comment || "";
    const editRatingModal = document.getElementById('editRatingModal');
    const modalInstance = new bootstrap.Modal(editRatingModal);
    modalInstance.show();
}

// Actualizar calificación
async function handleRatingUpdate() {
    const id = document.getElementById('editRatingId').value;
    const eventId = document.getElementById('editEvent').value;
    const score = document.getElementById('editScore').value;
    const comment = document.getElementById('editComment').value;
    if (!id || !eventId || !score) {
        return showMessage("Por favor, completa todos los campos obligatorios.", "error");
    }
    await updateRating(id, { event_id: eventId, score, comment });
}

async function updateRating(id, ratingData) {
    const token = localStorage.getItem("token");
    try {
        const response = await fetch(`${API_BASE_URL}/rating/${id}`, {
            method: 'PUT',
            headers: {
                "Content-Type": "application/json",
                "Authorization": `Bearer ${token}`
            },
            body: JSON.stringify(ratingData)
        });
        const result = await response.json();
        if (!response.ok) {
            const errorMessage = result.message || "Error al actualizar la calificación.";
            return showMessage(errorMessage, "error");
        }
        const editedRatingModal = document.getElementById('editRatingModal');
        const modalInstance = bootstrap.Modal.getInstance(editedRatingModal);
        modalInstance.hide();
        loadRatingsTable();
        showMessage("Calificación actualizada con éxito.", "success");
    } catch (error) {
        showMessage("Error de conexión con el servidor.", "error");
    }
}

async function getEvents() {
    const createEventSelect = document.getElementById('createEvent');
    const editEventSelect = document.getElementById('editEvent');
    const token = localStorage.getItem("token");

    if (!createEventSelect) return;

    try {
        const response = await fetch(API_BASE_URL + '/events', {
            method: 'GET',
            headers: {
                "Content-Type": "application/json",
                "Authorization": `Bearer ${token}`
            }
        });

        if (!response.ok) {
            return showMessage("Error cargando eventos.", "error");
        }

        const result = await response.json();

        // Limpiar
        createEventSelect.innerHTML = '<option value="" disabled selected>Seleccionar</option>';

        // Agregar nuevas opciones
        result.data.forEach(event => {
            const option1 = document.createElement('option');
            option1.value = event.id;
            option1.textContent = event.title;

            const option2 = document.createElement('option');
            option2.value = event.id;
            option2.textContent = event.title;

            createEventSelect.appendChild(option1);
            editEventSelect.appendChild(option2);
        });

        // Si usas Select2, refrescarlo
        if ($(createEventSelect).data('select2')) {
            $(createEventSelect).trigger('change');
        }

        // Si usas Select2, refrescarlo
        if ($(editEventSelect).data('select2')) {
            $(editEventSelect).trigger('change');
        }

    } catch (e) {
        console.log(e);
        showMessage("Error de conexión con el servidor.", "error");
    }
}

// 
getEvents();

// Iniciar funciones
loadRatingsTable();