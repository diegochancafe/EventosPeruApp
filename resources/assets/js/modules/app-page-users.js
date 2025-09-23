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
        handleUserCreation();          // Llama tu función de login
    });
}

const editButton = document.getElementById("editButton");
if (editButton) {
    editButton.addEventListener("click", (event) => {
        handleUserUpdate();          // Llama tu función de login
    });
}

// Limpiar el modal al cerrarlo
document.addEventListener('hidden.bs.modal', function (event) {
    if (event.target.id === 'createUserModal') {
        // Selecciona todos los inputs y selects dentro del modal
        event.target.querySelectorAll('input, select').forEach(el => {
            if (el.tagName === 'SELECT') {
                el.selectedIndex = 0; // resetear al primer option
            } else {
                el.value = ""; // vaciar inputs
            }
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
        getUserById(id)
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
                return fetch(`${API_BASE_URL}/user/${id}`, {
                    method: 'DELETE',
                    headers: {
                        "Content-Type": "application/json",
                        "Authorization": `Bearer ${localStorage.getItem("token")}`
                    }
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Error al eliminar el usuario.');
                        }
                        return response.json();
                    })
                    .then(data => {
                        showMessage("Usuario eliminado con éxito ✅", "success");
                        // Recargar la tabla
                        loadDataTable();
                    })
                    .catch(error => {
                        showMessage("Error al eliminar el usuario.", "error");
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
    const dataTableUsers = document.querySelector('.datatable-users');
    const token = localStorage.getItem("token");

    if (!dataTableUsers) return;

    try {
        // Primero traes la data
        const response = await fetch(API_BASE_URL + '/users', {
            method: 'GET',
            headers: {
                "Content-Type": "application/json",
                "Authorization": `Bearer ${token}`
            }
        });

        if (!response.ok) { return showMessage("Error al cargar los datos de usuarios.", "error"); }

        const result = await response.json();

        // Ahora sí inicializas el DataTable con la data
        new DataTable(dataTableUsers, {
            destroy: true, // Destruye cualquier instancia previa
            data: result.data ?? result, // depende de cómo te devuelve tu API
            columns: [
                { data: 'id' },
                { data: 'name' },
                { data: 'email' },
                { data: 'role_description' },
                { data: 'phone' },
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
            layout: createDataTableLayout('createUserModal', 'Crear usuario', [0, 1, 2])
        });

        // Estilos aplicados solo cuando ya existe la tabla
        applyCustomClasses();

    } catch (error) {
        showMessage("Error de conexión con el servidor ❌", "error");
    }
}

// Función para aplicar clases personalizadas a los elementos del DataTable
async function handleUserCreation() {

    const name = document.getElementById("createName").value.trim();
    const email = document.getElementById("createEmail").value.trim();
    const password = document.getElementById("createPassword").value.trim();
    const role = document.getElementById("createRole").value.trim();
    const phone = document.getElementById("createPhone").value.trim();


    if (!name || !email || !password || !role) {
        return showMessage("Por favor, completa todos los campos.", "error");
    }

    if (password.length < 8) {
        return showMessage("La contraseña debe tener al menos 8 caracteres.", "error");
    }

    // Llamar al servicio
    await registerUser({ name, email, password, role, phone });
}

// Función para consumir el servicio
async function registerUser(userData) {
    const token = localStorage.getItem("token");

    try {
        const response = await fetch(API_BASE_URL + '/user', {
            method: 'POST',
            headers: {
                "Content-Type": "application/json",
                "Authorization": `Bearer ${token}`
            },
            body: JSON.stringify(userData)
        });

        const result = await response.json();

        if (!response.ok) {
            const errorMessage = result.message || "Error al crear el usuario.";
            return showMessage(errorMessage, "error");
        }

        showMessage("Usuario creado con éxito ✅", "success");
        // Cerrar el modal
        const createUserModal = document.getElementById('createUserModal');
        const modalInstance = bootstrap.Modal.getInstance(createUserModal);
        modalInstance.hide();
        // Recargar la tabla
        loadDataTable();

    } catch (error) {
        showMessage("Error de conexión con el servidor ❌", "error");
    }
}

// Función para obtener datos de un usuario por ID y llenar el modal de edición
async function getUserById(id) {
    const token = localStorage.getItem("token");

    try {
        const response = await fetch(`${API_BASE_URL}/user/${id}`, {
            method: 'GET',
            headers: {
                "Content-Type": "application/json",
                "Authorization": `Bearer ${token}`
            }
        });

        if (!response.ok) {
            showMessage("Error al cargar los datos del usuario.", "error");
            return null;
        }

        const result = await response.json();
        const user = result.data ?? result;
        populateEditModal(user);
        return user;

    } catch (error) {
        showMessage("Error de conexión con el servidor ❌", "error");
        return null;
    }
}

// Función para llenar el modal de edición con los datos del usuario
async function populateEditModal(user) {
    if (!user) return;
    document.getElementById("editId").value = user.id || "";
    document.getElementById("editName").value = user.name || "";
    document.getElementById("editEmail").value = user.email || "";
    document.getElementById("editRole").value = user.role || "";
    document.getElementById("editPhone").value = user.phone || "";

    const editUserModal = document.getElementById('editUserModal');
    const modalInstance = new bootstrap.Modal(editUserModal);
    modalInstance.show();
}

// Evento para el botón de guardar cambios en el modal de edición
async function handleUserUpdate() {
    const id = document.getElementById("editId").value.trim();
    const name = document.getElementById("editName").value.trim();
    const email = document.getElementById("editEmail").value.trim();
    const role = document.getElementById("editRole").value.trim();
    const phone = document.getElementById("editPhone").value.trim();

    if (!id || !name || !email || !role) {
        return showMessage("Por favor, completa todos los campos.", "error");
    }

    // Llamar al servicio
    await updateUser(id, { name, email, role, phone });
}

// Función para consumir el servicio de actualización
async function updateUser(id, userData) {
    const token = localStorage.getItem("token");

    try {
        const response = await fetch(`${API_BASE_URL}/user/${id}`, {
            method: 'PUT',
            headers: {
                "Content-Type": "application/json",
                "Authorization": `Bearer ${token}`
            },
            body: JSON.stringify(userData)
        });

        const result = await response.json();

        if (!response.ok) {
            const errorMessage = result.message || "Error al actualizar el usuario.";
            return showMessage(errorMessage, "error");
        }

        // Cerrar el modal
        const editUserModal = document.getElementById('editUserModal');
        const modalInstance = bootstrap.Modal.getInstance(editUserModal);
        modalInstance.hide();

        loadDataTable();
        showMessage("Usuario actualizado con éxito ✅", "success");

    } catch (error) {
        showMessage("Error de conexión con el servidor ❌", "error");
    }
}

// Iniciar funciones
loadDataTable()
