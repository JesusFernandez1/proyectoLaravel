<html>

<head>
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
   <title>Base de Datos</title>
</head>

<body>
   @extends('base')

   @section('mostrarExtension')

   <h1>¿Estas seguro de eliminar el usuario?</h1>

   <table class="table">
      <thead class="thead-dark">
         <tr>
            <th scope="col">DNI</th>
            <th scope="col">Nombre</th>
            <th scope="col">Correo</th>
            <th scope="col">Telefono</th>
            <th scope="col">Direccion</th>
            <th scope="col">Fecha de alta</th>
            <th scope="col">Tipo</th>
         </tr>
      </thead>
      <tbody>
         <tr>
            <td>{{$usuario->DNI}}</td>
            <td>{{$usuario->name}}</td>
            <td>{{$usuario->email}}</td>
            <td>{{$usuario->telefono}}</td>
            <td>{{$usuario->direccion}}</td>
            <td>{{$usuario->fecha_alta}}</td>
            <td>{{$usuario->tipo}}</td>
            <td><a href=" {{route('usuarios.confirmarEliminarUsuario',$usuario) }}" class="btn btn-outline-danger" role="button">Si</a> <a href="{{ route('usuarios.index') }}" class="btn btn-outline-success" role="button">No</a>
         </tr>
      </tbody>
   </table>
   @endsection
</body>
</html>