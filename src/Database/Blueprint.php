<?php

namespace DB;

/**
 * Funções que retornam strings para serem usadas em conjunto com Schema.
 */
class Blueprint
{
	/**
	 * Guarda o status da condição NULL de um campo.
	 *
	 * @var String $nullable
	 */
	private $nullable;

	/**
	 * Guarda o valor padrão de um campo.
	 *
	 * @var String $defaultValue
	 */
	private $defaultValue;

	/**
	 * Define o valor padrão das variaveis globais.
	 *
	 * @return void
	 */
	public function __construct()
	{
		// Define $nullable
		$this->nullable = "NOT NULL";

		// Define $defaultValue
		$this->defaultValue = "";
	}

	/**
	 * Configura o proximo campo a ser criado para aceitar NULL como valor.
	 *
	 * @return $this
	 */
	public function nullable()
	{
		// Define $nullable como vazio
		$this->nullable = "";

		// Retorna $this para encadear métodos
		return $this; 
	}

	/**
	 * Configura o proximo campo a ser criado com um valor padrão.
	 *
	 * @param $value
	 *
	 * @return $this
	 */
	public function default($value)
	{
		// Define $defaultValue com DEFAULT $value
		$this->defaultValue = "DEFAULT " . $value;

		// Retorna $this para encadear métodos
		return $this; 
	}

	/**
	 * String para um campo de id.
	 *
	 * @param String $name (opcional)
	 *
	 * @return String $field
	 */
	public function id($name="id")
	{
		// Monta o campo
		$field = "{$name} INT AUTO_INCREMENT PRIMARY KEY";

		// Retorna o campo
		return $field;
	}

	/**
	 * String para o campo de senha.
	 *
	 *
	 * @return String $field
	 */
	public function password()
	{
		// Monta o campo
		$field = "password CHAR(32) NOT NULL";

		// Retorna o campo $field
		return $field;
	}

	/**
	 * Cria um campo TIMESTAMP chamado created_at.
	 *
	 * @return String $field
	 */
	public function created_at()
	{
		// Monta os campos
		$field = "created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP";

		// Retorna o campo $field
		return $field;
	}

	/**
	 * Cria um campos TIMESTAMP chamado updated_at.
	 *
	 * @return String $field
	 */
	public function updated_at()
	{
		// Monta os campos
		$field = "updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP";

		// Retorna o campo $field
		return $field;
	}


	/**
	 * String para um campo de tipo VARCHAR.
	 *
	 * @param String $name
	 * @param Int $length (Opcional)
	 *
	 * @return String $field
	 */
	public function string($name, $length=255)
	{
		// Monta o campo
		$field = "{$name} VARCHAR({$length}) {$this->nullable} {$this->defaultValue}";

		// Define $nullable como NOT NULL para não afetar outros campos caso tenha sido alterada
		$this->nullable = "NOT NULL";

		// Define $defaultValue como vazio para não afetar outros campos caso tenha sido alterada
		$this->defaultValue = "";

		// Retorna o campo
		return $field;
	}

	/**
	 * String para um campo de tipo CHAR.
	 *
	 * @param String $name
	 * @param Int $length (Opcional)
	 *
	 * @return String $field
	 */
	public function char($name, $length=255)
	{
		// Monta o campo
		$field = "{$name} CHAR({$length}) {$this->nullable} {$this->defaultValue}";

		// Define $nullable como NOT NULL para não afetar outros campos caso tenha sido alterada
		$this->nullable = "NOT NULL";

		// Define $defaultValue como vazio para não afetar outros campos caso tenha sido alterada
		$this->defaultValue = "";

		// Retorna o campo
		return $field;
	}

	/**
	 * String para um campo de tipo TEXT.
	 *
	 * @param String $name
	 * @param Int $length (Opcional)
	 *
	 * @return String $field
	 */
	public function text($name)
	{
		// Monta o campo
		$field = "{$name} TEXT {$this->nullable} {$this->defaultValue}";

		// Define $nullable como NOT NULL para não afetar outros campos caso tenha sido alterada
		$this->nullable = "NOT NULL";

		// Define $defaultValue como vazio para não afetar outros campos caso tenha sido alterada
		$this->defaultValue = "";

		// Retorna o campo
		return $field;
	}

	/**
	 * String para um campo de tipo INT.
	 *
	 * @param String $name
	 * @param Int $length (Opcional)
	 *
	 * @return String $field
	 */
	public function integer($name, $length=255)
	{
		// Monta o campo
		$field = "{$name} INT({$length}) {$this->nullable} {$this->defaultValue}";

		// Define $nullable como NOT NULL para não afetar outros campos caso tenha sido alterada
		$this->nullable = "NOT NULL";

		// Define $defaultValue como vazio para não afetar outros campos caso tenha sido alterada
		$this->defaultValue = "";

		// Retorna o campo
		return $field;
	}

	/**
	 * String para um campo de tipo DECIMAL.
	 *
	 * @param String $name
	 * @param Int $length1
	 * @param Int $length2
	 *
	 * @return String $field
	 */
	public function decimal($name, $length1, $length2)
	{
		// Monta o campo
		$field = "{$name} DECIMAL({$length1}, {$length2}) {$this->nullable} {$this->defaultValue}";

		// Define $nullable como NOT NULL para não afetar outros campos caso tenha sido alterada
		$this->nullable = "NOT NULL";

		// Define $defaultValue como vazio para não afetar outros campos caso tenha sido alterada
		$this->defaultValue = "";

		// Retorna o campo
		return $field;
	}
}

?>