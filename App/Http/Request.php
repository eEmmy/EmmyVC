<?php

namespace App\Http;

use \Exception;

/**
 * Lida as requisições do resto da aplicação.
 */
class Request
{
	/**
	 * Armazena os valores de $_POST.
	 *
	 * @var Array $postParams
	 */
	private $postParams;

	/**
	 * Tenta recuperar uma variavel de sessão ou criar uma nova.
	 *
	 * @param String $paramName
	 * @param $value (opcional)
	 *
	 * @return void
	 */
	public function session($paramName, $value="")
	{
		// Verifica se $paramName tem algum conteudo
		if (empty($paramName)) {
			// Gera um erro
			throw new Exception("Informe um nome a ser recuperado ou definido.");

			return false;
		}

		// Verifica se foi definido um valor para $value
		if (empty($value)) {  // Recupera uma variavel de sessão
			// Verifica se a variavel de sessão existe
			if (isset($_SESSION[$paramName])) {
				// Retorna o valor da varivel
				return $_SESSION[$paramName];
			}
			else {
				// Retorna false
				return false;
			}
		}
		else {  // Define uma nova variavel de sessão
			// Cria a variavel
			$_SESSION[$paramName] = $value;
		}
	}

	/**
	 * Cria uma variavel de sessão do tipo flash.
	 *
	 * @param String $sessionName
	 * @param String $sessionVal
	 *
	 * @return void
	 */
	public function flash($sessionName, $sessionVal)
	{
		// Cria a sessão
		$this->session(
			"FLASH",
			[$sessionName => $sessionVal, "count" => 0]
		);
	}

	/**
	 * Recupera um variavel de sessão do tipo flash.
	 *
	 * @param String $sessionName
	 *
	 * @return void
	 */
	public function getFlash($sessionName)
	{
		// Verifica se a sessão existe
		if (isset($_SESSION["FLASH"][$sessionName])) {
			// Retorna o valor da sessão
			return $_SESSION["FLASH"][$sessionName];
		}
		else {
			// Retorna false
			return false;
		}
	}

	/**
	 * Redireciona para uma página. 
	 *
	 * @param String $url
	 *
	 * @return void
	 */
	public function redirect($url)
	{
		// Verifica se a pagina é /
		if ($url == "/") {
			// Define como vazia para evitar conflitos
			$url = "";
		}

		// Verifica se existem sessões do tipo flash
		if (isset($_SESSION["FLASH"]["count"])) {
			// Verifica se o indice "count" é igual 1
			if($_SESSION["FLASH"]["count"] == 1) {
				// Exclui sessões do tipo flash
				unset($_SESSION["FLASH"]);
			}
			else {
				// Aumenta o contador
				$_SESSION["FLASH"]["count"]++;
			}
		}

		// Muda o cabeçalho e redireciona
		header("Location: " . BASE_URL . $url);

		// Executa o método die() para evitar robôs
		die();
	}

	/**
	 * Salva o valor de $_POST.
	 *
	 * @return void
	 */
	public function getPOSTData()
	{
		// Armazena o valor de post
		$this->postParams = $_POST;
	}

	/**
	 * Recupera um campo de $_POST.
	 *
	 * @param String $index
	 *
	 * @return void
	 */
	public function input($index)
	{
		// Verifica se foi informado um indice
		if (empty($index)) {
			// Gera um erro
			throw new Exception("Nenhum indice definido");

			return false;
		}

		// Verifica se o index existe
		if (!isset($this->postParams[$index])) {
			// Retorna false
			return false;
		}

		// Retorna o valor pedido
		return $this->postParams[$index];
	}
}
?>