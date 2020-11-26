<?php

namespace App\Http\Controller;

/**
 * Controller responsavel pelos métodos usados nas paginas de erro da aplicação.
 */
class ErrorController extends Controller
{
	/**
	 * Carrega a view para requisições que resultem em um erro 404.
	 * 
	 * @return $this->view()
	 */
	public function error404()
	{
		return $this->view("errors.error404");
	}

	/**
	 * Carrega a view para requisições que resultem em um erro 403.
	 * 
	 * @return $this->view()
	 */
	public function error403()
	{
		return $this->view("errors.error403");
	}
}


?>