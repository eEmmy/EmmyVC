<?php

namespace App;

use DB\DB;
use \Exception;
use App\EmailToken;
use App\Http\Request;

/**
 * Classe responsavel por todas as operações de autenticação
 */
class Auth
{
	/**
	 * Tabela em que as informações dos usuários serão guardadas.
	 *
	 * @var String $table
	 */
	private static $table;

	/**
	 * Define variaveis de acesso global para o escopo da classe.
	 *
	 * @return void
	 */
	protected static function init()
	{
		// Define as variaveis
		self::$table = $GLOBALS["authTable"];
	}

	/**
	 * Filtra dados passados.
	 *
	 * @param String $inputString
	 * @param String $filter
	 *
	 * @return bool
	 */
	protected static function filter($inputString, $filter)
	{
		// Separa os filtros
		$filters = explode("|", $filter);

		// Loop em $filters
		foreach ($filters as $key => $filter) {
			// Filtros de tamanho
			if (strpos($filter, "minlen") !== false) {  // Tamanho minimo
				// Separa o parametro do filtro
				$filter = explode(":", $filter);

				// Pega o tamanho minimo
				$minlen = intval($filter[1]);

				// Verifica se o tamanho de $inputString cumpre o tamanho minimo informado
				if (strlen($inputString) < $minlen) {
					// Retorna falso
					return false;
				}
			}

			elseif (strpos($filter, "maxlen") !== false) {  // Tamanho maximo
				// Separa o parametro do filtro
				$filter = explode(":", $filter);

				// Pega o tamanho maximo
				$maxlen = intval($filter[1]);

				// Verifica se o tamanho de $inputString excede o tamanho maximo informado
				if (strlen($inputString) > $maxlen) {
					// Retorna falso
					return false;
				}
			}

			// Filtros de exclusividade
			elseif (strpos($filter, "unique") !== false) {
				// Separa o parametro do filtro
				$filter = explode(":", $filter);

				// Pega o campo a ser filtrado
				$field = $filter[1];

				// Executa um select com o campo informado
				$select = DB::table(self::$table)
					::where([
						[$field, "=", $inputString]
					])::
					select([$field]);

				// Verifica se o select retornou algum resultado
				if (count($select) > 0) {
					// Retorna falso
					return false;
				}
			}
		}

		// Retorna true caso o loop não tenha terminado a função ainda
		return true;
	}

	/**
	 * Criptografa uma string.
	 *
	 * @param String $inputString
	 *
	 * @return String $outputString
	 */
	protected static function encrypt($inputString)
	{
		// Criptografa a string de entrada
		$outputString = md5($inputString . $GLOBALS["passHash"]);

		// Retorna a string criptografada
		return $outputString;
	}

	/**
	 * Registra um usuario.
	 *
	 * @param Array $values
	 * @param Array $fields
	 *
	 * @return void
	 */
	public static function register($values, $fields)
	{
		// Verifica se $values tem o mesmo numero de campos que $fields
		if (count($values) != count($fields)+1) {
			// Gera um erro
			throw new Exception("Os valores passados não estão de acordo com a quantidade de campos necessários para registro");
			
			return false;
		}

		// Adiciona o campo de senha a $fields
		array_push($fields, ["password", "minlen:32|maxlen:32"]);

		// Criptografa a senha
		$values[array_search(end($values), $values)] = self::encrypt(end($values));

		// Define as variaveis do tipo self
		self::init();

		// Array de insersão
		$create = array();

		// Loop em $fields
		foreach ($fields as $key => $info) {
			// Filtra o campo respectivo em $values
			if (!self::filter($values[$key], $info[1])) {
				// Verifica se o filtro que falhou foi de exclusividade
				if (strpos($info[1], "unique")) {
					// Retorna 3 para usuario que ja existe
					return 3;
				}
				// Retorna 2 para campos com valores invalidos
				return 2;
			}

			// Monta o array de insersão
			$create[$info[0]] = $values[$key];
		}

		// Insere os dados
		DB::table(self::$table)::create($create);

		return true;
	}

	/**
	 * Cria um token de confirmação de email.
	 *
	 * @return String $token
	 */
	public static function makeConfirmationToken()
	{
		// Define as variaveis do tipo self
		self::init();

		// Recupera o ultimo id inserido na tabela
		$lastId = DB::table(self::$table)::lastId();

		// Cria o token
		$token = md5(time());

		// Insere os dados na tabela
		EmailToken::create([
			"user_id" => $lastId,
			"token" => $token
		]);

		// Retorna o token
		return $token;
	}

	/**
	 * Valida um token de email, depois exclui ele da tabela
	 *
	 * @param String $token
	 *
	 * @return Int $userId
	 */
	public static function validateToken($token)
	{
		// Busca pelo token na tabela
		$user = EmailToken::where([
			["token", "=", $token]
		])::select(["user_id"]);

		// Verifica se a busca retornou algo
		if (count($user) > 0) {  // Resultado encontrado
			// Pega o id do usuario
			$userId = intval($user[0]["user_id"]);

			// Exclui aquele token da tabela
			EmailToken::update([
				"token" => ["", "=", $token]
			]);

			// Retorna o id do usuario
			return $userId;
		}
		else {  // Nenhum resultado encontrado
			// Retorna falso
			return false;
		}
	}


	/**
	 * Confirma o email de um usuario.
	 *
	 * @param String $token
	 *
	 * @return Bool
	 */
	public static function emailConfirmation($token)
	{
		// Define as variaveis do tipo self
		self::init();

		// Valida o token
		$userId = self::validateToken($token);

		// Verifica se a busca retornou algo
		if ($userId !== false) {
			// Altera o status do usuario
			DB::table(self::$table)::update([
				"user_state" => [1, "=", 0],
				"id" => [$userId, "=", $userId]
			]);

			// Retorna true
			return true;
		}
		else {
			// Retorna falso
			return false;
		}
	}

	/**
	 * Autentica um usuario.
	 *
	 * @param Array $values
	 * @param Array $fields
	 *
	 * @return Bool
	 */
	public static function login($values, $fields)
	{
		// Verifica se $values tem o mesmo numero de campos que $fields
		if (count($values) != count($fields)+1) {
			// Retorna 2 para campos com valores invalidos
			return 2;
		}

		// Adiciona o campo de senha a $fields
		array_push($fields, ["password", "minlen:32|maxlen:32"]);

		// Criptografa a senha
		$values[array_search(end($values), $values)] = self::encrypt(end($values));

		// Define as variaveis do tipo self
		self::init();

		// Array para busca dentro da tabela
		$where = array();

		// Loop em $fields
		foreach ($fields as $key => $info) {
			// Filtra o campo respectivo em $values
			if (!self::filter($values[$key], $info[1])) {
				// Retorna falso
				return false;
			}

			if (empty($info[0])) {
				continue;
			}

			// Monta o array de busca
			$where[$key] = [$info[0], "=", $values[$key]];
		}

		// Busca pelo usuario na base
		$userResult = DB::table(self::$table)::where($where)::select(["id"]);

		// Verifica a busca
		if (count($userResult) > 0) {
			// Pega o id do usuario
			$id = $userResult[0]["id"];

			// Instancia um objeto request
			$request = new Request();

			// Cria a sessão com o identificador
			$request->session("USER_GUARD_IDENTIFICATION_KEY_12890", $id);

			// Retorna true
			return true;
		}
		else { 
			// Retorna falso
			return false;
		}
	}

	/**
	 * Desloga um usuario.
	 *
	 * @return void
	 */
	public static function logout()
	{
		// Destroi a sessão
		unset($_SESSION["USER_GUARD_IDENTIFICATION_KEY_12890"]);
	}

	/**
	 * Valida um email e cria um token de email.
	 *
	 * @param String $email
	 *
	 * @return void
	 */
	public static function setEmailToken($email)
	{
		// Define as variaveis do tipo self
		self::init();

		// Busca pelo email na tabela de usuarios
		$userData = DB::table(self::$table)::
		where([
			["email", "=", $email]
		])::
		select(["id", "`user`"]);

		// Verifica se a busca retornou resultados
		if (count($userData) > 0) {
			// Dados separadas
			$userId = $userData[0]["id"];
			$userName = $userData[0]["user"];

			// Cria o token
			$token = md5(time());

			// Atualiza a tabela de tokens
			EmailToken::update([
				"token" => [$token, "=", ""],
				"user_id" => [$userId, "=", $userId]
			]);

			// Monta o array de retorno
			$userData = array(
				"name" => $userName,
				"token" => $token
			);

			// Retorna as informações
			return $userData;
		}
		else {
			// Retorna falso
			return false;
		}
	}

	/**
	 * Redefine a senha de um usuario.
	 *
	 * @param Int $userId
	 * @param String $newPass
	 *
	 * @return Bool
	 */
	public static function resetPassword($userId, $newPass)
	{
		// Define as variaveis do tipo self
		self::init();

		// Encripta a senha
		$newPass = self::encrypt($newPass);

		// Atualiza o usuario
		$passUpdate = DB::table(self::$table)::update([
			"password" => [$newPass, "<>", $newPass],
			"id" => [$userId, "=", $userId]
		]);

		// Retorna o resultado da operação
		return $passUpdate;
	}

	/**
	 * Verifica se o usuario está logado.
	 *
	 * @return Bool
	 */
	public static function check()
	{
		// Instancia o objeto Request
		$request = new Request();

		// Recupera a variavel de sessão USER_GUARD_IDENTIFICATION_KEY_12890
		$userStatus = $request->session("USER_GUARD_IDENTIFICATION_KEY_12890");

		// Verifica se a variavel foi achada
		if ($userStatus !== false) {  // Usuario logado
			// Retorna true
			return true;
		}
		else {  // Usuario não logado
			// Retorna false
			return false;
		}
	}

	/**
	 * Retorna o dado requisitado do usuario.
	 *
	 * @param Array $fields (opcional)
	 *
	 * @return void $userData
	 */
	public static function userData($fields=[])
	{
		// Define as variaveis do tipo self
		self::init();

		// Verifica se o usuario está logado
		if (self::check()) {
			// Verifica se algum campo especifico foi passado
			if (count($fields) == 0) {
				// Retorna apenas o id do usuario
				return $request->session("USER_GUARD_IDENTIFICATION_KEY_12890");
			}
			// Armazena a busca pelos campos
			$selectRes = DB::table(self::$table)::
			where([
				["id", "=", $request->session("USER_GUARD_IDENTIFICATION_KEY_12890")]
			])::
			select($fields);

			// Verifica o resultado da busca
			if (count($selectRes) > 0) {
				// Monta um array com os dados
				$userData = $selectRes[0];

				// Retorna os dados
				return $userData;
			}
			else {
				// Retorna falso
				return false;
			}
		}
		else {
			// Retorna falso
			return false;
		}
	}
}

?>