#!/usr/bin/env php
<?php

require_once "vendor/autoload.php";

use App\Maker;

/**
 * Usa os comandos do usuario para executar os métodos da classe Maker.
 */
class Make
{
	/**
	 * Guarda um objeto Maker
	 *
	 * @var Maker $maker
	 */
	protected static $maker;

	/**
	 * Armazena o comando passado pelo usuario.
	 *
	 * @var String $command
	 */
	public static $command;

	/**
	 * Armazena o argumento passado pelo usuario.
	 *
	 * @var String $argument
	 */
	public static $argument;

	/**
	 * Guarda os comandos que precisam de argumentos.
	 *
	 * @var Array $argumentedCommands
	 */
	public static $argumentedCommands;

	/**
	 * Guarda os comandos que não precisam de argumentos.
	 *
	 * @var Array $nonArgumentedCommands
	 */
	public static $nonArgumentedCommands;

	/**
     * Define as variaveis de acesso global dentro do escopo da classe.
     *
     * @param Array $argv
	 *
	 * @return void
	 */
	protected static function init($argv)
	{
		// Instancia um objeto Maker
		self::$maker = new Maker();

		// Define os comandos passados no script
		self::$command = isset($argv[1]) ? $argv[1] : "";
		self::$argument = isset($argv[2]) ? $argv[2] : "";


		// Pega a lista de comandos
		self::$argumentedCommands = self::$maker->argumentedCommands;
		self::$nonArgumentedCommands = self::$maker->nonArgumentedCommands;
	}

	/**
	 * Verifica qual o tipo de comando passado.
	 *
	 * @return String
	 */
	protected static function commandType()
	{
		// Verifica se o comando está dentro de algum dos arrays de comandos
		if (in_array(self::$command, self::$argumentedCommands)) {  // Comando que precisa de argumento
			// Verifica se algum parametro foi passado
			if (empty(self::$argument)) {
				// Retorna que falta um argumento
				return "expecting-arguments";
			}
			else {
				// Retorna que é um comando com argumento
				return "argumented";
			}
		}
		elseif (in_array(self::$command, self::$nonArgumentedCommands)) {  // Comando que não precisa de argumento
			// Retorna que é um comando sem argumento
			return "non-argumented";
		}
		else {  // Comando inválido
			// Retorn que é um comando invalido
			return "invalid";
		}
	}

	/**
	 * Executa o comando solicitado caso ele exista.
	 *
	 * @param Array $argv
	 *
	 * @return void
	 */
	public static function run($argv)
	{
		// Define as variaveis da classe
		self::init($argv);

		// Verifica se algum comando foi passado
		if (empty(self::$command)) {
			// String que será exibida ao usuario
			$outputString = "Nenhum comando especificado. Para listar todos os comandos use [highlight]help[/highlight].";

			// Exibe a string
			self::$maker->showOutput($outputString);

			// Termina o script
			exit();
		}

		// Verifica qual tipo de comando foi passado
		if (self::commandType() == "non-argumented") {  // Comando que não necessita de 
			// Guarda o método da classe Maker correspondente ao controller.
			$command = array_search(self::$command, self::$nonArgumentedCommands);

			// Executa o comando
			self::$maker->$command();

			// Termina o script
			exit();
		}
		elseif (self::commandType() == "argumented") {
			// Guarda o método da classe Maker correspondente ao controller.
			$command = array_search(self::$command, self::$argumentedCommands);

			// Executa o comando
			self::$maker->$command(self::$argument);

			// Termina o script
			exit();
		}
		elseif (self::commandType() == "expecting-arguments") {
			// String que será exibida ao usuario
			$outputString = "[red]" . self::$command . "[/red] - é necessário um argumento.";

			// Exibe a string
			self::$maker->showOutput($outputString);

			// Termina o script
			exit();
		}
		else {
			// String que será exibida ao usuario
			$outputString = "[red]Operação inválida[/red]: " . self::$command;

			// Exibe a string
			self::$maker->showOutput($outputString);

			// Termina o script
			exit();
		}
	}
}

// Auto-executa o script
Make::run($argv);

?>