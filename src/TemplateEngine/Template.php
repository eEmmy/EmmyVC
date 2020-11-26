<?php

namespace TemplateEngine;

/**
 * Template engine.
 */
class Template
{
	/**
	 * Guarda o conteudo da view.
	 *
	 * @var String $viewContent
	 */
	protected static $viewContent;

	/**
	 * Guarda o conteudo de uma view extendida.
	 *
	 * @var String $extendedViewContent
	 */
	protected static $extendedViewContent;

	/**
	 * Define o conteudo da view para o informado.
	 *
	 * @param String $viewContent
	 * 
	 * @return void
	 */
	protected static function setViewContent($viewContent)
	{
		// Define o conteudo da view
		self::$viewContent = $viewContent;
	}

	/**
	 * Define o conteudo da view extendida para o informado.
	 *
	 * @param String $extendedViewContent
	 * 
	 * @return void
	 */
	protected static function setExtendedViewContent($extendedViewContent)
	{
		// Define o conteudo da view
		self::$extendedViewContent = $extendedViewContent;
	}

	/**
	 * Muda os termos entre colchetes duplos ({{ }}) para o valor respectivo de suas variaveis.
	 *
	 * @param Array $params
	 *
	 * @return void
	 */
	protected static function setParams($params)
	{
		// Guarda o conteudo da view formatado
		$newViewContent = self::$viewContent;

		// Loop em $params
		foreach ($params as $key => $value) {
			// Verifica se $value é um array
			if (gettype($value) == "array") {
				// Pula para o proximo item
				continue;
			}

			// Substitui $key por $value
			$newViewContent = str_replace("{{ ".$key." }}", $value, self::$viewContent);
		}

		// Define o novo conteudo da view
		self::setViewContent($newViewContent);
	}

	/**
	 * Troca as chaves @if/@elseif/@else/@endif pelo respectivo em php.
	 *
	 * @return void
	 */
	protected static function setConditionalStructures()
	{
		// Guarda o conteudo da view formatado
		$newViewContent = self::$viewContent;

		// Converte as chaves @ em codigo php
		$newViewContent = preg_replace('~@if([^\r\n}]+):~', "<?php if ($1): ?>", $newViewContent);
		$newViewContent = preg_replace('~@elseif([^\r\n}]+):~', "<?php elseif ($1): ?>", $newViewContent);
		$newViewContent = preg_replace('~@else:~', '<?php else: ?>', $newViewContent);
		$newViewContent = preg_replace('~@endif~', '<?php endif; ?>', $newViewContent);

		// Define o novo conteudo da view
		self::setViewContent($newViewContent);
	}

	/**
	 * Troca as chaves @foreach/@endforeach pelo respectivo em php.
	 *
	 * @return void
	 */
	protected static function setForeach($params)
	{
		// Guarda o conteudo da view formatado
		$newViewContent = self::$viewContent;

		// Converte as chaves @ em codigo php
		$newViewContent = preg_replace('~@foreach (\w+) as (\w+) => (\w+):~', '<?php foreach ($params["$1"] as \$$2 => \$$3): ?>', $newViewContent);
		$newViewContent = preg_replace('~\{\{ (\w+) \}\}~', "<?php echo \$$1; ?>", $newViewContent);
		$newViewContent = preg_replace('~@endforeach~', '<?php endforeach; ?>', $newViewContent);

		// Define o novo conteudo da view
		self::setViewContent($newViewContent);
	}

	/**
	 * Troca os colchetes ({}) unicos pelas funções de views.
	 *
	 * @return void
	 */
	protected static function setViewFunctions()
	{
		// Guarda o conteudo da view formatado
		$newViewContent = self::$viewContent;

		// Troca os colchetes ({}) por tags php
		$newViewContent = preg_replace('~\{ ([^\r\n}]+) \}~', "<?php TemplateEngine\Views::$1; ?>", $newViewContent);

		// Define o novo conteudo da view
		self::$viewContent = $newViewContent;
	}

	/**
	 * Extende uma view.
	 *
	 * @return void
	 */
	protected static function extendView()
	{
		// Verifica se existe @extend na view
		if (strpos(self::$viewContent, "@extends") === false) {
			// Encerra a função
			return false;
		}

		// Variaveis
		$viewNameStart = substr(self::$viewContent, strpos(self::$viewContent, "@extends('")+10);  // Pega o nome parcial da view a ser extendida
		$toExtend = str_replace(substr($viewNameStart, strpos($viewNameStart, "')")), "", $viewNameStart);  // Guarda o nome da view a ser extendida
		
		$newViewContent = str_replace("@extends('".$toExtend."')", "", self::$viewContent);  // Guarda o conteudo da view formatado

		// Substitui pontos (.) pela constante DIR
		$toExtend = str_replace(".", DIR, $toExtend); 

		// Verifica se a view extendida existe
		if (!file_exists(VIEWS . $toExtend . ".php")) {
			// Retorna falso
			return false;
		}

		// Pega o conteudo da view
		$newExtendedViewContent = file_get_contents(VIEWS . $toExtend . ".php");

		// Define o novo conteudo da view extendida
		self::setExtendedViewContent($newExtendedViewContent);
	}

	/**
	 * Une a view extendida (caso exista) com a view requisitada.
	 *
	 * @return void
	 */
	protected static function joinViews()
	{
		// Verifica se existe uma variavel a extender
		if (empty(self::$extendedViewContent)) {
			// Retorna falso
			return false;
		}

		// Busca as sessões na view filha
		while (strpos(self::$viewContent, "@section") !== false) {
			// Variaveis
			$newExtendedViewContent = self::$extendedViewContent;  // Guarda a view extendida
			$viewContent = self::$viewContent;
			
			$sectionStart = strpos($viewContent, "@section('")+10;  // Guarda o começo da section
			$sectionEnd = strpos($viewContent, "@endsection");  // Guarda o fim da section
			$partialSectionName = substr($viewContent, $sectionStart);  // Nome parcial da section
			$yieldName = str_replace(substr($partialSectionName, strpos($partialSectionName, "')")), "", $partialSectionName);  // Nome do yield da view pai
			
			$sectionContent = str_replace("{$yieldName}')", "", $partialSectionName);  // Conteudo parcial da section
			$sectionContent = str_replace(substr($sectionContent, strpos($sectionContent, "@endsection")), "", $sectionContent);  // Conteudo da section

			// Substitui @yield na view pai pelo conteudo de @section
			$newExtendedViewContent = str_replace("@yield('".$yieldName."')", $sectionContent, $newExtendedViewContent);  // Conteudo da view extendida formatado
			self::$extendedViewContent = $newExtendedViewContent;

			// Exclui toda a section
			self::$viewContent = str_replace("@section('{$yieldName}')", "", $viewContent);
		}

		self::setViewContent(preg_replace('~@yield([^\r\n}]+)~', "", $newExtendedViewContent));
	}

	/**
	 * Executa todos os métodos necessários para a renderização da view.
	 * 
	 * @param String $viewContent
	 * @param Array $params
	 *
	 * @return Bool 
	 */
	public static function render($viewContent, $params)
	{
		// Define a o conteudo da view para acesso global
		self::setViewContent($viewContent);

		// Carrega uma view extendida
		self::extendView();

		// Une as views caso haja extensões
		self::joinViews();

		// Muda as variaveis dentro de colchetes ({}) para o equivalente em php
		self::setParams($params);

		// Muda os campos @if/@elseif/@else para o equivalente em php
		self::setConditionalStructures();

		// Muda os campos @foreach/@endforeach para o equivalente em php
		self::setForeach($params);

		// Troca as funções de view por blocos php
		self::setViewFunctions();

		// Exibe o conteudo da view
		eval("?>" . self::$viewContent);
	}
}

?>