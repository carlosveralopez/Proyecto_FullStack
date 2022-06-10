<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{    
    /**
     * create
     * Funcion para crear una Categoria

     * @param  json $request parametros para la creacion de la Categoria
     * @return void
     */
    public function create(Request $request){
        $data=$request->only(['nombre', 'descripcion']);

        $request->validate([
            'nombre' => 'required|string|max:20',
            'descripcion'=>'nullable|string|max:50'
        ]);

        try{
            DB::table('categories')->insert($data);
            return response()->json([
                'success' => true,
                'mensaje' => 'Categoria creada con exito',
                'data'    => $data
            ], 200);
        }catch (\Exception $e){
            return response()->json([
                'success' => false,
                'mensaje' => 'La categoria no ha sido creada',
                'data'    => null,
            ], 500);
        }
    }
    
    /**
     * get
     * Devuelve la Categoria a partir de la ID

     * @param  mixed $id id de la categoria
     * @return void
     */
    public function get($id){
        $categoria=Category::find($id);

        if($categoria==null){
            return response()->json([
                'success' => false,
                'mensaje' => 'No existe esta categoria',
                'data'    => null
            ], 400);
        }

        return response()->json([
            'success' => true,
            'mensaje' => 'Categoria recogida',
            'data'    => $categoria
        ], 200);
    }
    
    /**
     * getAll
     * Devuelve todas las Categorias
     * 
     * @return void
     */
    public function getAll(){
        return response()->json([
            'success' => true,
            'mensaje' => 'Todas los categorias',
            'data'    => Category::all()
        ], 200);
    }
    
    /**
     * delete
     * Borra la Categoria a partir del ID
     * 
     * @param  mixed $id
     * @return void
     */
    public function delete($id){
        $categoria=Category::find($id);

        if($categoria==null){
            return response()->json([
                'success' => false,
                'mensaje' => 'No existe esta categoria',
                'data'    => null
            ], 400);
        }

        DB::table('categories')->where('id', $id)->delete();

        return response()->json([
            'success' => true,
            'mensaje' => 'Categoria borrada con exito',
            'data'    => $categoria
        ], 200);
    }
    
    /**
     * update
     * Actualiza la Categoria
     * 
     * @param  mixed $request
     * @param  mixed $id
     * @return void
     */
    public function update(Request $request, $id){
        $data=$request->only(['nombre', 'descripcion']);

        $request->validate([
            'nombre' => 'nullable|string|max:20',
            'descripcion'=>'nullable|string|max:50',
        ]);

        $nombre=$data['nombre'];
        $descripcion=$data['descripcion'];

        $data=[];

        if(!empty($nombre)){
            $data['nombre']=$nombre;
        }

        if(!empty($descripcion)){
            $data['descripcion']=$descripcion;
        }

        Category::where('id', $id)->update($data);

        $categoria=Category::find($id);

        return response()->json($categoria, 200);
    }
    
    /**
     * products
     * 
     * @param  mixed $id
     * @return void
     */
    public function products($id){
        $categoria=Category::find($id);

        if($categoria==null){
            return response()->json([
                'success' => false,
                'mensaje' => 'No existe esta categoria',
                'data'    => null
            ]);
        }

        return $categoria->products;
    }
}
