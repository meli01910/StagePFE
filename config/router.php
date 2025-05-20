<?php
// app/router.php

class Router {
    private $routes = [];
    
    /**
     * Ajoute une route au routeur
     * 
     * @param string $module Le module à charger
     * @param string $action L'action à exécuter
     * @param callable $callback La fonction à appeler pour cette route
     */
    public function add($module, $action, $callback) {
        // Enregistre la route dans un tableau avec module et action comme clés
        $this->routes[$module][$action] = $callback;
    }
    
    /**
     * Ajoute plusieurs routes pour un même module
     * 
     * @param string $module Le module à charger
     * @param array $routes Un tableau associatif action => callback
     */
    public function addRoutes($module, $routes) {
        foreach ($routes as $action => $callback) {
            $this->add($module, $action, $callback);
        }
    }
    
    /**
     * Dispatche la requête vers le contrôleur approprié
     * 
     * @param string $module Le module demandé
     * @param string $action L'action demandée
     * @param array $params Paramètres additionnels
     * @return bool True si la route a été trouvée, false sinon
     */
    public function dispatch($module, $action, $params = []) {
        // Vérifie si le module et l'action existent
        if (isset($this->routes[$module][$action])) {
            // Appelle la fonction de rappel avec les paramètres
            call_user_func($this->routes[$module][$action], $params);
            return true;
        }
        
        // Route non trouvée
        return false;
    }
    
    /**
     * Redirige vers une autre route
     * 
     * @param string $module Le module cible
     * @param string $action L'action cible
     * @param array $params Paramètres additionnels
     */
    public function redirect($module, $action, $params = []) {
        $url = 'index.php?module=' . urlencode($module) . '&action=' . urlencode($action);
        
        // Ajoute les paramètres additionnels à l'URL
        foreach ($params as $key => $value) {
            $url .= '&' . urlencode($key) . '=' . urlencode($value);
        }
        
        header('Location: ' . $url);
        exit;
    }
    
    /**
     * Gère les routes non trouvées
     */
    public function notFound() {
        http_response_code(404);
        include __DIR__ . '/views/errors/404.php';
        exit;
    }
}
