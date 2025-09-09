/**
 *  Pages Authentication
 */
import { Notyf } from 'notyf';
import 'notyf/notyf.min.css'; // for React, Vue and Svelte

'use strict';

// VARIABLES
const notify = new Notyf();
const API_BASE_URL = "http://localhost:8000/api";

// -- EVENTS
const loginForm = document.getElementById("formLogin");
if (loginForm) {
    loginForm.addEventListener("submit", handleLogin);
}

const registerForm = document.getElementById("formRegister");
if (registerForm) {
    registerForm.addEventListener("submit", handleSignUp);
}

// FUNCTIONS

// Función que maneja el evento
async function handleLogin(event) {
    event.preventDefault(); // Evita que se recargue la página

    const email = document.getElementById("email").value.trim();
    const password = document.getElementById("password").value.trim();

    // Validaciones front-end (opcionales, ya que el backend también valida)
    if (!email || !password) {
        showMessage("Todos los campos son obligatorios", "error");
        return;
    }
    if (password.length < 8) {
        showMessage("La contraseña debe tener al menos 8 caracteres", "error");
        return;
    }

    // Llamar al servicio
    await loginUser({ email, password });
}


// Función que maneja el evento
async function handleSignUp(event) {
    event.preventDefault(); // Evita que se recargue la página

    const name = document.getElementById("username").value.trim();
    const email = document.getElementById("email").value.trim();
    const password = document.getElementById("password").value.trim();

    // Validaciones front-end (opcionales, ya que el backend también valida)
    if (!name || !email || !password) {
        showMessage("Todos los campos son obligatorios", "error");
        return;
    }
    if (password.length < 8) {
        showMessage("La contraseña debe tener al menos 8 caracteres", "error");
        return;
    }

    // Llamar al servicio
    await registerUser({ name, email, password });
}


// SERVICES

// Función para consumir el servicio
async function loginUser(userData) {
    try {
        const response = await fetch(`${API_BASE_URL}/login`, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(userData),
        });

        const data = await response.json();

        if (response.ok) {
            localStorage.setItem("token", data.token); // Save token
            showMessage("Inicio de sesión exitoso ✅", "success");
            window.location.replace(baseUrl);
        } else {
            showMessage("Error: " + (data.message || "Credenciales incorrectas ❌"), "error");
        }
    } catch (error) {
        showMessage("Error de conexión con el servidor ❌", "error");
    }
}

// Función para consumir el servicio
async function registerUser(userData) {
    try {
        const response = await fetch(`${API_BASE_URL}/user`, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(userData),
        });

        const data = await response.json();

        if (response.ok) {
            showMessage("Usuario creado con éxito ✅", "success");
            window.location.replace(baseUrl + 'auth/login');
        } else {
            showMessage("Error: " + (data.message || "No se pudo registrar"), "error");
        }
    } catch (error) {
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