<?php 

namespace App\Http\Controller;

use App\Auth;
use App\Mail;
use App\Http\Request;

/**
 * Controller responsavel pelos métodos usados para redefinir a senha do usuario na aplicação.
 */
class PasswordResetController extends Controller
{
	/**
	 * Mostra a view com o formulario de envio de reset.
	 * 
	 * @return $this->view()
	 */
	public function showSendView()
	{
		return $this->view("auth.sendPasswordReset");
	}

	/**
	 * Envia o email para reset de senha caso o usuario exista.
	 *
	 * @param Request $request
	 *
	 * @return $request->redirect()
	 */
	public function sendPasswordReset($request)
	{
		// Recupera o campo do formulario
		$email = $request->input("email");

		// Valida o usuario e cadastra um novo token de email
		$user = Auth::setEmailToken($email);

		// Verifica o resultado da validação
		if ($user !== false) {  // O usuario existe
			// Monta o link de redefinição
			$accessLink = BASE_URL . "password/token/" . $user["token"];

			// Envia um email ao usuario
			Mail::subject("Redefinir sua senha")::to($email, $user["name"], "emails.resetYourPassword", ["link" => $accessLink]);
		}

		// Instancia um objeto Request
		$request = new Request();

		// Cria uma sessão flash
		$request->flash("passwordSended", 1);

		// Redireciona para o pós envio
		return $request->redirect("password/sended");
	}

	/**
	 * Mostra a view informando que o formulario foi enviado.
	 *
	 * @return void
	 */
	public function showSendedView()
	{
		// Instancia um objeto Request
		$request = new Request();

		// Verifica se a sessão flash que permite essa pagina ser exibida existe
		if ($request->getFlash("passwordSended") === false) {
			// Redireciona para a pagina 404
			return $request->redirect("404");
		}
		else {
			// Redefine essa sessão
			$request->flash("passwordSended", 1);
		}

		// Carrega a view
		return $this->view("auth.sendedPasswordReset");
	}

	/**
	 * Checa o token de reset.
	 *
	 * @param Array $params
	 *
	 * @return $request->redirect()
	 */
	public function tokenCheck($params)
	{
		// Instancia um objeto Request
		$request = new Request();

		// Valida o token
		$validateToken = Auth::validateToken($params["token"]);

		// Verifica se o token foi validado com sucesso
		if ($validateToken !== false) {
			// Cria a sessão para ser possivel resetar a senha
			$request->session("RESET_PASSWORD_PERMISSION", ["id" => $validateToken, "confirmation" => $GLOBALS["RESET_PASSWORD_PERMISSION"]]);

			// Redireciona para a pagina de reset
			$request->redirect("password/reset");
		}
		else {
			// Redireciona para a pagina 404
			return $request->redirect("404");
		}
	}

	/**
	 * Mostra a view com o formulario de redefinição de senha.
	 *
	 * @return void
	 */
	public function showResetView()
	{
		// Instancia um objeto Request
		$request = new Request();

		// Armazena a sessão
		$passwordResetPermission = $request->session("RESET_PASSWORD_PERMISSION");

		// Verifica se a chave é valida
		if (isset($passwordResetPermission["confirmation"]) && $passwordResetPermission["confirmation"] == $GLOBALS["RESET_PASSWORD_PERMISSION"]) {  // Chave valida
			// Mostra a view
			return $this->view("auth.passwordReset");
		}
		else {  // Chave invalida
			// Redireciona para a pagina 404
			return $request->redirect("404");
		}

	}

	/**
	 * Redefine a senha de um usuario.
	 *
	 * @param Request $request
	 *
	 * @return $request->redirect
	 */
	public function resetPassword($request)
	{
		// Armazena a sessão
		$passwordResetPermission = $request->session("RESET_PASSWORD_PERMISSION");

		// Verifica se a chave é valida
		if (isset($passwordResetPermission["confirmation"]) && $passwordResetPermission["confirmation"] == $GLOBALS["RESET_PASSWORD_PERMISSION"]) {  // Chave valida
			// Destroi a sessão
			unset($_SESSION["RESET_PASSWORD_PERMISSION"]);

			// Recupera os dados do formulário
			$pass = $request->input("password");

			// Redefine a senha do usuario
			$passUpdateResult = Auth::resetPassword($passwordResetPermission["id"], $pass);

			// Verifica o resultado da operação
			if ($passUpdateResult === true) {
				// Cria uma sessão flash
				$request->flash("success", "Sua senha foi redefinida com sucesso!");
			}
			else {
				// Crua uma sessão flash
				$request->flash("error", "Ocorreu um erro. Tente novamente mais tarde.");
			}

			// Redireciona para a pagina de login
			return $request->redirect("login");
		}
		else {  // Chave invalida
			// Redireciona para a pagina 404
			return $request->redirect("404");
		}

	}
}

?>