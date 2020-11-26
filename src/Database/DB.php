<?php

namespace DB;

use \PDO;
use Exception;

/**
 * CRUD para lidar com banco de dados
 */
class DB
{
	/**
	 * Guarda o objeto PDO.
	 *
	 * @var PDO $conn
	 */
	private static $conn;

	/**
	 * Guarda o nome da tabela a aplicar as querys das funções.
	 *
	 * @var String $tableName;
	 */
	public static $tableName;

	/**
	 * Guarda uma clausula WHERE para selects.
	 *
	 * @var String $where
	 */
	protected static $where;

	/**
	 * Guarda uma clausula LIMIT para selects.
	 *
	 * @var String $limit
	 */
	protected static $limit;

	/**
	 * Guarda uma clausula ORDER BY para selects.
	 *
	 * @var String $orderBy
	 */
	protected static $orderBy;
	
	/**
	 * Realiza a conexão com o banco de dados.
	 *
	 * @return void
	 */
	static function init()
	{
		// Tenta realizar a conexão com o banco de dados
		try {
			// Instancia o pdo
			self::$conn = new PDO("mysql:host=" . $GLOBALS["host"] . 
				";dbname=" . $GLOBALS["db"],
				$GLOBALS["user"],
				$GLOBALS["pass"],

				// Define a conexão como permanente
				array(
					PDO::ATTR_PERSISTENT => true
				)
			);

			// Verifica as configurações de debug
			if ($GLOBALS["debug"]) {
				// Muda as configurações para mostrar os erros
				self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			}

		} catch (Exception $e) {
			// Retorna o erro
			throw new Exception("Erro ao criar a conexão pdo. " . $e->getMessage());
			
			return false;
		}
	}

	/**
	 * Seleciona a tabela a aplicar as funções encadeadas.
	 *
	 * @param String $tableName
	 *
	 * @return __CLASS__
	 */
	public static function table($tableName)
	{
		// Verifica se um valor foi informado
		if (empty($tableName)) {
			// Gera um erro
			throw new Exception("DB::table() - O nome da tabela não pode ser vazio");
			
			return false;
		}

		// Define o parametro self::$tableName como o informado
		self::$tableName = $tableName;

		// Retorna __CLASS__ para encadear métodos
		return __CLASS__;
	}

	/**
	 * Executa um SELECT * FROM.
	 *
	 * @return Array $result
	 */
	public static function all()
	{
		// Instancia o objeto self::$conn
		self::init();

		// Verifica se uma tabela foi informada
		if (empty(self::$tableName)) {
			// Gera um erro
			throw new Exception("DB::all() - Nenhuma tabela informada.");
			
			return false;
		}

		// Armazena a query
		$selectQuery = "SELECT * FROM " . self::$tableName;

		// Guarda o Objeto retornado da consulta
		$queryReturn = self::$conn->query($selectQuery);

		// Verifica se retornou algum resultado
		if (!empty($queryReturn)) {
			// Executa um fetch all na consulta
			$result = $queryReturn->fetchAll(PDO::FETCH_ASSOC);

			// Retorna o resultado
			return $result;
		}
		else {
			// Retorna falso caso não hajam resultados
			return false;
		}
	}

	/**
	 * Define o where de um select.
	 * 
	 * @param Array $whereParams
	 *
	 * @return __CLASS__
	 */
	public static function where($whereParams)
	{
		// Variavel para armazenar o resultado
		$where = '';

		// Verifica se o array tem algum indice definido
		if (count($whereParams) > 0) {
			// Atualiza o where
			$where .= 'WHERE ';

			// Loop em $whereParams
			foreach ($whereParams as $key => $value) {
				// Verifica o tipo de valor para se comparar
				if (gettype($value[2]) == 'string') {
					// Atualiza a clausula where com tipo string
					$where .= $value[0] . ' ' . $value[1] . ' ' . "\"" . $value[2] . "\"";
				}
				else {
					// Atualiza a clausula where com tipo integer/bool
					$where .= $value[0] . ' ' . $value[1] . ' ' . $value[2];
				}

				// Verifica se existe mais alguma condição
				if (isset($whereParams[$key+1])) {
					// Adiciona um AND no $where
					$where .= ' AND ';
				}
			}

			// Define o $where global com o local
			self::$where = $where;

			// Retorna __CLASS__ para encadear métodos.
			return __CLASS__;
		}
		else {
			// Define o $where global como vazio
			self::$where = '';

			// Retorna __CLASS__ para encadear métodos
			return __CLASS__;
		}
	}

	/**
	 * Define o Limit de um select
	 *
	 * @param Int $limit
	 *
	 * @return __CLASS__
	 */
	public static function limit($limit)
	{
		// Verifica se algum valor foi informado
		if (!empty($limit)) {
			// Define o $limit global com o local
			self::$limit = "LIMIT " . $limit;

			// Retorna __CLASS__ para encadear métodos
			return __CLASS__;
		}
		else {
			// Define o $limit global como vazio
			self::$limit = '';

			// Retorna __CLASS__ para encadear métodos
			return __CLASS__;
		}
	}

	/**
	 * Define a ordenagem de um select
	 *
	 * @param String $orderBy
	 *
	 * @return __CLASS__
	 */
	public static function orderBy($orderBy)
	{
		// Verifica se algum valor foi informado
		if (!empty($orderBy)) {
			// Define o $orderBy global com o local
			self::$orderBy = "ORDER BY " . $orderBy;

			// Retorna __CLASS__ para encadear métodos
			return __CLASS__;
		}
		else {
			// Define o $orderBy global como vazio
			self::$orderBy = '';

			// Retorna __CLASS__ para encadear métodos
			return __CLASS__;
		}
	}

	/**
	 * Executa um select com os campos informados.
	 *
	 * @param Array $fields
	 *
	 * @return Array $return
	 */
	public static function select($fields)
	{
		// Instancia o objeto self::$conn
		self::init();

		// Verifica se uma tabela foi informada
		if (empty(self::$tableName)) {
			// Gera um erro
			throw new Exception("DB::select() - Nenhuma tabela informada.");
			
			return false;
		}

		// Verifica se array tem algum indice definido
		if (count($fields) > 0) {
			// Armazena a query
			$selectQuery = 'SELECT ';

			// Loop em $fields
			foreach ($fields as $key => $value) {
				// Adiciona o especificado a query
				$selectQuery .= $value;

				// Verifica se existe mais algum campo
				if (isset($fields[$key+1])) {
					// Adiciona uma virgula para o próximo campo
					$selectQuery .= ', ';
				}
			}

			// Adiciona o parametro FROM a query
			$selectQuery .= " FROM " . self::$tableName;

			// Adiciona o WHERE a query
			$selectQuery .= " " . self::$where;

			// Adiciona o LIMIT a query
			$selectQuery .= " " . self::$limit;

			// Adiciona o ORDER BY a query
			$selectQuery .= " " . self::$orderBy;

			// Tenta executar a consulta
			try {
				// Armazena o objeto da query
				$queryReturn = self::$conn->query($selectQuery);

				// Verifica se algum resultado foi retornado
				if (!empty($queryReturn)) {
					// Executa um fetch all na consulta
					$return = $queryReturn->fetchAll(PDO::FETCH_ASSOC);

					// Retorna o resultado
					return $return;
				}
				else {
					// Retorna false caso não hajam resultados
					return false;
				}
			} catch (Exception $e) {
				// Retorna o erro
				throw new Exception("DB::select() - Ocorreu um erro: " . $e->getMessage());
				
				return false;
			}
		}
	}

	/**
	 * Realiza um insert.
	 *
	 * @param Array $toInsert
	 *
	 * @return Bool
	 */
	public static function create($toInsert)
	{
		// Instancia o objeto self::$conn
		self::init();

		// Verifica se uma tabela foi informada
		if (empty(self::$tableName)) {
			// Gera um erro
			throw new Exception("DB::create() - Nenhuma tabela informada.");
			
			return false;
		}

		// Verifica se algum valor foi definido
		if (count($toInsert) > 0) {
			// Query de insert
			$insertQuery = 'INSERT INTO ' . self::$tableName;

			// Querys para controle
			$fieldsString = ' (';
			$valuesString = ' VALUES (';

			// Loop em $toInsert
			foreach ($toInsert as $field => $value) {
				// String com os campos
				$fieldsString .= $field;

				// Verifica qual o tipo de dado passado
				if (gettype($value) == 'string') {
					// Atualiza $valueString com string
					$valuesString .= "'{$value}'";
				}
				elseif (gettype($value) == 'integer') {
					// Atualiza $valueString com int
					$valuesString .= $value;
				}

				// Verifica se tem mais campos
				if ($value != end($toInsert)) {
					// Adiciona mais um campo
					$fieldsString .= ', ';

					// Adiciona outro value
					$valuesString .= ', ';
				}
				else {
					// Encerra a string de campos
					$fieldsString .= ') ';

					// Encerra a string de values
					$valuesString .= ')';

					// Atualiza a query de INSERT
					$insertQuery .= $fieldsString . $valuesString;
				}
			}

			// Tenta executar o insert
			try {
				// Prepara a query
				$queryPreprare = self::$conn->prepare($insertQuery);

				// Executa a query
				$queryPreprare->execute();

				// Retorna true
				return true;
			} catch (Exception $e) {
				// Retorna um erro
				throw new Exception("DB::create() - Ocorreu o seguinte erro: " . $e->getMessage());
				
				return false;
			}
		}
	}

	/**
	 * Recupera o ultimo id inserido na tabela.
	 *
	 * @return Int $lastId
	 */
	public static function lastId()
	{
		// Instancia o objeto self::$conn
		self::init();

		// Verifica se uma tabela foi informada
		if (empty(self::$tableName)) {
			// Gera um erro
			throw new Exception("DB::update() - Nenhuma tabela informada.");
			
			return false;
		}

		// Recupera o ultimo id
		$lastId = self::$conn->lastInsertId();

		// Retorna o valor recuperado
		return $lastId;
	}

	/**
	 * Realiza um update.
	 *
	 * @param Array $toUpdate
	 * @param String $operator (opcional)
	 *
	 * @return Bool
	 */
	public static function update($toUpdate, $operator="AND")
	{
		// Instancia o objeto self::$conn
		self::init();

		// Verifica se uma tabela foi informada
		if (empty(self::$tableName)) {
			// Gera um erro
			throw new Exception("DB::update() - Nenhuma tabela informada.");
			
			return false;
		}

		// Verifica se algum valor foi definido
		if (count($toUpdate) > 0) {
			// Query de update
			$updateQuery = "UPDATE " . self::$tableName . " SET ";

			// Condição where
			$where = "WHERE ";

			// Variaveis para guardar campos
			$set = '';

			// Loop em $toInsert
			foreach ($toUpdate as $field => $value) {
				// Pega o nome do campo
				$set .= "{$field} = ";
				$where .= "{$field} ";

				// Verifica o tipo de dado do valor
				if (isset($value[0]) && gettype($value[0]) == 'string') {
					// Adiciona o valor como string
					$set .= "'{$value[0]}'";
				}
				elseif (isset($value[0]) && gettype($value[0]) == 'integer') {
					// Adiciona o valor como int
					$set .= "{$value[0]}";
				}

				// Verifica o tipo de dado do valor
				if (isset($value[2]) && gettype($value[2]) == 'string') {
					// Adiciona o valor como string
					$where .= "{$value[1]} '{$value[2]}'";
				}
				elseif (isset($value[2]) && gettype($value[2]) == 'integer') {
					// Adiciona o valor como int
					$where .= "{$value[1]} {$value[2]}";
				}
				
				// Guarda as chaves de $toUpdate para verificação
				$keys = array_keys($toUpdate);

				// Verifica se existem mais valores
				if ($field != end($keys)) {
					// Adiciona a virgula ao set
					$set .= ', ';

					// Adiciona um and/or ao where
					$where .= " {$operator} ";
				}
				else {
					// Monta a query
					$updateQuery .= "{$set} {$where}";

					// Encerra o loop
					break;
				}
			}

			// Tenta executar o update
			try {
				// Prepara a query
				$queryPreprare = self::$conn->prepare($updateQuery);

				// Executa a query
				$queryPreprare->execute();

				// Retorna true
				return true;
			} catch (Exception $e) {
				// Retorna um erro
				throw new Exception("DB::update() - Ocorreu o seguinte erro: " . $e->getMessage());
				
				return false;
			}
		}
	}

	/**
	 * Deleta registros.
	 *
	 * @param Array $toDelete
	 * @param String $operator (opcional)
	 *
	 * @return Bool
	 */
	public static function delete($toDelete, $operator="AND")
	{
		// Instancia o objeto self::$conn
		self::init();

		// Verifica se uma tabela foi informada
		if (empty(self::$tableName)) {
			// Gera um erro
			throw new Exception("DB::update() - Nenhuma tabela informada.");
			
			return false;
		}

		// Verifica se algum valor foi definido
		if (count($toDelete) > 0) {
			// Query de delete
			$deleteQuery = "DELETE FROM " . self::$tableName . " ";

			// Condição where
			$where = "WHERE ";

			// Loop em $toDelete
			foreach ($toDelete as $key => $values) {
				// Pega o campo
				$where .= "{$values[0]} ";

				// Verifica o tipo de dado do valor
				if (isset($values[1]) && gettype($values[2]) == 'string') {
					// Adiciona a comparação com tipo string
					$where .= "{$values[1]} '$values[2]' ";
				}
				elseif (isset($values[1]) && gettype($values[2]) == 'integer') {
					// Adiciona a comparação com tipo int
					$where .= "{$values[1]} $values[2] ";
				}

				// Guarda as chaves de $toUpdate para verificação
				$keys = array_keys($toDelete);

				// Verifica se existem mais valores
				if ($key != end($keys)) {
					// Adiciona um and/or ao where
					$where .= "{$operator} ";
				}
				else {
					// Monta a query
					$deleteQuery .= "{$where}";

					// Encerra o loop
					break;
				}
			}

			// Tenta executar o delete
			try {
				// Prepara a query
				$queryPreprare = self::$conn->prepare($deleteQuery);

				// Executa a query
				$queryPreprare->execute();

				// Retorna true
				return true;
			} catch (Exception $e) {
				// Retorna um erro
				throw new Exception("DB::delete() - Ocorreu o seguinte erro: " . $e->getMessage());
				
				return false;
			}
		}
	}
}
?>