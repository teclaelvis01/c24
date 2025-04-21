<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Simple function to test
function saludar($nombre) {
    $mensaje = "Hola, " . $nombre;
    return $mensaje;
}

// Test variable
$nombre = "Mundo";

// Function call
$saludo = saludar($nombre);

// Show result
echo $saludo; 