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
        const response = await fetch(API_BASE_URL + '/user', {
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
                { data: 'role' },
                { data: 'phone' }
            ],
            pageLength: 10,
            layout: createDataTableLayout('createUserModal', 'Add User', [0, 1, 2])
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



// Iniciar funciones
loadDataTable()
