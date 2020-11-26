<?php

namespace App;

use Exception;

/**
 * Classe responsavel pela criação de arquivos e realização das migrações.
 */
class Maker
{
	/**
	 * Guarda o conteudo padrão de um controller.
	 *
	 * @var String $controllerContent
	 */
	protected $controllerContent;

	/**
	 * Guarda o conteudo padrão de um model.
	 *
	 * @var String $modelContent
	 */
	protected $modelContent;

	/**
	 * Guarda o conteudo padrão de um middleware.
	 *
	 * @var String $middlewareContent
	 */
	protected $middlewareContent;

	/**
	 * Guarda o conteudo padrão de uma migration.
	 *
	 * @var String $migrationContent
	 */
	protected $migrationContent;

	/**
	 * Guarda o caminho para novos controllers.
	 *
	 * @var String $controllersPath
	 */
	protected $controllersPath;

	/**
	 * Guarda o caminho para novos models.
	 *
	 * @var String $modelsPath
	 */
	protected $modelsPath;

	/**
	 * Guarda o caminho para novos middlewares.
	 *
	 * @var String $middlewaresPath
	 */
	protected $middlewaresPath;

	/**
	 * Guarda o caminho para novas migrations.
	 *
	 * @var String $migrationsPath
	 */
	protected $migrationsPath;

	/**
	 * Guarda a url da página do GitHub do projeto.
	 *
	 * @var String $sourceUrl
	 */
	public $sourceUrl;

	/**
	 * Guarda a url da página do GitHub da documentação do projeto.
	 *
	 * @var String $docsUrl
	 */
	public $docsUrl;

	/**
	 * Guarda a versão da aplicação.
	 *
	 * @var String $version
	 */
	public $version;

	/**
	 * Guarda os comandos que precisam de argumentos.
	 *
	 * @var Array $argumentedCommands
	 */
	public $argumentedCommands;

	/**
	 * Guarda os comandos que não precisam de argumentos.
	 *
	 * @var Array $nonArgumentedCommands
	 */
	public $nonArgumentedCommands;

	/**
	 * Define as variaveis de acesso global dentro do escopo da classe.
	 *
	 * @return void
	 */
	function __construct()
	{
		// Define o fuso-horario
		date_default_timezone_set("America/Sao_Paulo");

		// Define a constante DIR
		define("DIR", DIRECTORY_SEPARATOR);
		
		// Define o conteudo padrão de cada tipo de arquivo
		$this->controllerContent = "PD9waHAgCgpuYW1lc3BhY2UgQXBwXEh0dHBcQ29udHJvbGxlcjsKCi8qKgogKiAKICovCmNsYXNzIE5BTUVIRVJFIGV4dGVuZHMgQ29udHJvbGxlcgp7CgkvKioKCSAqIAoJICoKCSAqIEByZXR1cm4gCgkgKi8KfQoKPz4=";
		
		$this->modelContent = "PD9waHAKCm5hbWVzcGFjZSBBcHA7CgovKioKICogCiAqLwpjbGFzcyBOQU1FSEVSRSBleHRlbmRzIE1vZGVsCnsKCS8qKgoJICogVGFiZWxhIHVuaWNhIGEgcXVhbCBvIG1vZGVsIHRlbSBhY2Vzc28uCgkgKgoJICogQHZhciBTdHJpbmcgJHRhYmxlCgkgKi8KCXByaXZhdGUgc3RhdGljICR0YWJsZSA9ICIiOwkKCgkvKioKCSAqIENhbXBvcyBlbSBxdWUgbW9kZWwgcG9kZXJhIGVzY3JldmVyLCBtb2RpZmljYXIgZSBhcGFnYXIgZGFkb3MuCgkgKgoJICogQHZhciBBcnJheSAkZmlsbGFibGUKCSAqLwoJcHJpdmF0ZSBzdGF0aWMgJGZpbGxhYmxlID0gW107CgoJLyoqCgkgKiBGYXogY29tIHF1ZSBhcyB2YXJpYXZlaXMgdGVuaGFtIHZhbG9yZXMKCSAqCgkgKiBAcmV0dXJuIHZvaWQKCSAqLwoJcHVibGljIHN0YXRpYyBmdW5jdGlvbiBpbml0KCkKCXsKCQkvLyBEZWZpbmUgJHRhYmxlIGUgJGZpbGxhYmxlCgkJc2VsZjo6JG1vZGVsVGFibGUgPSBzZWxmOjokdGFibGU7CgkJc2VsZjo6JG1vZGVsRmlsbGFibGUgPSBzZWxmOjokZmlsbGFibGU7Cgl9Cn0KCi8vIEV4ZWN1dGEgbyBjb25zdHJ1Y3QgZG8gbW9kZWwKTkFNRUhFUkU6OmluaXQoKTsKCj8+";

		$this->middlewareContent = "PD9waHAKCm5hbWVzcGFjZSBBcHBcSHR0cFxNaWRkbGV3YXJlOwoKdXNlIEFwcFxIdHRwXFJlcXVlc3Q7CgovKioKICogCiAqLwpjbGFzcyBOQU1FSEVSRQp7CgkKCS8qKgoJICogVmVyaWZpY2Egc2UgbyB1c3VhcmlvIGN1bXByZSBvcyByZXF1aXNpdG9zIHBhcmEgdGVyIGFjZXNzbyBhIHBhZ2luYS4KCSAqCgkgKiBAcmV0dXJuIEJvb2wKCSAqLwoJcHVibGljIGZ1bmN0aW9uIGNoZWNrKCkKCXsKCQkvLyAKCX0KCgkvKioKCSAqIE3DqXRvZG8gYSBzZXIgYWNpb25hZG8gY2FzbyBvIHVzdWFyaW8gbsOjbyBjdW1wcmEgb3MgcmVxdWlzaXRvcy4KCSAqCgkgKiBAcmV0dXJuIAoJICovCglwdWJsaWMgZnVuY3Rpb24gZGVuaWVkKCkKCXsKCQkvLwoJfQp9Cgo/Pg==";

		$this->migrationContent = "PD9waHAKCnVzZSBEQlxTY2hlbWE7CnVzZSBEQlxCbHVlcHJpbnQ7CgovLyBJbnN0YW5jaWEgb2JqZXRvIGJsdWVwcmludAokdGFibGUgPSBuZXcgQmx1ZXByaW50KCk7CgovLyBFeGNsdWkgYSB0YWJlbGEgY2FzbyBqw6EgZXhpc3RhClNjaGVtYTo6ZG93bigiTkFNRUhFUkUiKTsKCi8vIENyaWEgYSB0YWJlbGEKU2NoZW1hOjp1cCgiTkFNRUhFUkUiLCBbCgkkdGFibGUtPmlkKCkKXSk7Cgo/Pg==";

		// Define o caminho para cada arquivo
		$this->controllersPath = "App" . DIR . "Http" . DIR . "Controllers" . DIR;
		$this->modelsPath = "App" . DIR . "Models" . DIR;
		$this->middlewaresPath = "App" . DIR . "Http" . DIR . "Middlewares" . DIR;
		$this->migrationsPath = "Database" . DIR . "migrations" . DIR;

		// Configura a versão da aplicação
		$this->sourceUrl = "https://github.com/eEmmy/EmmyVC/";
		$this->docsUrl = "https://github.com/eEmmy/EmmyVC-Doc/";
		$this->version = "1.0.0";

		// Armazena os commandos que precisam de argumentos
		$this->argumentedCommands = array(
			"createNewController" => "controller",
			"createNewModel" => "model",
			"createNewMiddleware" => "middleware"
		);

		// Armazena os commandos que não precisam de argumentos
		$this->nonArgumentedCommands = array(
			"showHelp" => "help",
			"migrate" => "migrate"
		);
	}

	/**
	 * Monta o caminho do arquivo.
	 *
	 * @param String $filename
	 * @param String $filePath
	 *
	 * @return String $filePath
	 */
	protected function getPath($filename, $filePath)
	{
		// Coloca a primeira letra como maiuscula
		$filename = ucfirst($filename);

		// Adiciona o path e a extensão ao arquivo
		$filePath = $filePath . $filename . ".php";

		// Retorna o nome formatado
		return $filePath;
	}

	/**
	 * Cria um novo arquivo com o caminho informado.
	 *
	 * @param String $filePath
	 * @param String $classname
	 * @param String $fileContent
	 *
	 * @return Bool
	 */
	protected function writeFile($filePath, $classname, $fileContent)
	{
		// Verifica se o arquivo existe
		if (file_exists($filePath)) {
			// Retorna um erro
			return "O arquivo {$classname} já existe.";
		}

		// Decodifica o conteudo do arquivo
		$fileContent = base64_decode($fileContent);

		// Substitui NAMEHERE por $classname em $fileContent
		$fileContent = str_replace("NAMEHERE", $classname, $fileContent);

		// Tenta criar o arquivo
		try {
			// Cria o arquivo em modo de escrita
			$file = fopen($filePath, "w+");

			// Escreve o conteudo dentro do arquivo
			fwrite($file, $fileContent);

			// Encerra a manipulação do arquivo
			fclose($file);

			// Retorna true
			return true;
		} catch (Exception $e) {
			// Retorna o erro
			return $e->getMessage();
		}
	}

	/**
	 * Cria um novo controller.
	 *
	 * @param String $controllerName
	 *
	 * @return void
	 */
	public function createNewController($controllerName)
	{
		// Pega o caminho completo do arquivo a ser gerado
		$controllerPath = $this->getPath($controllerName, $this->controllersPath);

		// Transforma a primeira letra do nome do controller em maiuscula 
		$controllerName = ucfirst($controllerName);

		// Cria o arquivo
		$writeResult = $this->writeFile($controllerPath, $controllerName, $this->controllerContent);

		// Verifica se o arquivo foi escrito com sucesso
		if ($writeResult === true) {
			// String que será exibida ao usuario
			$outputString = "[green]{$controllerName}[/green] criado com sucesso.";

			// Exibe a string
			$this->showOutput($outputString);
		}
		else {
			// String que será exibida ao usuario
			$outputString = "[red]Erro na criação do controller.[/red] - {$writeResult}";

			// Exibe a string
			$this->showOutput($outputString);
		}
	}

	/**
	 * Cria um novo model.
	 *
	 * @param String $modelName
	 *
	 * @return void
	 */
	public function createNewModel($modelName)
	{
		// Salva o nome passado para criar uma migration
		$migrationName = $modelName;

		// Quebra o nome do arquivo
		$modelName = explode("_", $modelName);

		// Loop em $modelName
		foreach ($modelName as $key => $value) {
			// Coloca a primeira letra como maiuscula
			$modelName[$key] = ucfirst($value);
		}

		// Une o array como uma string novamente
		$modelName = implode("_", $modelName);

		// Retira os travessões (_) de $modelName
		$modelName = str_replace("_", "", $modelName);

		// Pega o path do model
		$modelPath = $this->getPath($modelName, $this->modelsPath);

		// Cria o arquivo
		$writeResult = $this->writeFile($modelPath, $modelName, $this->modelContent);

		// Verifica se o model foi escrito com sucesso
		if ($writeResult === true) {
			// String que será exibida ao usuario
			$outputString = "[green]{$modelName}[/green] criado com sucesso.";

			// Exibe a string
			$this->showOutput($outputString);

			// Chama a criação da migration
			$this->createNewMigration($migrationName);
		}
		else {
			// String que será exibida ao usuario
			$outputString = "[red]Erro na criação do model.[/red] - {$writeResult}";

			// Exibe a string
			$this->showOutput($outputString);
		}
	}

	/**
	 * Cria um novo model e uma nova migration.
	 *
	 * @param String $modelName
	 *
	 * @return void
	 */
	public function createNewMigration($migrationName)
	{
		// Monta o nome correto da migration
		$migrationFileName = "create_table_" . $migrationName . "s_" . date("Y_m_d");

		// Pega o path dos arquivos
		$migrationPath = $this->getPath($migrationFileName, $this->migrationsPath);

		// Substitui o caractere maiusculo do nome da migration por um minusculo
		$migrationPath = str_replace(ucfirst($migrationFileName), $migrationFileName, $migrationPath);

		// Cria o arquivo
		$writeResult = $this->writeFile($migrationPath, $migrationName . "s", $this->migrationContent);

		// Verifica se o migration foi escrito com sucesso
		if ($writeResult === true) {
			// String que será exibida ao usuario
			$outputString = "[green]{$migrationFileName}[/green] criada com sucesso.";

			// Exibe a string
			$this->showOutput($outputString);
		}
		else {
			// String que será exibida ao usuario
			$outputString = "[red]Erro na criação da migration.[/red] - {$writeResult}";

			// Exibe a string
			$this->showOutput($outputString);
		}
	}

	/**
	 * Cria um novo middleware.
	 *
	 * @param String $middlewareName
	 *
	 * @return void
	 */
	public function createNewMiddleware($middlewareName)
	{
		// Pega o caminho completo do arquivo a ser gerado
		$middlewarePath = $this->getPath($middlewareName, $this->middlewaresPath);

		// Transforma a primeira letra do nome do middleware em maiuscula 
		$middlewareName = ucfirst($middlewareName);

		// Cria o arquivo
		$writeResult = $this->writeFile($middlewarePath, $middlewareName, $this->middlewareContent);

		// Verifica se o arquivo foi escrito com sucesso
		if ($writeResult === true) {
			// String que será exibida ao usuario
			$outputString = "[green]{$middlewareName}[/green] criado com sucesso.";

			// Exibe a string
			$this->showOutput($outputString);
		}
		else {
			// String que será exibida ao usuario
			$outputString = "[red]Erro na criação do middleware.[/red] - {$writeResult}";

			// Exibe a string
			$this->showOutput($outputString);
		}
	}

	/**
	 * Roda todas as migrations.
	 *
	 * @return void
	 */
	public function migrate()
	{
		// Armazena as migrations
		$migrations = dir($this->migrationsPath);

		// Loop em $migrations
		while ($migration = $migrations->read()) {
			// Verifica se o arquivo atual não é igual a ".", ".." ou se é vazio
			if ($migration == "." || $migration == ".." || empty($migration)) {
				// Pula o arquivo
				continue;
			}

			// Inclui o arquivo
			include_once $this->migrationsPath . $migration;

			// Retira o ".php" de $migration
			$migration = str_replace(".php", "", $migration);

			// String que será exibida ao usuario
			$outputString = "[green]{$migration}[/green] migrada com sucesso.";

			// Exibe a string
			$this->showOutput($outputString);
		}
	}

	/**
	 * Exibe o menu de ajuda.
	 *
	 * @return void
	 */
	public function showHelp()
	{
		// Guarda as informações do programa
		$programInfo[0] = "------------------------------------------------------------------------------|";
		$programInfo[1] = "EmmyVC {$this->version} by Emmy Gomes.";
		$programInfo[2] = "2020 - " . date("Y") . "\n";
		$programInfo[3] = "[highlight]Código fonte disponivel em:[/highlight] {$this->sourceUrl}";
		$programInfo[4] = "[highlight]Documentação oficial disponivel em[/highlight]: {$this->docsUrl}\n";
		$programInfo[5] = "---------------------------------- [highlight]Comandos[/highlight] ----------------------------------|\n";

		// Guarda o nome dos comandos e suas descrições
		$commandsInfo = array(
			"help" => "\t    - Exibe esse menu.",
			"controller" => " - Cria um novo controller.",
			"model" => "\t    - Cria um novo model e uma nova migration para esse model.",
			"middleware" => " - Cria um novo middleware.",
			"migrate" => "    - Executa todas as migrations."
		);

		// Exibe o cabeçalho para o usuario
		$this->showOutput($programInfo[0]);
		$this->showOutput($programInfo[1]);
		$this->showOutput($programInfo[2]);
		$this->showOutput($programInfo[3]);
		$this->showOutput($programInfo[4]);
		$this->showOutput($programInfo[5]);

		// Loop em $commandsInfo
		foreach ($commandsInfo as $command => $description) {
			// String que será exibida ao usuario
			$outputString = "[highlight]{$command}[/highlight] {$description}";

			// Exibe a string
			$this->showOutput($outputString);
		}

		// Exibe a linha final para o usuario
		$this->showOutput("------------------------------------------------------------------------------|");
	}

	/**
	 * Exibe uma saida de dados formatada.
	 *
	 * @param String $outputString
	 *
	 * @return void
	 */
	public function showOutput($outputString)
	{
		// Guarda o código das cores
		$colors = array(
			// Vermelho
			"[red]" => "\e[31m",
			"[/red]" => "\e[39m",
			// Verde
			"[green]" => "\e[32m",
			"[/green]" => "\e[39m",
			// Destaque em Magenta
			"[highlight]" => "\e[95m",
			"[/highlight]" => "\e[39m"
		);

		// Loop em $colors
		foreach ($colors as $colorName => $colorCode) {
			// Substitui o codigo da cor pelo seu codigo em $outputString
			$outputString = str_replace($colorName, $colorCode, $outputString);
		}

		// Converte $outputString para UTF-8
		$outputString = utf8_encode($outputString);
		$outputString = utf8_decode($outputString);

		// Exibe a string de saida formatada
		echo $outputString;

		// Exibe uma quebra de linha
		echo "\n";
	}

	/**
	 * Lista os comandos que precisam de argumentos.
	 *
	 * @return Array $commands
	 */
	public function listArgumentedCommands()
	{
		// Recupera os comandos que precisam de argumentos
		$commands = $this->argumentedCommands;

		// Retorna os comandos
		return $commands;
	}

	/**
	 * Lista os comandos que não precisam de argumentos.
	 *
	 * @return Array $commands
	 */
	public function listNonArgumentedCommands()
	{
		// Recupera os comandos que não precisam de argumentos
		$commands = $this->nonArgumentedCommands;

		// Retorna os comandos
		return $commands;
	}
}