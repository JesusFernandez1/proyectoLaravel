<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\task;

class taskController extends Controller {

    public function verTareas() {
        $tareas = task::all();
        return view('tareas.tareas_mostrar', compact('tareas'));
    }

    public function crearTarea(Request $request) {

        $tareas = task::all();
        if ($_POST) {

            if ("condition") {
                $tarea = new task();
                $tarea->nombre = $request->nombre;
                $tarea->apellido = $request->apellido;
                $tarea->correo = $request->correo;
                $tarea->save();

                return view('tareas.tareas_mostrar');
            } else {
                return view('tareas.tareas_modificar');
            }
        } else {
            return view('tareas.tareas_mostrar', compact('tareas'));
        }
    }

    public function modificarTarea($id) {

        $tareas = task::all();
        $tarea = task::where('id', 1)->get();
        if ($_POST) {
            return view('tareas.tareas_mostrar', compact('tareas'));
        } else {
            return view('tareas.tareas_modificar', compact('tarea'));
        }
    }

     public function borrarTarea($id) {
        
        $tareas = task::all();
        $tarea = task::where('id', 1)->get();
        if ($_POST) {
            //borra la tarea
            return view('tareas.tareas_mostrar', compact('tareas'));
        } else {
            return view('tareas.tareas_modificar', compact('tarea'));
        }
    }

    public function completarTarea($id) {
        $tareas = task::all();
        $tarea = task::where('id', 1)->get();
        if ($_POST) {
            //completa la tarea
            return view('tareas.tareas_mostrar', compact('tareas'));
        } else {
            return view('tareas.tareas_modificar', compact('tarea'));
        }
    }

    public function verTareasCompletas() {
        $tareas = task::all();
        return view('tareas.tareas_mostrar_completa', compact('tareas'));
    }

    public function verTareasPendientes() {
        $tarea = task::where('estado_tarea', 'P')->get();
        return view('tareas.tareas_mostrar_pendientes', compact('tarea'));
    }
}
