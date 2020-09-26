<?php

namespace MiExpresionCuenta\Http\Controllers;

//use Illuminate\Http\Request;
use MiExpresionCuenta\Http\Requests\StoreActividadesRequest;
use MiExpresionCuenta\Opciones;
use MiExpresionCuenta\Actividades;



class ActividadesController extends Controller
{
    public function index( $idsubtema ){
        
        $actvidades = Actividades::with(['ActividadPreguntas' => function ( $query ) {
            $query->inRandomOrder()
            ->with(['PreguntasOpciones' => function( $query ){
                $query->inRandomOrder();
            }]);
        }])
        ->whereHas('ActividadPreguntas')
        ->where('idsubtema','=', $idsubtema)
        ->get();
        
    
        return view('actividades.actividadSeleccionUnica', compact('actvidades'));
    }
    public function store ( StoreActividadesRequest $request )
    {   /*
        $opciones = $request->input('preguntas');
        */
        $opciones = Opciones::find(array_values($request->input('preguntas')));
        
        print_r($opciones);
        $resultado = auth()->user()->userResultados()->create([
            'puntos_totales' => $opciones->sum('puntos')
        ]);
            
        $preguntas = $opciones->mapWithKeys( function ( $opcion ) {
            return [$opcion->id_pregunta => [
                'id_opcion' => $opcion->id,
                'puntos' => $opcion->puntos
            ]
        ];
        })->toArray();

        $resultado->preguntas()->sync($preguntas);
        
        return redirect()->route('resultado.show', $resultado->id);
    }
}
