<?php 

namespace App\Http\Controller;

use App\Auth;
use App\Mail;
use App\Http\Request;

/**
 * Controller responsavel pelos métodos usados no registro de usuarios da aplicação.
 */
class RegisterController extends Controller
{
	/**
	 * Mostra a view com o formulario de registro.
	 * 
	 * @return $this->view()
	 */
	public function showRegisterView()
	{
		return $this->view("auth.register");
	}

	/**
	 * Registra um usuario.
	 *
	 * @param Request $request
	 *
	 * @return $request->redirect()
	 */
	public function register($request)
	{
		// Recupera os campos do formulario
		$user = $request->input("user");
		$email = $request->input("email");
		$pass = $request->input("password");

		// Instancia um objeto Request
		$request = new Request();

		// Array de valores
		$values = array(
			$user,
			$email,
			$pass
		);

		// Tenta registrar o usuario
		$registerResult = Auth::register($values, $GLOBALS["registerFields"]);

		// Checa o resultado do registro
		if ($registerResult === true) {  // Registrou
			// Cria o token de confirmação
			$token = Auth::makeConfirmationToken();

			// Adiciona a url ao token
			$accessLink = BASE_URL . "email/token/{$token}";

			// Envia o email de confirmação
			$mail = Mail::subject("Confirme seu email")::to($email, $user, "emails.confirmYourEmail", ["link" => $accessLink]);

			// Verifica se o email foi enviado
			if ($mail) {  // Email enviado
				// Redireciona para o aviso de confirmação de email
				$request->redirect("email/sended");
			}
			else {  // Email não enviado
				// Cria a sessão flash de erro
				$request->flash("error", "Ocorreu um erro ao enviar o email");

				// Volta para a pagina de registro
				$request->redirect("register");
			}
		}
		elseif ($registerResult === 2) {  // Erro na filtragem dos campos
			// Cria a sessão flash de erro
			$request->flash("error", "Preencha todos os campos corretamente");

			// Volta para a pagina de registro
			$request->redirect("register");
		}
		elseif ($registerResult === 3) {  // Usuario já existe
			// Cria a sessão flash de erro
			$request->flash("error", "Usuario já existe");

			// Volta para a pagina de registro
			$request->redirect("register");
		}
		else {  // Erro desconhecido
			// Cria a sessão flash de erro
			$request->flash("error", "Ocorreu um erro desconhecido. Informe ao suporte os passos que o fizeram ver esse erro.");

			// Volta para a pagina de registro
			$request->redirect("register");
		}
	}
}


?>