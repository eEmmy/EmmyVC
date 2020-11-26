<?php 

namespace App\Http\Controller;

/**
 * Controller responsavel pelos métodos usados na home da aplicação.
 */
class HomeController extends Controller
{
	/**
	 * Retorna a view da home.
	 * 
	 * @return $this->view()
	 */
	public function index()
	{
		return $this->view("home");
	}
}

?>