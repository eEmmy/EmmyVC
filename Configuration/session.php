<?php

/*
|--------------------------------------------------------------------------------------
| Hash guardar o id da sessão.
|--------------------------------------------------------------------------------------
|
| Aqui você deve definir qual o hash a ser usado para guardar o id da sessão. O hash tem que
| ter exatamente 32 caracteres. É altamente recomendável muda-lo, e mante-lo
| secreto para evitar falhas de segurança.
|
*/

$GLOBALS["sessionHash"] = "7e78466a0fc130c0295057e921f5f630";

/*
|--------------------------------------------------------------------------------------
| Tempo de expiração da sessão.
|--------------------------------------------------------------------------------------
|
| Aqui você deve definir qual o tempo em minutos que a sessão do usuario continuara 
| ativa em caso de inatividade. 
|
*/

$GLOBALS["sessionExpiresIn"] = 20;

/*
|--------------------------------------------------------------------------------------
| Token para validação da origem de formulários.
|--------------------------------------------------------------------------------------
|
| Aqui você deve difinir um token para dificultar a realização de solicitações POST 
| fora da aplicação. Pode ter qualquer tamanho.
|
*/

$GLOBALS["token"] = "az223QU";

?>