<?php

use App\Http\Request;

/**
 * Executa os métodos necessários para a aplicação se auto-executar.
 */
class App
{
	/**
	 * Executa os outros métodos da classe.
	 *
	 * @return void
	 */
	public static function init()
	{
		// Método para o inicio da sessão
		self::sessionStart();

		// Método para a verificação da origem de solicitações POST
		self::postVerify();

		// Método para definir constantes
		self::defineConstants();

		// Método para configurar o reporting de erros
		self::setReporting();
	}

	/**
	 * Inicia a sessão caso não tenha sido iniciada.
	 *
	 * @return void
	 */
	protected static function sessionStart()
	{
		// Verifica se a sessão já foi iniciada
		if (session_status() != PHP_SESSION_ACTIVE) {
			// Define o um nome novvo para PHPSESSID
			session_name(md5($_SERVER["REMOTE_ADDR"] . $_SERVER["HTTP_USER_AGENT"] . $GLOBALS["sessionHash"]));

			// Define o timeout da sessão
			session_cache_expire($GLOBALS["sessionExpiresIn"]);

			// Inicia a sessão
			session_start();
		}
	}

	/**
	 * Valida solicitações do tipo POST.
	 *
	 * @return void
	 */
	protected static function postVerify()
	{
		// Verifica se a página foi postada de algum formulário
		if (isset($_POST) && count($_POST) > 0) {
			// Compara o token do POST com o da sessão
			if (!isset($_POST["token"]) || $_POST["token"] != $GLOBALS["token"]) {
				// Destroi o post caso o token não seja valido
				unset($_POST);

				// Instancia um objeto Request
				$request = new Request;

				// Redireciona para a pagina 403
				return $request->redirect("403");
			}
		}
	}

	/** 
	 * Define as constantes da aplicação.
	 *
	 * @return void
	 */
	protected static function defineConstants()
	{
		// Previne erros nas classes Make e App\Maker
		$_SERVER["REQUEST_URI"] = isset($_SERVER["REQUEST_URI"]) ? $_SERVER["REQUEST_URI"] : "";

		// Constantes de diretório
		define("DIR", DIRECTORY_SEPARATOR);
		define("ROOT", null !== $_SERVER["DOCUMENT_ROOT"]  ? $_SERVER["DOCUMENT_ROOT"] . DIR : "");

		// Constantes para as pastas da aplicação
		define("VIEWS", ROOT . "Resources" . DIR . "views" . DIR);

		// Define constantes de URL
		define("BASE_URL", $GLOBALS["base_url"]);
		define("URL", $_SERVER["REQUEST_URI"]);
	}

	/**
	 * Configura a exibição de erros da aplicação.
	 *
	 * @return void
	 */
	protected function setReporting()
	{
		// Verifica a configuração de $debug
		if ($GLOBALS["debug"]) {
			// Habilita todos os erros
			error_reporting(E_ALL);

			// Reporta os erros
			ini_set("display_errors", 1);
		}
		else {
			// Oculta os erros
			ini_set('display_errors', 0 );
	
			// Desabilita o reporting
			error_reporting(0);
		}
	}
}

?>