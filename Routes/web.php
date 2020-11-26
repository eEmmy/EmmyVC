<?php

/*
|------------------------------------------------------------------------
| Define as rotas da aplicação.
|------------------------------------------------------------------------
|
| Aqui é onde você define todas as rotas da aplicação. A ordem dos 
| parametros é:
|
| * Método = método da URL, pode ser GET ou POST.
| * URL = URL que será associada a action.
| * Controller e action = O nome do controller que deve ser usado, 
| seguido de um arroba (@) e o nome do método que será usado para essa URL.
| * Parametros = Define se deverão ser passados parametros para a URL.
| Precisa ser definido, mesmo que não seja passado nenhum parametro.
| * Middleware = Middleware a ser usado na rota. Se você não ira usar
| nenhum, não é necessário declarar um. 
|
*/

$GLOBALS["routes"] = array(
	/*
	|---------------------------------------
	| Rotas GET da aplicação.
	|---------------------------------------
	|
	| Defina aqui todas as rotas GET da 
	| aplicação aqui.
	|
	*/

	["GET", "/", "HomeController@index", []],
	
	/*
	|---------------------------------------
	| Rotas POST da aplicação.
	|---------------------------------------
	|
	| Defina aqui todas as rotas POST da
	| aplicação aqui.
	|
	*/

	
	
	/*
	|---------------------------------------
	| Rotas para autenticação.
	|---------------------------------------
	|
	| Rotas para autenticação, registro e 
	| demais operações relacionadas a 
	| usuarios.
	|
	*/

	["GET", "/register", "RegisterController@showRegisterView", [], "UnloggedOnly"],
	["GET", "/email/sended", "EmailConfirmationController@showSendedView", [], "UnloggedOnly"],
	["GET", "/email/token", "EmailConfirmationController@confirmEmail", ["token"], "UnloggedOnly"],
	["GET", "/login", "LoginController@showLoginView", [], "UnloggedOnly"],
	["GET", "/logout", "LoginController@logout", [], "LoggedOnly"],
	["GET", "/password/send", "PasswordResetController@showSendView", [], "UnloggedOnly"],
	["GET", "/password/sended", "PasswordResetController@showSendedView", [], "UnloggedOnly"],
	["GET", "/password/token", "PasswordResetController@tokenCheck", ["token"]],
	["GET", "/password/reset", "PasswordResetController@showResetView", [], "UnloggedOnly"],
	
	["POST", "/register", "RegisterController@register", []],
	["POST", "/login", "LoginController@login", []],
	["POST", "/password/send", "PasswordResetController@sendPasswordReset", []],
	["POST", "/password/reset", "PasswordResetController@resetPassword", []],

	/*
	|---------------------------------------
	| Rotas para erros.
	|---------------------------------------
	|
	| Rotas para erros da aplicação.
	|
	*/

	["GET", "/404", "ErrorController@error404", []],
	["GET", "/403", "ErrorController@error403", []]	
);

?>