<?php

namespace App\Http;

use Exception;

/**
 * Lida com as rotas da aplicação.
 *
 * @param Array $routes
 */
class Router
{
	/**
	 * Separa os dados fornecidos e chama os métodos de requisição.
	 *
	 * @param Array $routes
	 *
	 * @return void
	 */
	function __construct($routes)
	{
		// Instancia um objeto Request
		$this->request = new Request();

		// Verifica se $routes tem algum indice
		if (count($routes) > 0) {
			// Loop em $routes
			foreach ($routes as $route) {
				// Verifica se existem os campos necessarios dentro do sub array
				if (isset($route[0]) && isset($route[1]) && isset($route[2]) && isset($route[3])) {
					// Variaveis
					$method = $route[0];
					$url = $route[1];
					$controllerAction = $route[2];
					$params = $route[3];
					$middleware = isset($route[4]) ? $route[4] : "";

					// Verifica se o controller e action foram passados
					if (empty($controllerAction) || strpos($controllerAction, "@") <= 0) {
						// Gera um erro
						throw new Exception("Nenhum Controller e/ou action passados na rota " . $url);

						return false;					
					}

					// Verifica a url
					if (count($params) > 0 && $url != str_replace((str_replace($url, "", URL)), "", URL)) {  // Quantidade de parametros diferente do especificado
						// Pula para a próxima rota
						continue;
					}
					elseif (count($params) == 0 && $url != URL) {  // Nenhum parametro
						// Pula para a próxima rota
						continue;
					}

					// Verifica se algum parametro foi passado
					if (count($params) > 0) {
						// Exclui a url base de $url para ficar apenas com os parametros
						$paramsInUrl = str_replace($url, "", URL);

						// Explode os parametros em um array
						$paramsInUrl = explode("/", substr($paramsInUrl, 1));

						// Loop para definir os parametros
						foreach ($params as $key => $value) {
							// Verifica se a chave esta vazia
							if (empty($paramsInUrl[$key])) {
								// Redireciona para a pagina 404
								return $this->request->redirect("404");
							}

							// Define o parametro com a chave correta
							$params["$value"] = $paramsInUrl[$key];

							// Destroi a chave original
							unset($params[$key]);
						}

						// Verifica se os todos parametros foram passados
						if (count($params) != count($paramsInUrl)) {
							// Redireciona para a pagina 404
							return $this->request->redirect("404");
						}
					}

					// Verifica qual método está sendo usado
					if (count($_POST) == 0 && $method == "GET") {
						// Chama o método GET
						return $this->getRequest($this->request, $url, $controllerAction, $params, $middleware);
					}
					elseif (count($_POST) > 0) {
						// Verifica se a url é GET
						if ($method != "POST") {
							// Pula para a próxima rota
							continue;
						}

						// Chama o método POST
						return $this->postRequest($this->request, $url, $controllerAction, $middleware);
					}
					else {
						// Gera um erro
						throw new Exception("Nenhum Método definido na rota " . $url);

						return false;
					}
				}
			}
		}

		// Redireciona para a pagina 404 por padrão
		return $this->request->redirect("404");
	}

	/**
	 * Lida com solicitações GET.
	 *
	 * @param Request $request
	 * @param String $url
	 * @param String $controllerAction
	 * @param Array $params
	 * @param String $middleware
	 *
	 * @return $controller->$action()
	 */
	public function getRequest($request, $url, $controllerAction, $params, $middleware)
	{
		// Separa o controller do action
		$controllerAction = explode("@", $controllerAction);

		// Guarda controller e action separadamente
		$controller = $controllerAction[0];
		$action = $controllerAction[1];

		// Verifica se o controller existe
		if (class_exists("App\\Http\\Controller\\{$controller}")) {
			// Adiciona o namespace ao controller
			$controller = str_replace($controller, "App\\Http\\Controller\\{$controller}", $controller);

			// Instancia o controller
			$controller = new $controller();

			// Verifica se a action existe
			if (method_exists($controller, $action)) {
				// Verifica se foi definido um middleware
				if (!empty($middleware)) {  // Usar middleware
					// Verifica se o middleware existe
					if (class_exists("App\\Http\\Middleware\\{$middleware}")) {  // Middleware existe
						// Adiciona o namespace ao middleware
						$middleware = str_replace($middleware, "App\\Http\\Middleware\\{$middleware}", $middleware);
							
						// Instancia o middleware
						$middleware = new $middleware();

						// Verifica se os requisitos do middleware estão sendo cumpridos
						if ($middleware->check()) {  // Acesso permitido
							// Verifica se foram passados parametros
							if (!empty($params)) {
								// Chama a action passado $params
								return $controller->$action($params);
							}
							else {
								// Chama a action
								return $controller->$action();
							}
						}
						else {  // Acesso negado
							// Executa o metodo denied de middleware
							return $middleware->denied();
						}
					}
					else {  // Middleware não existe
						// Gera um erro
						throw new Exception("O arquivo " . MIDDLEWARES . $middleware . ".php não existe.");

						return false;
					}
				}
				else {  // Não usar middleware
					// Verifica se foram passados parametros
					if (!empty($params)) {
						// Chama a action passando $params
						return $controller->$action($params);
					}
					else {
						// Chama a action
						return $controller->$action();
					}
				}
			}
		}
	}

	/**
	 * Lida com solicitações POST.
	 *
	 * @param Request $request
	 * @param String $url
	 * @param String $controllerAction
	 * @param String $middleware
	 *
	 * @return $controller->$action()
	 */
	public function postRequest($request, $url, $controllerAction, $middleware)
	{
		// Separa o controller do action
		$controllerAction = explode("@", $controllerAction);

		// Guarda controller e action separadamente
		$controller = $controllerAction[0];
		$action = $controllerAction[1];

		// Salva os dados do formulário
		$request->getPOSTData();

		// Verifica se o controller existe
		if (class_exists("App\\Http\\Controller\\{$controller}")) {
			// Adiciona o namespace ao controller
			$controller = str_replace($controller, "App\\Http\\Controller\\{$controller}", $controller);

			// Instancia o controller
			$controller = new $controller();

			// Verifica se a action existe
			if (method_exists($controller, $action)) {
				// Chama a action
				return $controller->$action($request);
			}
		}
	}
}

?>