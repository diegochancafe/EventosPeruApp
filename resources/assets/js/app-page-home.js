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
const loginButton = document.getElementById("loginButton");
if (loginButton) {
    loginButton.addEventListener("click", (event) => {
        event.preventDefault(); // Evita redirección si es <a>
        logoutUser();          // Llama tu función de login
    });
}

// FUNCTIONS

// Función para cerrar sesión
async function logoutUser() {
    const token = localStorage.getItem("token");
    console.log("Token retrieved:", token); // Debugging line

    try {
        const response = await fetch(`${API_BASE_URL}/logout`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Authorization": `Bearer ${token}`
            }
        });

        const data = await response.json();

        if (response.ok) {
            showMessage("Cierre de sesión exitoso ✅", "success");
            localStorage.removeItem("token");
            window.location.replace(baseUrl + "auth/login-basic");
        } else {
            showMessage("Error: " + (data.message || "No se pudo cerrar sesión ❌"), "error");
        }
    } catch (error) {
        showMessage("Error de conexión con el servidor ❌", "error");
    }
}

fetch(API_BASE_URL + "/user")
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        const tbody = document.getElementById("userTableBody");
        tbody.innerHTML = "";

        if (data.data.length === 0) {
            tbody.innerHTML = "<tr><td colspan='3'>No hay usuarios</td></tr>";
            return;
        }

        data.data.forEach(user => {
            const tr = document.createElement("tr");
            tr.innerHTML = `
                    <td>${user.id}</td>
                    <td>${user.name}</td>
                    <td>${user.email}</td>
                `;
            tbody.appendChild(tr);
        });
    })
    .catch(error => {
        console.error("Error al obtener usuarios:", error);
        const tbody = document.getElementById("userTableBody");
        tbody.innerHTML = "<tr><td colspan='3'>Error al cargar usuarios</td></tr>";
    });

// Función para mostrar mensajes en pantalla
function showMessage(message, type) {
    if (type === "success") {
        notify.success(message);
    } else if (type === "error") {
        notify.error(message);
    }
}