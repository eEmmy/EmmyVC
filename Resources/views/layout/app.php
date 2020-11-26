<!DOCTYPE html>
<html>
<head>
	<!-- Titulo -->
	<title>@yield('title')</title>
	<!-- Bootstrap meta tags -->
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Favicon -->
    <link rel="icon" href="{ favicon() }" type="image/x-icon"/>
	{ css('bootstrap') }
	{ css('app') }
	@yield('css')
</head>
<body>
	
	<!-- Navbar -->
	<nav class="navbar navbar-expand-lg navbar-light bg-light">
		<!-- Titulo da navbar -->
		<a class="navbar-brand" href="{ url('/') }">
			<!-- Logo. Descomente para usar -->
			<!-- <img src="{ image('seu-logo', 'svg') }" width="30" height="30" class="d-inline-block align-top"> -->
			Marca
		</a>
		<!-- Menu hamburguer -->
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navMenus" aria-controls="navMenus" aria-expanded="false" aria-label="Abrir menu">
			<span class="navbar-toggler-icon"></span>
		</button>

		<!-- Conteudo expandivel -->
		<div class="collapse navbar-collapse" id="navMenus">
			<!-- Menus a esquerda -->
			<ul class="navbar-nav mr-auto">
				<!-- Inicio -->
				<li class="nav-item active">
					<a class="nav-link" href="{ url('/') }">Inicio</a>
				</li>
				<!-- Pagina -->
				<li class="nav-item">
					<a class="nav-link" href="{ url('pagina') }">Pagina</a>
				</li>
				<!-- Dropdown -->
      			<li class="nav-item dropdown">
      				<!-- Botão -->
			        <a class="nav-link dropdown-toggle" href="#" id="dropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          				Dropdown
        			</a>
        			<!-- Itens -->
					<div class="dropdown-menu" aria-labelledby="dropdown">
						<!-- Item 1 -->
						<a class="dropdown-item" href="{ url('item-1') }">Item 1</a>
						<!-- Item 2 -->
						<a class="dropdown-item" href="{ url('item-2') }">Item 2</a>
						<!-- Separador -->
						<div class="dropdown-divider"></div>
						<!-- Item 3 -->
						<a class="dropdown-item" href="={ url('item-3') }">Item 3</a>
					</div>
				</li>
			</ul>

			<!-- Barra de pesquisa -->
			<form class="form-inline my-2 my-lg-0">
				<!-- Campo de verificação -->
				{ inputToken() }
				<!-- Campo de busca -->
				<input class="form-control mr-sm-2" type="search" placeholder="Pesquisar" aria-label="Pesquisar">
				<!-- Botão de envio -->
				<button class="btn btn-outline-success my-2 my-sm-0">Ok</button>
			</form>

			<!-- Menus a direita -->
			<ul class="navbar-nav ml-auto">
				<!-- Menus para usuarios logados -->
				@if (App\Auth::check()):
					<!-- Logout -->
					<li class="nav-item">
						<a href="{ url('logout') }" class="nav-link">Logout</a>
					</li>
				<!-- Menus para usuarios não logados -->
				@else:
					<!-- Registro -->
					<li class="nav-item">
						<a href="{ url('register') }" class="nav-link">Registro</a>
					</li>
					<!-- Login -->
					<li class="nav-item">
						<a href="{ url('login') }" class="nav-link">Login</a>
					</li>
				@endif
			</ul>
		</div>
	</nav>

	@if (isset($_SESSION["FLASH"]["error"])):
		<!-- Sucesso -->
		<div class="alert alert-danger" role="alert">
  			{ sessionContent('error') }
		</div>
	@endif

	@if (isset($_SESSION["FLASH"]["success"])):
		<!-- Erro -->
		<div class="alert alert-success" role="alert">
  			{ sessionContent('success') }
		</div>
	@endif

	<!-- Conteudo em container full width -->
	<div class="container-fluid">
		@yield('content-fluid')
	</div>

	<!-- Conteudo em container -->
	<div class="container">
		@yield('content')
	</div>

	<!-- Javascript -->
	{ js('jquery') }
	{ js('popper') }
	{ js('bootstrap') }
	@yield('js')
</body>
</html>