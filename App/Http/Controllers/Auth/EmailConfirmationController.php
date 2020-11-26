<?php 

namespace App\Http\Controller;

use App\Auth;
use App\Http\Request;

/**
 * Controller responsavel pelos métodos usados na confirmação de email da aplicação.
 */
class EmailConfirmationController extends Controller
{
	/**
	 * Mostra a view informando a necessidade de confirmação do email.
	 * 
	 * @return $this->view()
	 */
	public function showSendedView()
	{
		return $this->view("auth.emailConfirmationSended");
	}

	/**
	 * Confirma o email de um usuario.
	 *
	 * @param Array $params
	 *
	 * @return $request->redirect()
	 */
	public function confirmEmail($params)
	{
		// Instancia um objeto Request
		$request = new Request();

		// Guarda o token
		$token = $params["token"];

		// Verifica se o token está vazio ou é menor que o esperado
		if (empty($token) || strlen($token) != 32) {
			// Redireciona para a página 404
			return $request->redirect("404");
		}

		// Confirma o email
		$confirmationResult = Auth::emailConfirmation($token);

		// Verifica se o email foi confirmado
		if ($confirmationResult === true) {
			// Cria uma sessão flash
			$request->flash("success", "Sua conta foi confirmada com sucesso.");

			// Redireciona para a pagina de login
			return $request->redirect("login");
		}
		else {
			// Redireciona para a página 404
			return $request->redirect("404");
		}
	}
}


?>