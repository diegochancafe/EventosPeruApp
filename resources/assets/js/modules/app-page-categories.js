/**
 *  Pages Home
 */
import { Notyf } from 'notyf';
import 'notyf/notyf.min.css'; // for React, Vue and Svelte

'use strict';

// VARIABLES
const notify = new Notyf();
const API_BASE_URL = "http://localhost:8000/api";

// -- EVENTS

// Función para mostrar mensajes en pantalla
function showMessage(message, type) {
    if (type === "success") {
        notify.success(message);
    } else if (type === "error") {
        notify.error(message);
    }
}


// Función para cerrar sesión
async function getCategories() {
    const token = localStorage.getItem("token");

    try {
        const response = await fetch(`${API_BASE_URL}/category`, {
            method: "GET",
            headers: {
                "Content-Type": "application/json",
                "Authorization": `Bearer ${token}`
            }
        });

        const data = await response.json();

        if (response.ok) {
            const tbody = document.getElementById("categoryTableBody");
            tbody.innerHTML = "";

            if (data.data.length === 0) {
                tbody.innerHTML = "<tr><td colspan='3'>No hay categorias</td></tr>";
                return;
            }

            data.data.forEach(user => {
                const tr = document.createElement("tr");
                tr.innerHTML = `
                        <td>${user.id}</td>
                        <td>${user.name}</td>
                        <td>${user.description}</td>
                    `;
                tbody.appendChild(tr);
            });

        } else {
            showMessage("No se encontraron registros.", "error");
        }
    } catch (error) {
        showMessage("Error de conexión con el servidor ❌", "error");
    }
}

// Iniciar funciones
getCategories();