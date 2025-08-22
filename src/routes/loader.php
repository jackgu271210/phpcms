<?php
function loadRoutes($router, $pdo, $routeFile) {
    if (file_exists($routeFile)) {
        require $routeFile;
    }
}