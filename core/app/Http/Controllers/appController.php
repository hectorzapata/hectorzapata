<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Actor;

class appController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function app(Request $request)
    {
      Actor::truncate();
      $clientes = [];
      for ($i=1; $i <= $request->clientes; $i++) {
        $actor = Actor::create([
          'nombre' => 'Cliente' . $i,
          'tipo' => 'cl',
          'lat' => $this->coordenadaAleatoria('lat'),
          'lng' => $this->coordenadaAleatoria('lng')
        ]);
        $clientes[$actor->nombre] = $actor;
      }
      $conductores = [];
      for ($i=1; $i <= $request->conductores; $i++) {
        $actor = Actor::create([
          'nombre' => 'Conductor' . $i,
          'tipo' => 'co',
          'lat' => $this->coordenadaAleatoria('lat'),
          'lng' => $this->coordenadaAleatoria('lng')
        ]);
        $conductores[$actor->nombre] = $actor;
      }
      return view('app')->with('datos', array("conductores" => $conductores, "clientes" => $clientes));
    }

    public function coordenadaAleatoria($tipo)
    {
      $lat = rand(696967, 773637);
      $lat = "23.".$lat;
      $lon = rand(1118972, 1751427);
      $lon = "-99.".$lon;
      if ($tipo == "lat")
        return (float)$lat;
      if ($tipo == "lng")
        return (float)$lon;
      return false;
    }

    public function obtPosiciones(Request $request)
    {
      $respuesta = array();
      for ($i=0; $i < $request->n; $i++) {
        array_push($respuesta, $this->coordenadaAleatoria());
      }
      return $respuesta;
    }

    public function nuevasPosiciones()
    {
      $actores = Actor::all();
      $incremento = 0.0100;
      foreach ($actores as $key => $value) {
        $aleatorio = array(
          'enLat' => rand(0,1) == 1,
          'masLat' => rand(0,1) == 1,
          'enLng' => rand(0,1) == 1,
          'masLng' => rand(0,1) == 1
        );
        if ($aleatorio['enLat']) {
          if ($aleatorio['masLat']) {
            $value->lat += $incremento;
          }else{
            $value->lat -= $incremento;
          }
        }
        if ($aleatorio['enLng']) {
          if ($aleatorio['masLng']) {
            $value->lng += $incremento;
          }else{
            $value->lng -= $incremento;
          }
        }
        $value->save();
      }
      return $actores;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
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
}
