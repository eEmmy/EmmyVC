@extends('layout.app')

@section('title')
Login
@endsection

@section('content')
	<div class="row">
		<!-- Conteudo -->
		<div class="col-xs-12 col-md-8 mx-md-auto px-2 pt-3 pb-2 pt-md-5 pb-md-1 px-md-3 mt-3 border rounded">
			<!-- Titulo -->
			<h2 class="text-center">Login</h2>
			<!-- Formulario -->
			<form method="POST">
				<!-- Token de verificação -->
				{ inputToken() }
				<!-- Campo de email -->
				<input type="email" class="form-control mt-4 w-75 mx-auto" name="email" placeholder="Email">
				<!-- Campo de senha -->
				<input type="password" class="form-control mt-3 w-75 mx-auto" name="password" placeholder="Senha">
				<!-- Botão -->
				<div class="text-center">
					<button class="btn btn-outline-success mt-3">Login</button>
				</div>
			</form>
			<!-- Links de rodapé -->
			<div class="mt-3 text-right">
				<!-- Reset de senha -->
				<a href="{ url('password/send') }" class="mt-3 mr-3 text-success">Esqueci minha senha</a>
			</div>
		</div> 
	</div>
@endsection