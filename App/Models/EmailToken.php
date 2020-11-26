<?php

namespace App;

/**
 * Model da tabela email_tokens.
 */
class EmailToken extends Model
{
	/**
	 * Tabela unica a qual o model tem acesso.
	 *
	 * @var String $table
	 */
	private static $table = "email_tokens";	

	/**
	 * Campos em que model podera escrever, modificar e apagar dados.
	 *
	 * @var Array $fillable
	 */
	private static $fillable = ["user_id", "token"];

	/**
	 * Faz com que as variaveis tenham valores.
	 *
	 * @return void
	 */
	public static function init()
	{
		// Define $table e $fillable
		self::$modelTable = self::$table;
		self::$modelFillable = self::$fillable;
	}
}

// Executa o construct do model
EmailToken::init();

?>