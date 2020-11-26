<?php

/*
|--------------------------------------------------------------------------------------
| Usuario do email.
|--------------------------------------------------------------------------------------
|
| Aqui você deve definir qual o nome de usuario para o envio de emails.
|
*/

$GLOBALS["mailUser"] = "seuusuario@email.com";

/*
|--------------------------------------------------------------------------------------
| Senha para o usuario.
|--------------------------------------------------------------------------------------
|
| Aqui você deve definir qual a senha do usuario a cima.
|
*/

$GLOBALS["mailPass"] = "suasenha";

/*
|--------------------------------------------------------------------------------------
| Nome de remetente.
|--------------------------------------------------------------------------------------
|
| Aqui você deve definir qual o nome de remetente será exibido para o recepiente do
| email.
|
*/

$GLOBALS["name"] = "Jhon Doe";

/*
|--------------------------------------------------------------------------------------
| Serviço para envio de emails.
|--------------------------------------------------------------------------------------
|
| Aqui você deve definir qual o serviço a ser usado para o envio de emails.
| Para usar um serviço personalizado, deixe vazio.
|
| Gmail (@gmail.com) = "gmail"
| Microsoft (@outlook, @hotmail) = "ms"
|
*/

$GLOBALS["service"] = "ms";


/*
|--------------------------------------------------------------------------------------
| Host para envio de emails.
|--------------------------------------------------------------------------------------
|
| Aqui você deve definir qual o endereço do servidor usado para envio de emails.
| Só necessita ser alterado caso opte por usar um serviço de emails personalizado.
|
*/

$GLOBALS["smtpHost"] = "your.host.com";

/*
|--------------------------------------------------------------------------------------
| Porta para conexão com o servidor de emails.
|--------------------------------------------------------------------------------------
|
| Aqui você deve definir qual a porta a ser usada para se conectar ao servidor de 
| emails.
| Só necessita ser alterado caso opte por usar um serviço de emails personalizadi.
|
*/

$GLOBALS["smtpPort"] = 25;

?>