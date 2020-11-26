<?php 

namespace App\Http\Controller;

use App\Auth;
use App\Http\Request;

/**
 * Controller responsavel pelos métodos usados na autenticação da aplicação.
 */
class LoginController extends Controller
{
	/**
	 * Mostra a view com o formulario de login.
	 * 
	 * @return $this->view()
	 */
	public function showLoginView()
	{
		return $this->view("auth.login");
	}

	/**
	 * Loga um usuario.
	 *
	 * @param Request $request
	 *
	 * @return void
	 */
	public function login($request)
	{
		// Recupera os campos do formulario
		$email = $request->input("email");
		$pass = $request->input("password");

		// Instancia um objeto Request
		$request = new Request();

		// Array de valores
		$values = array(
			$email,
			$pass
		);

		// Tenta logar o usuario
		$loginResult = Auth::login($values, $GLOBALS["loginFields"]);

		// Verifica se o login foi efetuado com sucesso
		if ($loginResult === true) {  // Autenticou
			// Verifica se a variavel de sessão flash backlink existe
			if ($request->getFlash("backlink") !== false) {
				// Retorna para a pagina anterior
				return $request->redirect($request->getFlash("backlink"));
			}
			else {
				// Redireciona para a home
				return $request->redirect("/");
			}
		}
		else {  // Não autenticou
			// Cria a sessão flash de erro
			$request->flash("error", "Usuario e/ou senha incorretos.");

			// Retorna para a pagina de login
			return $request->redirect("login");
		}
	}

	/**
	 * Desloga um usuario.
	 *
	 * @return $request->redirect()
	 */
	public function logout()
	{
		// Desloga o usuario
		Auth::logout();
		
		// Instancia um objeto Request
		$request = new Request();
		
		// Redireciona para a home
		return $request->redirect("/");
	}
}


?>