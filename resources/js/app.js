import { LogarithmicScale } from 'chart.js';
import './bootstrap';
/*
  Add custom scripts here
*/
import.meta.glob([
    '../assets/img/**',
    // '../assets/json/**',
    '../assets/vendor/fonts/**'
]);

'use strict';

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
