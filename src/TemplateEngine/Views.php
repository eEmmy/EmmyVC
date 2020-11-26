<?php

namespace TemplateEngine;

use MatthiasMullie\Minify;

/**
 * Funções para serem usadas em todas as views. 
 */
class Views
{
	/**
	 * Exibe a a url base conifgurada concatenada com o endereço passado.
	 *
	 * @param String $page
	 *
	 * @return Bool true
	 */
	public static function url($page)
	{
		// Verifica se a url passada foi "/"
		if ($page == "/") {
			// Muda para vazio para não ocorrerem conflitos com a constante URL_BASE
			$page = "";
		}

		// Exibe a string com a url completa
		echo BASE_URL . $page;

		return true;
	}

	/**
	 * Minifica um arquivo css.
	 *
	 * @param String $file
	 *
	 * @return String $minifiedCss
	 */
	protected static function minifyCss($file)
	{
		// Instancia um objeto Minify
		$minifier = new Minify\CSS($file);

		// Guarda o conteudo minificado
		$minifiedCss = $minifier->minify();

		// Retorna o conteudo minificado
		return $minifiedCss;
	}

	/**
	 * Minifica um arquivo js.
	 *
	 * @param String $file
	 *
	 * @return String $minifiedJs
	 */
	protected static function minifyJs($file)
	{
		// Instancia um objeto Minify
		$minifier = new Minify\JS($file);

		// Guarda o conteudo minificado
		$minifiedJs = $minifier->minify();

		// Retorna o conteudo minificado
		return $minifiedJs;
	}

	/**
 	* Carrega um arquivo css em public/css
 	*
 	* @param String $file
 	*
 	* @return Bool
 	*/
	public static function css($file) 
	{
		// Substitui pontos (.) pela constante DIR
		$file = str_replace(".", DIR, $file);

		// Guarda apenas o nome do arquivo
		$fileName = $file . ".css";

		// Monta o caminho do arquivo
		$file = ROOT . "Resources" . DIR . "public" . DIR . "css" . DIR . $file . ".css";

		// Verifica se o arquivo existe
		if (!file_exists($file)) {
			// Retorna falso
			return false;
		}

		// Minifica o conteudo do arquivo
		$file = self::minifyCss($file);

		// Exibe a tag link
		echo "<!-- {$fileName} -->\n<style type=\"text/css\">" . $file . "</style>";

		// Retorna true por padrão
		return true;
	}

	/**
 	* Carrega um arquivo css em public/js
 	*
 	* @param String $file
 	*
 	* @return Bool
 	*/
	public static function js($file) 
	{
		// Substitui pontos (.) pela constante DIR
		$file = str_replace(".", DIR, $file);

		// Guarda apenas o nome do arquivo
		$fileName = $file . ".js";

		// Monta o caminho do arquivo
		$file = ROOT . "Resources" . DIR . "public" . DIR . "js" . DIR . $file . ".js";

		// Verifica se o arquivo existe
		if (!file_exists($file)) {
			// Retorna falso
			return false;
		}

		// Minifica o conteudo do arquivo
		$file = self::minifyJs($file);

		// Exibe a tag script
		echo "<!-- {$fileName} -->\n<script type=\"text/javascript\">" . $file . "</script>";

		// Retorna true por padrão
		return true;
	}

	/**
	 * Converte uma imagem em base64 e mostra a string.
	 *
	 * @param String $imageName
	 * @param String $imageType
	 *
	 * @return void
	 */
	public static function image($imageName, $imageType)
	{
		// Verifica se o tipo de imagem passado é valido
		if (empty($imageType) || !in_array($imageType, ["png", "jpg", "gif", "bmp", "svg"])) {
			// Retorna o aviso de erro
			echo "{$imageType} é um tipo de imagem desconhecido";
			return;
		}

		// Substitui pontos (.) pela constante DIR e adiciona a extensão
		$imageName = str_replace(".", DIR, $imageName) . ".{$imageType}";

		// Guarda o path completo da imagem
		$imagePath = ROOT . "Resources" . DIR . "assets" . DIR . "images" . DIR . $imageName;

		// Verifica se a imagem existe
		if (!file_exists($imagePath)) {
			// Retorna o aviso de erro
			echo "O arquivo {$imageName} não existe";
			return;
		}

		// Pega o conteudo de $imagePath
		$image = file_get_contents($imagePath);

		// Guarda o codigo final da imagem
		$imageSrc = "";

		// Verifica o tipo da imagem
		switch ($imageType) {
			case "png":
				$imageSrc = "data:image/png;base64,";
				break;
			
			case "jpg":
				$imageSrc = "data:image/jpeg;base64,";
				break;

			case "gif":
				$imageSrc = "data:image/gif;base64,";
				break;

			case "bmp":
				$imageSrc = "data:image/bmp;base64";

			case "svg":
				$imageSrc = "data:image/svg+xml;base64,";
				break;
			
			default:
				break;
		}

		// Adiciona a string base64 para o codigo da imagem
		$imageSrc .= base64_encode($image);

		// Exibe o codigo da imagem
		echo $imageSrc;

		return true;
	}

	/**
	 * Converte um arquivo de audio em base64 e mostra a string.
	 *
	 * @param String $audioName
	 * @param String $audioType
	 *
	 * @return Bool
	 */
	public static function audio($audioName, $audioType)
	{
		// Verifica se o tipo de audio passado é valido
		if (empty($audioType) || !in_array($audioType, ["mp3", "ogg", "wav"])) {
			// Retorna o aviso de erro
			echo "{$audioType} é um tipo de audio desconhecido";
			return;
		}

		// Substitui pontos (.) pela constante DIR e adiciona a extensão
		$audioName = str_replace(".", DIR, $audioName) . ".{$audioType}";

		// Guarda o path completo do audio
		$audioPath = ROOT . "Resources" . DIR . "assets" . DIR . "audio" . DIR . $audioName;

		// Verifica se o audio existe
		if (!file_exists($audioPath)) {
			// Retorna o aviso de erro
			echo "O arquivo {$audioName} não existe";
			return;
		}

		// Pega o conteudo de $audioPath
		$audio = file_get_contents($audioPath);

		// Guarda o codigo final do audio
		$audioSrc = "";

		// Verifica o tipo da audio
		switch ($audioType) {
			case "mp3":
				$audioSrc = "data:audio/mpeg;base64,";
				break;
			
			case "ogg":
				$audioSrc = "data:audio/ogg;base64,";
				break;

			case "wav":
				$audioSrc = "data:audio/x-wav;base64,";
				break;
			
			default:
				return false;
				break;
		}

		// Adiciona a string base64 para o codigo do audio
		$audioSrc .= base64_encode($audio);

		// Exibe o codigo do audio
		echo $audioSrc;

		return true;
	}

	/**
	 * Converte um arquivo de video em base64 e mostra a string.
	 *
	 * @param String $videoName
	 * @param String $videoType
	 *
	 * @return Bool
	 */
	public static function video($videoName, $videoType="")
	{
		// Verifica se apenas o tipo foi passado
		if (empty($videoType) && in_array($videoName, ["mp4", "webm", "ogv"])) {
			// Verifica o tipo de video
			switch ($videoName) {
				case "mp4":
					// Exibe o tipo MIME do video
					echo "video/mp4";

					// Encerra a função
					return;
					break;

				case "webm":
					// Exibe o tipo MIME do video
					echo "video/webm";

					// Encerra a função
					return;
					break;
				
				case "ogv":
					// Exibe o tipo MIME do video
					echo "video/ogg";

					// Encerra a função
					return;
					break;
				
				default:
					# code...
					break;
			}
		}

		// Verifica se o tipo de video passado é valido
		if (empty($videoType) || !in_array($videoType, ["mp4", "webm", "ogv"])) {
			// Retorna o aviso de erro
			echo "{$videoType} é um tipo de video desconhecido";
			return;
		}

		// Substitui pontos (.) pela constante DIR e adiciona a extensão
		$videoName = str_replace(".", DIR, $videoName) . ".{$videoType}";

		// Guarda o path completo da video
		$videoPath = ROOT . "Resources" . DIR . "assets" . DIR . "videos" . DIR . $videoName;

		// Verifica se o video existe
		if (!file_exists($videoPath)) {
			// Retorna o aviso de erro
			echo "O arquivo {$videoName} não existe";
			return;
		}

		// Pega o conteudo de $videoPath
		$video = file_get_contents($videoPath);

		// Guarda o codigo final do video
		$videoSrc = "";

		// Verifica o tipo da video
		switch ($videoType) {
			case "mp4":
				$videoSrc = "data:video/mp4;base64,";
				break;
			
			case "ogv":
				$videoSrc = "data:video/ogg;base64,";
				break;

			case "webm":
				$videoSrc = "data:video/webm;base64,";
				break;
			
			default:
				return false;
				break;
		}

		// Adiciona a string base64 para o codigo do video
		$videoSrc .= base64_encode($video);

		// Exibe o codigo do video
		echo $videoSrc;

		return true;
	}

	/**
	 * Converte o arquivo Resouces/public/favicon.ico em base64 e mostra a string.
	 *
	 * @return Bool true
	 */
	public static function favicon()
	{
		// Verifica se o arquivo favicon.ico existe
		if (file_exists(ROOT . "Resources" . DIR . "public" . DIR . "favicon.ico")) {
			// Guarda o conteudo do arquivo
			$file = file_get_contents(ROOT . "Resources" . DIR . "public" . DIR . "favicon.ico"); 
			
			// Guarda o código final do arquivo
			$fileSrc = "data:image/x-icon;base64,";

			// Adiciona a string base64 para o codigo do arquivo
			$fileSrc .= base64_encode($file);

			// Exibe o codigo do arquivo
			echo $fileSrc;
		}

		// Retorna true por padrão
		return true;
	}

	/**
	 * Exibe um input com o token configurado em Configuration/session.php
	 *
	 * @return Bool true
	 */
	public static function inputToken()
	{
		// Inporta a variavel $token
		global $token;

		// Exibe o token
		echo "<input type=\"text\" hidden=\"hidden\" value=\"" . $GLOBALS["token"] . "\" name=\"token\">";

		return true;
	}

	/**
	 * Exibe variaveis de sessão do tipo flash.
	 *
	 * @param String $sessionName
	 *
	 * @return void
	 */
	public static function sessionContent($sessionName)
	{
		// Exibe o conteudo de uma sessão flash
		echo $_SESSION["FLASH"][$sessionName];
	}
}

?>