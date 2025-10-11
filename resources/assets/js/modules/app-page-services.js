/**
 *  Pages Services
 */
import { createDataTableLayout, applyCustomClasses } from '../utils/datatable-utils.js';
import { Notyf } from 'notyf';
import 'notyf/notyf.min.css'; // for React, Vue and Svelte

'use strict';

// VARIABLES
const notify = new Notyf();
const API_BASE_URL = "http://localhost:8000/api";

// -- EVENTS
const createButton = document.getElementById("createButton");
if (createButton) {
    createButton.addEventListener("click", (event) => {
        handleServiceCreation();
    });
}

const editButton = document.getElementById("editButton");
if (editButton) {
    editButton.addEventListener("click", (event) => {
        handleServiceUpdate();
    });
}

// Limpiar el modal al cerrarlo
document.addEventListener('hidden.bs.modal', function (event) {
    if (event.target.id === 'createServiceModal') {
        // Selecciona todos los inputs y selects dentro del modal
        event.target.querySelectorAll('input, select').forEach(el => {
            el.value = ""; // vaciar inputs
        });

        // Asegurar que ningún campo quede con foco
        if (document.activeElement) {
            document.activeElement.blur();
        }
    }
});

// Detectar clic en botón Editar
document.addEventListener('click', (e) => {
    const btn = e.target.closest('.edit-btn'); // busca si se hizo clic en un botón con esa clase
    if (btn) {
        const id = btn.getAttribute('data-id'); // recupera el atributo
        getServiceEdit(id);
    }
});

// Detectar clic en botón Eliminar
document.addEventListener('click', (e) => {
    const deleteButton = e.target.closest('.delete-btn'); // busca si se hizo clic en un botón con esa clase
    if (deleteButton) {
        const id = deleteButton.getAttribute('data-id'); // recupera el atributo

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
                return fetch(`${API_BASE_URL}/service/${id}`, {
                    method: 'DELETE',
                    headers: {
                        "Content-Type": "application/json",
                        "Authorization": `Bearer ${localStorage.getItem("token")}`
                    }
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Error al eliminar el servicio.');
                        }
                        return response.json();
                    })
                    .then(data => {
                        showMessage("Servicio eliminado con éxito.", "success");
                        // Recargar la tabla
                        loadDataTable();
                    })
                    .catch(error => {
                        showMessage("Error al eliminar el servicio.", "error");
                    });
            }
        }).then(function (result) { });
    }
});

// Función para cargar y mostrar la tabla de usuarios
async function loadDataTable() {
    const dataTableServices = document.querySelector('.datatable-services');
    const token = localStorage.getItem("token");

    if (!dataTableServices) return;

    try {
        // Primero traes la data
        const response = await fetch(API_BASE_URL + '/services', {
            method: 'GET',
            headers: {
                "Content-Type": "application/json",
                "Authorization": `Bearer ${token}`
            }
        });

        if (response.status === 403) { // No autorizado
            $("#content-not-authorized").css("display", "block");
            $("#content-authorized").css("display", "none");
            return;
        }
        if (!response.ok) { return showMessage("Error al cargar los datos de los servicios.", "error"); }

        const result = await response.json();
        // Mostrar contenido autorizado y ocultar no autorizado
        $("#content-not-authorized").css("display", "none");
        $("#content-authorized").css("display", "block");
        // Ahora sí inicializas el DataTable con la data
        new DataTable(dataTableServices, {
            destroy: true, // Destruye cualquier instancia previa
            data: result.data ?? result, // depende de cómo te devuelve tu API
            columns: [
                { data: 'id' },
                { data: 'category.name' },
                { data: 'title' },
                { data: 'description' },
                { data: 'price' },
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
            columnDefs: [
                { width: "30%", targets: 3 }  // Description column
            ],
            pageLength: 10,
            layout: createDataTableLayout('createServiceModal', 'Nuevo', [0, 1, 2])
        });

        applyCustomClasses();

    } catch (error) {
        console.error('Error fetching services:', error);
        showMessage("Error de conexión con el servidor.", "error");
    }
}

// Función para cargar las categorías en el select del modal
async function getCategories() {
    const createCategorySelect = document.getElementById('createCategory');
    const editCategorySelect = document.getElementById('editCategory');
    const token = localStorage.getItem("token");
    if (!createCategorySelect) return;

    try {
        // Primero traes la data
        const response = await fetch(API_BASE_URL + '/categories', {
            method: 'GET',
            headers: {
                "Content-Type": "application/json",
                "Authorization": `Bearer ${token}`
            }
        });
        if (response.status === 403) { // No autorizado
            return;
        }
        if (!response.ok) { return showMessage("Error al cargar las categorías.", "error"); }

        const result = await response.json();

        // Limpiar opciones existentes
        createCategorySelect.innerHTML = '<option value="" disabled selected>Seleccionar</option>';
        // Agregar nuevas opciones
        result.data.forEach(category => {
            const option1 = document.createElement('option');
            option1.value = category.id;
            option1.textContent = category.name;

            const option2 = document.createElement('option');
            option2.value = category.id;
            option2.textContent = category.name;

            createCategorySelect.appendChild(option1);
            editCategorySelect.appendChild(option2);
        });

        // Si usas Select2, refrescarlo
        if ($(createCategorySelect).data('select2')) {
            $(createCategorySelect).trigger('change');
        }

        // Si usas Select2, refrescarlo
        if ($(editCategorySelect).data('select2')) {
            $(editCategorySelect).trigger('change');
        }
    } catch (error) {
        console.error('Error fetching categories:', error);
        showMessage("Error de conexión con el servidor.", "error");
    }
}

// Función para manejar la creación de un nuevo servicio
async function handleServiceCreation() {
    const token = localStorage.getItem("token");
    const title = document.getElementById('createTitle').value;
    const description = document.getElementById('createDescription').value;
    const price = document.getElementById('createPrice').value;
    const categoryId = document.getElementById('createCategory').value;
    // Validaciones básicas
    if (!title || !description || !price || !categoryId) {
        return showMessage("Por favor, completa todos los campos.", "error");
    }

    const serviceData = {
        title,
        description,
        price,
        category_id: categoryId
    };

    try {
        const response = await fetch(API_BASE_URL + '/services', {
            method: 'POST',
            headers: {
                "Content-Type": "application/json",
                "Authorization": `Bearer ${token}`
            },
            body: JSON.stringify(serviceData)
        });
        const result = await response.json();

        if (!response.ok) {
            const message = result.message || "Error al crear el servicio.";
            return showMessage(message, "error");
        }
        // Éxito
        showMessage("Servicio creado con éxito.", "success");
        // Cerrar el modal
        const createModal = document.getElementById('createServiceModal');
        const modal = bootstrap.Modal.getInstance(createModal);
        modal.hide();

        // Recargar la tabla
        loadDataTable();
    } catch (error) {
        showMessage("Error de conexión con el servidor.", "error");
    }
}

async function getServiceEdit(id) {
    const token = localStorage.getItem("token");
    try {
        const response = await fetch(`${API_BASE_URL}/service/${id}`, {
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

async function populateEditModal(service) {
    if (!service) return;
    document.getElementById('editId').value = service.id;
    document.getElementById('editTitle').value = service.title;
    document.getElementById('editDescription').value = service.description;
    document.getElementById('editPrice').value = service.price;
    document.getElementById('editCategory').value = service.category.id;

    const editServiceModal = document.getElementById('editServiceModal');
    const modalInstance = new bootstrap.Modal(editServiceModal);
    modalInstance.show();
}

async function handleServiceUpdate() {
    const id = document.getElementById('editId').value;
    const title = document.getElementById('editTitle').value;
    const description = document.getElementById('editDescription').value;
    const price = document.getElementById('editPrice').value;
    const categoryId = document.getElementById('editCategory').value;
    // Validaciones básicas
    if (!id || !title || !description || !price || !categoryId) {
        return showMessage("Por favor, completa todos los campos.", "error");
    }
    const serviceData = {
        title,
        description,
        price,
        category_id: categoryId
    };
    updateService(id, serviceData);
}

async function updateService(id, serviceData) {
    const token = localStorage.getItem("token");
    try {
        const response = await fetch(`${API_BASE_URL}/service/${id}`, {
            method: 'PUT',
            headers: {
                "Content-Type": "application/json",
                "Authorization": `Bearer ${token}`
            },
            body: JSON.stringify(serviceData)
        });
        const result = await response.json();
        if (!response.ok) {
            const message = result.message || "Error al actualizar el servicio.";
            return showMessage(message, "error");
        }
        // Éxito
        showMessage("Servicio actualizado con éxito.", "success");
        // Cerrar el modal
        const editModal = document.getElementById('editServiceModal');
        const modal = bootstrap.Modal.getInstance(editModal);
        modal.hide();
        // Recargar la tabla
        loadDataTable();
    } catch (error) {
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

//
loadDataTable();
getCategories();