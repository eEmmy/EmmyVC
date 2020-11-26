<?php

namespace App\Http\Controller;

use Exception;
use TemplateEngine\Template;

/**
 * Metodos herdados para todos os controllers.
 */
class Controller
{
	/**
	 * Carrega uma view.
	 *
	 * @param String $viewName
	 * @param Array $params (opcional)
	 *
	 * @return Bool
	 */
	protected function view($viewName, $params=[])
	{
		// Verifica se o nomde da view foi atribuido
		if (empty($viewName)) {
			// Gera um erro
			throw new Exception("O nome da view não pode ser vazio.");

			return false;
		}

		// Substitui pontos (.) pela constante DIR
		$viewName = str_replace(".", DIR, $viewName);

		// Verifica se a view existe
		if (!file_exists(VIEWS . $viewName . ".php")) {
			// Gera um erro
			throw new Exception("O arquivo " . VIEWS . $viewName . ".php" . " não existe.");

			return false;
		}

		// Recupera o conteudo da view
		$view = file_get_contents(VIEWS . $viewName . ".php");

		// Renderiza a view
		Template::render($view, $params);

		// Retorna true por padrão
		return true;
	}
}
?>