<?php

namespace App;

use DB\DB;

/**
 * Traduz os métodos de DB para tornar o model mais legivel, funcional e seguro.
 */
class Model
{
	/**
	 * Tabela unica a qual o model tem acesso.
	 *
	 * @var String $modelTable
	 */
	protected static $modelTable;	

	/**
	 * Campos em que model podera escrever, modificar e apagar dados.
	 *
	 * @var Array $modelFillable
	 */
	protected static $modelFillable;

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
	 * Executa um select * from.
	 *
	 * @return Array
	 */
	public static function all()
	{
		// Executa o método DB::all() com a tabela especificada na subclasse
		return DB::table(self::$modelTable)::all();
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
		// Executa o método DB::where
		DB::table(self::$modelTable)::where($whereParams);

		// Retorna __CLASS__ para encadear métodos
		return __CLASS__;
	}

	/**
	 * Define o Limit de um select.
	 *
	 * @param Int $limit
	 *
	 * @return __CLASS__
	 */
	public static function limit($limit)
	{
		// Executa o método DB::limit
		DB::table(self::$modelTable)::limit($limit);

		// Retorna __CLASS__ para encadear métodos
		return __CLASS__;
	}

	/**
	 * Define a ordenagem de um select.
	 *
	 * @param String $orderBy
	 *
	 * @return __CLASS__
	 */
	public static function orderBy($orderBy)
	{
		// Executa o método DB::orderBy
		DB::table(self::$modelTable)::orderBy($orderBy);

		// Retorna __CLASS__ para encadear métodos
		return __CLASS__;
	}

	/**
	 * Executa um select com os campos informados.
	 *
	 * @param Array $fields
	 *
	 * @return Array
	 */
	public static function select($fields)
	{
		// Retorna o resultado de DB::select
		return DB::table(self::$modelTable)::select($fields);
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
		// Loop em $toInsert
		foreach ($toInsert as $field => $value) {
			// Verifica se o campo está dentro de $modelFillable
			if (!in_array($field, self::$modelFillable)) {
				// Gera um erro
				throw new Exception("::create - O campo {$field} não pode ser escrito");
				
				return false;
			}
		}

		// Retorna o resultado de DB::create
		return DB::table(self::$modelTable)::create($toInsert);
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
		// Loop em $toInsert
		foreach ($toUpdate as $field => $value) {
			// Verifica se o campo está dentro de $modelFillable
			if (!in_array($field, self::$modelFillable)) {
				// Gera um erro
				throw new Exception("::update - O campo {$field} não pode ser escrito");
				
				return false;
			}
		}

		// Retorna o resultado de DB::update
		return DB::table(self::$modelTable)::update($toUpdate);
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
		// Loop em $toInsert
		foreach ($toDelete as $field => $value) {
			// Verifica se o campo está dentro de $modelFillable
			if (!in_array($field, self::$modelFillable)) {
				// Gera um erro
				throw new Exception("::delete - O campo {$field} não pode ser escrito");
				
				return false;
			}
		}

		// Retorna o resultado de DB::delete
		return DB::table(self::$modelTable)::delete($toDelete);
	}
}

?>