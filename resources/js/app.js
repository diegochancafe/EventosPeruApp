import { LogarithmicScale } from 'chart.js';
import './bootstrap';
import { Notyf } from 'notyf';
import 'notyf/notyf.min.css'; // for React, Vue and Svelte

/*
  Add custom scripts here
*/
import.meta.glob([
    '../assets/img/**',
    // '../assets/json/**',
    '../assets/vendor/fonts/**'
]);

'use strict';

// VARIABLES
const notify = new Notyf();
const API_BASE_URL = "http://localhost:8000/api";

console.log("App JS cargado");
// Valida solo si existe token
function hasToken() {
    return !!localStorage.getItem("token");
}

document.addEventListener("DOMContentLoaded", () => {
    const tokenExists = hasToken();
    const path = window.location.pathname;

    console.log("Token exists:", tokenExists);
    if (tokenExists) {
        // Si ya está logueado y trata de ir a login/register
        if (path.includes("/auth/login") || path.includes("/auth/register")) {
            window.location.replace("/");
        }
    } else {
        // Si no está logueado y trata de entrar al home u otras rutas protegidas
        if (path === "/" || path.startsWith("/page-")) {
            window.location.replace("/auth/login");
        }
    }
});


// -- EVENTS
const loginButton = document.getElementById("loginButton");
if (loginButton) {
    loginButton.addEventListener("click", (event) => {
        console.log("Login button clicked"); // Debugging line
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
            showMessage("Cierre de sesión exitoso.", "success");
            localStorage.removeItem("token");
            window.location.replace(baseUrl + "auth/login");
        } else {
            showMessage("Error: " + (data.message || "No se pudo cerrar sesión."), "error");
        }
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
