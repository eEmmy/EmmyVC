<?php

use DB\Schema;
use DB\Blueprint;

// Instancia objeto blueprint
$table = new Blueprint();

// Exclui a tabela caso já exista
Schema::down("email_tokens");

// Cria a tabela
Schema::up("email_tokens", [
	$table->id(),
	$table->integer("user_id", 11),
	$table->nullable()->char("token", 32)
]);

?>