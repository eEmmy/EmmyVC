<?php

/*
|--------------------------------------------------------------------------------------
| Tabela de usuarios.
|--------------------------------------------------------------------------------------
|
| Aqui você deve definir qual a tabela em que as informações dos usuarios serão
| armazenadas. O padrão é users.
|
*/

$GLOBALS["authTable"] = "users";

/*
|--------------------------------------------------------------------------------------
| Campos usados para registro.
|--------------------------------------------------------------------------------------
|
| Aqui você deve definir quais os campos necessários para registrar um usuario.
| Você pode adicionar campos criando um array com o nome do campo e o filtro ao qual
| ele deve corresponder. O nome definido aqui deve ser o mesmo que o campo na tabela de
| usuarios e o mesmo que no formulário.
| Para evitar erros, você deve adicionar o campo também em RegisterController.
|
*/

$GLOBALS["registerFields"] = [
	["user", "minlen:4|maxlen:32"],
	["email", "minlen:6|unique:email"]
];

/*
|--------------------------------------------------------------------------------------
| Campos usados para login.
|--------------------------------------------------------------------------------------
|
| Aqui você deve definir quais os campos necessários para autenticar um usuario.
| Você pode adicionar campos criando um array com o nome do campo e o filtro ao qual
| ele deve corresponder. O nome definido aqui deve ser o mesmo que o campo na tabela de
| usuarios e o mesmo que no formulário.
| Para evitar erros, você deve adicionar o campo também em LoginController.
|
*/

$GLOBALS["loginFields"] = [
	["email", "minlen:6"]
];


/*
|--------------------------------------------------------------------------------------
| Hash para encriptar senhas.
|--------------------------------------------------------------------------------------
|
| Aqui você deve definir qual a chave a ser usada para encriptar senhas. O hash tem que
| ter exatamente 128 caracteres. É altamente recomendável muda-la, e manter a sua chave
| secreta para evitar falhas de segurança.
|
*/

$GLOBALS["passHash"] = "acdce68dbafeb91977a98cf3e6cdae705936f1c0cfb5831aac5f9f0f794f4fec75b86d6445975c5193b2b77e1912a6c87422b5e3c6b16dcaa8487c90d5949d2f";

/*
|--------------------------------------------------------------------------------------
| Chave para permitir a redefinição de senhas.
|--------------------------------------------------------------------------------------
|
| Aqui você deve definir qual a chave a ser usada para permitir a redefinição de
| senhas. Também é altamente recomendado muda-la e manter sua anonimadade.
|
*/

$GLOBALS["RESET_PASSWORD_PERMISSION"] = "azK7";

?>