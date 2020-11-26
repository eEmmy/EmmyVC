<?php
/*
|-------------------------------------------------------------------
| Inclui o arquivo de autoload gerado pelo composer.
|-------------------------------------------------------------------
|
| Carrega os arquivos de configurações e todos os namespaces de 
| classes.
|
*/

require_once "../../vendor/autoload.php";

/*
|-------------------------------------------------------------------
| Roda o Bootstrap da aplicação.
|-------------------------------------------------------------------
|
| Executa todas as operações necessárias para a execução da 
| aplicação. Os métodos estão disponiveis no arquivo 
| Bootstrap/App.php
|
*/

App::init();

/*
|-------------------------------------------------------------------
| Inicia a aplicação.
|-------------------------------------------------------------------
|
| Instancia o Router da aplicação, que se auto-executa. 
|
*/

$router = new App\Http\Router($routes);

?>
