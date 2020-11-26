@extends('layout.app')

@section('title')
Redefinir senha
@endsection

@section('content')
	<div class="row">
		<!-- Conteudo -->
		<div class="col-xs-12 col-md-8 mx-md-auto px-2 pt-3 pb-2 pt-md-5 pb-md-1 px-md-3 mt-3 border rounded">
			<!-- Titulo -->
			<h2 class="text-center">Redefinir sua senha</h2>
			<!-- Formulario -->
			<form method="POST">
				<!-- Token de verificação -->
				{ inputToken() }
				<!-- Campo de senha -->
				<input type="password" class="form-control mt-3 w-75 mx-auto" name="password" placeholder="Senha">
				<!-- Botão -->
				<div class="text-center">
					<button class="btn btn-outline-success mt-3">Redefinir</button>
				</div>
			</form>
		</div> 
	</div>
@endsection