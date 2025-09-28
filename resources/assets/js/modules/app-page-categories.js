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
const createButton = document.getElementById("createButton");
if (createButton) {
    createButton.addEventListener("click", (event) => {
        handleCategoryCreation();
    });
}

const editButton = document.getElementById("editButton");
if (editButton) {
    editButton.addEventListener("click", (event) => {
        handleCategoryUpdate();
    });
}

// Limpiar el modal al cerrarlo
document.addEventListener('hidden.bs.modal', function (event) {
    if (event.target.id === 'createCategoryModal') {
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
        getCategoryEdit(id); // llama a la función con el ID
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
                return fetch(`${API_BASE_URL}/category/${id}`, {
                    method: 'DELETE',
                    headers: {
                        "Content-Type": "application/json",
                        "Authorization": `Bearer ${localStorage.getItem("token")}`
                    }
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Error al eliminar la categoría.');
                        }
                        return response.json();
                    })
                    .then(data => {
                        showMessage("Categoría eliminada con éxito ✅", "success");
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

// Función para mostrar mensajes en pantalla
function showMessage(message, type) {
    if (type === "success") {
        notify.success(message);
    } else if (type === "error") {
        notify.error(message);
    }
}

// Función para cargar y mostrar la tabla de usuarios
async function loadDataTable() {
    const dataTableCategories = document.querySelector('.datatable-categories');
    const token = localStorage.getItem("token");

    if (!dataTableCategories) return;

    try {
        // Primero traes la data
        const response = await fetch(API_BASE_URL + '/categories', {
            method: 'GET',
            headers: {
                "Content-Type": "application/json",
                "Authorization": `Bearer ${token}`
            }
        });

        if (!response.ok) { return showMessage("Error al cargar los datos de usuarios.", "error"); }

        const result = await response.json();

        // Ahora sí inicializas el DataTable con la data
        new DataTable(dataTableCategories, {
            destroy: true, // Destruye cualquier instancia previa
            data: result.data ?? result, // depende de cómo te devuelve tu API
            columns: [
                { data: 'id' },
                { data: 'name' },
                { data: 'description' },
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

// Función para crear una nueva categoría
async function handleCategoryCreation() {
    const createNameInput = document.getElementById('createName');
    const createDescriptionInput = document.getElementById('createDescription');
    const token = localStorage.getItem("token");

    const name = createNameInput.value.trim();
    const description = createDescriptionInput.value.trim();

    if (!name || !description) {
        return showMessage("Por favor, completa todos los campos.", "error");
    }

    try {
        const response = await fetch(API_BASE_URL + '/category', {
            method: 'POST',
            headers: {
                "Content-Type": "application/json",
                "Authorization": `Bearer ${token}`
            },
            body: JSON.stringify({ name, description })
        });

        const result = await response.json();

        if (!response.ok) {
            const errorMessage = result.message || "Error al crear la categoría.";
            return showMessage(errorMessage, "error");
        }

        // Si todo va bien
        showMessage("Categoría creada con éxito.", "success");

        // Cerrar modal
        const createCategoryModal = document.getElementById('createCategoryModal');
        const modalInstance = bootstrap.Modal.getInstance(createCategoryModal);
        modalInstance.hide();

        // Recargar tabla
        loadDataTable();

    } catch (error) {
        showMessage("Error de conexión con el servidor ❌", "error");
    }
}

// Función para obtener los datos de una categoría por ID y llenar el modal de edición
async function getCategoryEdit(id) {
    const token = localStorage.getItem("token");

    try {
        const response = await fetch(`${API_BASE_URL}/category/${id}`, {
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

        // Llenar el modal con los datos de la categoría
        populateEditModal(result.data);
        return category;

    } catch (error) {
        showMessage("Error de conexión con el servidor ❌", "error");
        return null;
    }
}

// Función para llenar el modal de edición con los datos de la categoría
async function populateEditModal(category) {
    console.log(category);
    if (!category) return;
    document.getElementById('editId').value = category.id || "";
    document.getElementById('editName').value = category.name || "";
    document.getElementById('editDescription').value = category.description || "";

    const editCategoryModal = document.getElementById('editCategoryModal');
    const modalInstance = new bootstrap.Modal(editCategoryModal);
    modalInstance.show();
}

// Función para manejar la actualización de una categoría
async function handleCategoryUpdate() {
    const id = document.getElementById('editId').value.trim();
    const name = document.getElementById('editName').value.trim();
    const description = document.getElementById('editDescription').value.trim();


    if (!id || !name || !description) {
        return showMessage("Por favor, completa todos los campos.", "error");
    }

    const categoryData = { name, description };

    // Llamar a la función para actualizar la categoría
    await updateCategory(id, categoryData);
}

// Función para actualizar una categoría
async function updateCategory(id, categoryData) {
    const token = localStorage.getItem("token");

    try {
        const response = await fetch(`${API_BASE_URL}/category/${id}`, {
            method: 'PUT',
            headers: {
                "Content-Type": "application/json",
                "Authorization": `Bearer ${token}`
            },
            body: JSON.stringify(categoryData)
        });

        const result = await response.json();

        if (!response.ok) {
            const errorMessage = result.message || "Error al actualizar la categoría.";
            return showMessage(errorMessage, "error");
        }

        const editedCategory = document.getElementById('editCategoryModal');
        const modalInstance = bootstrap.Modal.getInstance(editedCategory);
        modalInstance.hide();

        loadDataTable();
        showMessage("Categoría actualizada con éxito ✅", "success");

    } catch (error) {
        showMessage("Error de conexión con el servidor ❌", "error");
    }
}

// Iniciar funciones
loadDataTable();