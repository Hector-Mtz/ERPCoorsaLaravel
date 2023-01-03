<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Plataforma;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

use App\Exports\UsersExport;
use App\Models\empleados_puesto;
use App\Models\UsuarioPosicion;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{



    /**
     * Get list of users
     */
    public function list()
    {
        return response()->json(User::select('id')
            ->selectRaw("concat(users.name,IFNULL(concat(' ',users.apellido_paterno), '')) as name")->get());
    }

    public function export($activo) 
    {
        if($activo === 'activo')
        {
            return Excel::download(new UsersExport(1), 'Reporte_Empleados_Activos.xlsx');
        }

        if($activo === 'inactivo')
        {
            return Excel::download(new UsersExport(0), 'Reporte_Empleados_Inactivos.xlsx');
        }
    }

    public function viewCard ()
    {
        return Inertia::render('CardViewUser/IndexCard',
        []);
    }

    public function getPuesto($id)
    {
        $puesto  = empleados_puesto::select('puestos.name AS puesto')
        ->where('empleado_id','=',$id)
        ->join('puestos','empleados_puestos.puesto_id','=','puestos.id')
        ->get();

        return $puesto[0];
    }
}
