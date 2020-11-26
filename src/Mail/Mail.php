<?php

namespace App;

use \Exception;
use TemplateEngine\Template;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\PHPMailer;

/**
 * Cuida do envio de emails da aplicação.
 */
class Mail
{
	/**
	 * Guarda o usuario do email.
	 *
	 * @var String $user
	 */
	private static $user;

	/**
	 * Guarda a senha do email.
	 *
	 * @var String $pass
	 */
	private static $pass;

	/**
	 * Guarda o nome de quem enviou o email.
	 *
	 * @var String $name
	 */
	private static $name;

	/**
	 * Guarda o serviço a ser usado.
	 *
	 * @var String $service
	 */
	private static $service;

	/**
	 * Guarda o host SMTP.
	 *
	 * @var String $smtpHost
	 */
	private static $smtpHost;

	/**
	 * Guarda a porta SMTP.
	 *
	 * @var Int $smtpPort
	 */
	private static $smtpPort;

	/**
	 * Guarda um objeto PHPMailer.
	 *
	 * @var PHPMailer $mailer
	 */
	protected static $mailer;

	/**
	 * Guarda o conteudo da mensagem.
	 *
	 * @var String $emailContent
	 */
	protected static $emailContent;

	/**
	 * Guarda o conteudo da mensagem.
	 *
	 * @var String $emailContent
	 */
	protected static $subject;

	/**
	 * Define as variaveis globais dentro do escopo da classe.
	 *
	 * @return void
	 */
	protected static function init()
	{
		// Define as variaveis
		self::$user = $GLOBALS["mailUser"];
		self::$pass = $GLOBALS["mailPass"];
		self::$name = $GLOBALS["name"];
		self::$service = $GLOBALS["service"];
		self::$smtpHost = $GLOBALS["smtpHost"];
		self::$smtpPort = $GLOBALS["smtpPort"];
		
	}

	/**
	 * Define as configurações para o tipo de serviço utilizado.
	 *
	 * @return void
	 */
	protected static function setService()
	{

		// Verifica self::$service
		switch (self::$service) {
			// Gmail
			case "gmail":
				// Define as configurações de envio
				self::$mailer->Host = "smtp.gmail.com";
				self::$mailer->Port = 587;
				self::$mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
				break;

			// Microsoft
			case "ms":
				// Define as configurações de envio
				self::$mailer->Host = "smtp.office365.com";
				self::$mailer->Port = 587;
				self::$mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
				break;

			default:
				// Define as configurações de envio
				self::$mailer->Host = self::$smtpHost;
				self::$mailer->Port = self::$smtpPort;
				self::$mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
				break;
		}
	}

	/**
	 * Define algumas confiugrações do PHPMailer independentes de serviços. 
	 *
	 * @return void
	 */
	protected static function initMailer()
	{
		// Objeto PHPMailer
		self::$mailer = new PHPMailer();

		// Informa ao PHPMailer para usar SMTP
		self::$mailer->isSMTP();
		self::$mailer->SMTPAuth = true;
		
		// Debug
		self::$mailer->SMTPDebug = SMTP::DEBUG_OFF;

		// Encoding
		self::$mailer->CharSet = "UTF-8";
		
		// Usuario e senha
		self::$mailer->Username = self::$user;
		self::$mailer->Password = self::$pass;

		// Endereço de envio
		self::$mailer->setFrom(self::$user, self::$name);
		
		// Assunto do email
		self::$mailer->Subject = self::$subject;

		// Conteudo do email
		self::$mailer->msgHTML(self::$emailContent, __DIR__);
		self::$mailer->AltBody = "";
	}

	/**
	 * Pega o conteudo do arquivo especificado.
	 *
	 * @param String $file
	 * @param Array $params (opcional)
	 *
	 * @return String $fileContent
	 */
	protected static function getFile($file, $params=[])
	{
		// Substitui pontos (.) pela constante DIR
		$file = str_replace(".", DIR, $file);

		// Verifica se o email existe
		if (!file_exists(VIEWS . "{$file}.html")) {
			// Gera um erro
			throw new Exception("O arquivo " . VIEWS . "{$file}.html não existe");
			
			return false;
		}

		// Pega o conteudo do arquivo
		$fileContent = file_get_contents(VIEWS . "{$file}.html");

		// Verifica se foram passados parametros
		if (count($params) > 0) {
			// Loop em $params
			foreach ($params as $key => $value) {
				// Procura e substitui $key por $value
				$fileContent = str_replace("{{ ".$key." }}", $value, $fileContent);
			}
		}

		// Retorna o conteudo
		self::$emailContent = $fileContent;
	}

	/**
	 * Define o assunto do email.
	 *
	 * @param String $subject
	 *
	 * @return __CLASS__
	 */
	public static function subject($subject)
	{
		// Muda o valor de self::$subject
		self::$subject = $subject;

		// Retorna __CLASS__ para encadear métodos
		return __CLASS__;
	}

	/**
	 * Autoexecuta os métodos da classe e envia o email.
	 *
	 * @param String $to
	 * @param String $name
	 * @param String $viewName
	 *
	 * @return Bool
	 */
	public static function to($to, $name, $viewName, $params=[])
	{
		// Define as variaveis da classe
		self::init();

		// Pega o conteudo da view
		self::getFile($viewName, $params);

		// Configura o PHPMailer
		self::initMailer();

		// Configura o serviço
		self::setService();

		// Define o endereço de envio
		self::$mailer->addAddress($to, $name);


		// Envia o email
		if(!self::$mailer->send()) {  // Erro no envio
			// Retorna falso
			return false;
		}
		else {  // Email enviado com sucesso
			return true;
		}
	}
}