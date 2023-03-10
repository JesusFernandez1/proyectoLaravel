<html>

<head>
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
   <title>Base de Datos</title>
</head>

<body>
   @extends('base')

   @section('mostrarExtension')

   <table class="table">
      <thead class="thead-dark">
         <tr>
            <th scope="col">Concepto</th>
            <th scope="col">Fecha de emision</th>
            <th scope="col">Importe</th>
            <th scope="col">Pagada</th>
            <th scope="col">Fecha del pago</th>
            <th scope="col">Nota</th>
            <th scope="col">Cliente correspondiente</th>
            <th scope="col">Acciones</th>
         </tr>
      </thead>
      <tbody>
         @foreach ($cuotas as $cuota)
         <tr>
            <td>{{$cuota->concepto}}</td>
            <td>{{$cuota->fecha_emision}}</td>
            <td>{{$cuota->importe}}</td>
            <td>{{$cuota->pagada}}</td>
            <td>{{$cuota->fecha_pago}}</td>
            <td>{{$cuota->notas}}</td>
            <td>{{$cliente}}</td>
            <td><a href="{{ route('cuotas.edit',$cuota) }}" class="btn btn-outline-primary" role="button">Modificar</a> 
               <a href="{{ route('paypal.pay',$cuota) }}" class="btn btn-outline-success" role="button">Pagar</a>
               <a href="{{ route('cuotas.pdf',$cuota) }}" class="btn btn-outline-info" role="button">PDF</a>
               <a href="{{ route('cuotas.mostrarEliminar',$cuota) }}" class="btn btn-outline-danger" role="button">Eliminar</a></td>
         </tr>
         @endforeach
      </tbody>
   </table>
   {!! $cuotas->links() !!}
   @endsection
</body>
</html>