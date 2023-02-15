<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\task;
use App\Models\User;
use App\Models\customer;
use App\Models\provincias;
use Illuminate\Support\Facades\Auth;


class taskController extends \Illuminate\Routing\Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->tipo == 'Admin') {
            $tareas = task::paginate(2);
            return view('tareas.tareas_mostrar', compact('tareas'));
        } else {
            return view('base');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::user()->tipo == 'Admin') {
            $provincias = provincias::all();
            $empleados = User::all();
            $clientes = customer::all();
            return view('tareas.tareas_crear', compact('provincias', 'empleados', 'clientes'));
        } else {
            return view('base');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->users_id != null) {
            $users_id = User::select('id')->where('name', '=', $request->users_id)->first();
            $users = $users_id->id;
        }

        if ($request->customers_id != null) {
            $customers_id = customer::select('id')->where('nombre', '=', $request->customers_id)->first();
            $customers = $customers_id->id;
        }
        $fecha_creacion = $request->fecha_creacion;
        $datos = $request->validate([
            'nombre' => ['regex:/^[a-z]+$/i'],
            'apellido' => ['regex:/^[a-z]+$/i'],
            'telefono' => ['regex:/(\+34|0034|34)?[ -]*(6|7|8|9)[ -]*([0-9][ -]*){8}/'],
            'descripcion' => ['required'],
            'correo' => ['regex:#^(((([a-z\d][\.\-\+_]?)*)[a-z0-9])+)\@(((([a-z\d][\.\-_]?){0,62})[a-z\d])+)\.([a-z\d]{2,6})$#i'],
            'direccion' => ['required'],
            'poblacion' => ['required'],
            'codigo_postal' => ['required'],
            'provincia' => ['required'],
            'estado_tarea' => ['required'],
            'fecha_creacion' => [
                'required', 'date_format:Y-m-d\TH:i',
                function ($atribute, $value, $fail) {
                    if (date("Y-m-d\TH", strtotime($value)) != date("Y-m-d\TH")) {
                        $fail('La fecha de creación no se puede modificar.');
                    }
                }
            ],
            'fecha_final' => [
                'nullable', 'date_format:Y-m-d\TH:i',
                function ($atribute, $value, $fail) use ($fecha_creacion) {
                    if (date("Y-m-d\TH", strtotime($value)) <= date("Y-m-d\TH", strtotime($fecha_creacion))) {
                        $fail('La fecha de creación no se puede modificar.');
                    }
                }
            ],
            'anotacion_anterior' => ['nullable'],
            'anotacion_posterior' => ['nullable'],
            'users_id' => ['required'],
            'customers_id' => ['required']

        ]);
        $datos['users_id'] = $users;
        $datos['customers_id'] = $customers;
        task::insert($datos);
        $tareas = task::paginate(2);
        return view('tareas.tareas_mostrar', compact('tareas'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (Auth::user()->tipo == 'Admin') {
            $tarea = task::find($id);
            return view('tareas.tareas_eliminar', compact('tarea'));
        } else {
            return view('base');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Auth::user()->tipo == 'Admin') {
            $tarea = task::find($id);

            $nombreEmpleado = User::select('name')->where('id', '=', $tarea->users_id)->first();
            $nombreCliente = customer::select('nombre')->where('id', '=', $tarea->customers_id)->first();

            $provincias = provincias::where('nombre', '!=', $tarea->provincia)->get();
            $empleados = User::where('id', '!=', $tarea->users_id)->get();
            $clientes = customer::where('id', '!=', $tarea->customers_id)->get();
            return view('tareas.tareas_modificar', compact('tarea', 'provincias', 'empleados', 'clientes', 'nombreEmpleado', 'nombreCliente'));
        } else {
            return view('base');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $users_id = User::select('id')->where('name', '=', $request->users_id)->first();
        $users = $users_id->id;
        $customers_id = customer::select('id')->where('nombre', '=', $request->customers_id)->first();
        $customers = $customers_id->id;
        $fecha = task::select('fecha_creacion')->where('id', '=', $id)->first();

        $fecha_creacion = $request->fecha_creacion;
        $datos = $request->validate([
            'nombre' => ['regex:/^[a-z]+$/i'],
            'apellido' => ['regex:/^[a-z]+$/i'],
            'telefono' => ['regex:/(\+34|0034|34)?[ -]*(6|7|8|9)[ -]*([0-9][ -]*){8}/'],
            'descripcion' => ['required'],
            'correo' => ['regex:#^(((([a-z\d][\.\-\+_]?)*)[a-z0-9])+)\@(((([a-z\d][\.\-_]?){0,62})[a-z\d])+)\.([a-z\d]{2,6})$#i'],
            'direccion' => ['required'],
            'poblacion' => ['required'],
            'codigo_postal' => ['required'],
            'provincia' => ['required'],
            'estado_tarea' => ['required'],
            'fecha_creacion' => [
                'required', 'date_format:Y-m-d\TH:i',
                function ($atribute, $value, $fail) use ($fecha) {
                    if (date("Y-m-d\TH", strtotime($value)) != date("Y-m-d\TH", strtotime($fecha->fecha_creacion))) {
                        $fail('La fecha de creación no se puede modificar.');
                    }
                }
            ],
            'fecha_final' => [
                'nullable', 'date_format:Y-m-d\TH:i',
                function ($atribute, $value, $fail) use ($fecha_creacion) {
                    if (date("Y-m-d\TH", strtotime($value)) <= date("Y-m-d\TH", strtotime($fecha_creacion))) {
                        $fail('La fecha de creación no se puede modificar.');
                    }
                }
            ],
            'anotacion_anterior' => ['nullable'],
            'anotacion_posterior' => ['nullable'],
            'users_id' => ['required'],
            'customers_id' => ['required']

        ]);
        $datos['users_id'] = $users;
        $datos['customers_id'] = $customers;
        task::where('id', '=', $id)->update($datos);
        $tareas = task::paginate(2);
        return view('tareas.tareas_mostrar', compact('tareas'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function confirmarBorrarTarea($id)
    {
        $tarea = task::find($id)->delete();
        $tareas = task::paginate(2);
        return view('tareas.tareas_mostrar', compact('tareas'));
    }

    public function cambiarEstadoTarea(Request $request, $id)
    {
        $tarea = task::find($id);
        return view('tareas.tareas_completar', compact('tarea'));
    }

    public function completarTarea(Request $request, $id)
    {
        if (Auth::user()->tipo == 'Operario') {
            task::where('id', '=', $id)->update(['estado_tarea' =>  $request->estado_tarea]);
            $tareas = task::paginate(2);
            return view('tareas.tareas_mostrar', compact('tareas'));
        } else {
            return view('base');
        }
    }

    public function verInformacionDetallada()
    {
        $tareas = task::paginate(2);
        return view('tareas.tareas_verInformacionDetallada', compact('tareas'));
    }

    public function verTareasPendientes()
    {
        $tareas = task::where('estado_tarea', 'P')->paginate(2);
        return view('tareas.tareas_pendientes', compact('tareas'));
    }

    public function verTareasNoAsignadas()
    {
        $tareas = task::where('users_id', null)->paginate(2);
        return view('tareas.tareas_mostrarNoAsignadas', compact('tareas'));
    }

    public function asignarOperario($id)
    {
        $tarea = task::find($id);

        $provincias = provincias::where('nombre', '!=', $tarea->provincia)->get();
        $empleados = User::where('id', '!=', $tarea->users_id)->get();
        return view('tareas.tareas_modificar', compact('tarea', 'provincias', 'empleados'));
    }

    public function operarioAsignado(Request $request, $id)
    {
        task::where('id', '=', $id)->update(['users_id' =>  $request->users_id]);
        $tareas = task::paginate(2);
        return view('tareas.tareas_mostrar', compact('tareas'));
    }
}
