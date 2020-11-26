<?php

use DB\Schema;
use DB\Blueprint;

// Instancia objeto blueprint
$table = new Blueprint();

// Exclui a tabela caso já exista
Schema::down("users");

// Cria a tabela
Schema::up("users", [
	$table->id(),
	$table->string("`user`", 32),
	$table->string("email"),
	$table->password(),
	$table->default(0)->integer("user_state", 11),

	$table->created_at(),
	$table->updated_at()
]);

?>