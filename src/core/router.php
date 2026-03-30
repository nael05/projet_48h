<?php
namespace App\Core;

class Router {
    private array $routes = [];

    /**
     * Ajoute une route au routeur
     */
    public function add(string $route, string $controller, string $action): void {
        $this->routes[$route] = [
            'controller' => $controller,
            'action' => $action
        ];
    }

    /**
     * Dispatche la requête HTTP vers le bon contrôleur/méthode
     */
    public function dispatch(string $uri): void {
        // Nettoyage de l'URI (suppression des Query Parameters pour la correspondance exacte)
        $uri = parse_url($uri, PHP_URL_PATH);

        // Si la racine est demandée, on redirige vers /feed par défaut
        if ($uri === '/') {
            $uri = '/feed';
        }

        if (array_key_exists($uri, $this->routes)) {
            $controllerName = "App\\Controllers\\" . $this->routes[$uri]['controller'];
            $action = $this->routes[$uri]['action'];

            if (class_exists($controllerName)) {
                $controller = new $controllerName();
                if (method_exists($controller, $action)) {
                    $controller->$action();
                } else {
                    http_response_code(500);
                    echo "Erreur Serveur : Méthode '$action' introuvable dans le contrôleur '$controllerName'.";
                }
            } else {
                http_response_code(500);
                echo "Erreur Serveur : Le contrôleur '$controllerName' n'existe pas.";
            }
        } else {
            // Route non configurée
            http_response_code(404);
            echo "Erreur 404 : La page '$uri' n'existe pas ou n'a pas été trouvée.";
        }
    }
}
