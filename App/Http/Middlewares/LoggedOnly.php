<?php

namespace App\Http\Middleware;

use App\Auth;
use App\Http\Request;

/**
 * Verifica se o usuario está logado.
 */
class LoggedOnly
{
	
	/**
	 * Verifica se o usuario cumpre os requisitos para ter acesso a pagina.
	 *
	 * @return Bool
	 */
	public function check()
	{
		// Verifica se o usuario está logado
		if (Auth::check()) {
			// O usuario cumpre os requisitos
			return true;
		}
		else {
			// O usuario não cumpre os requisitos
			return false;
		}
	}

	/**
	 * Método a ser acionado caso o usuario não cumpra os requisitos.
	 *
	 * @return $request->redirect()
	 */
	public function denied()
	{
		// Instancia o objeto Request
		$request = new Request();

		// Redireciona para a pagina 404
		return $request->redirect("404");
	}
}