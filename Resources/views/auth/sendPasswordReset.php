@extends('layout.app')

@section('title')
Redefinir senha
@endsection

@section('content')
	<div class="row">
		<!-- Conteudo -->
		<div class="col-xs-12 col-md-8 mx-md-auto px-2 pt-3 pb-2 pt-md-5 pb-md-1 px-md-3 mt-3 border rounded">
			<!-- Titulo -->
			<h2 class="text-center">Redefinir senha</h2>
			<!-- Texto -->
			<p class="text-center mt-2">
				Informe abaixo o email cadastrado que enviaremos as intruções para a redefinição de sua senha.
			</p>
			<!-- Formulario -->
			<form method="POST">
				<!-- Token de verificação -->
				{ inputToken() }
				<!-- Campo de email -->
				<input type="email" class="form-control mt-4 w-75 mx-auto" name="email" placeholder="Email">
				<!-- Botão -->
				<div class="text-center">
					<button class="btn btn-outline-success mt-3">Login</button>
				</div>
			</form>
		</div> 
	</div>
@endsection