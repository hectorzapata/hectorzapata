<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Actor;
class ApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $actores = Actor::all();
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
      if (
        array_key_exists('nombre', $request->all()) &&
        array_key_exists('tipo', $request->all()) &&
        array_key_exists('lat', $request->all()) &&
        array_key_exists('lng', $request->all())
      ) {
        if ($request->tipo == 'co' || $request->tipo == 'cl') {
          $actor = Actor::create($request->all());
          if ($actor->id) {
            return array(
              "exito" => true,
              "actor" => $actor
            );
          }else{
            return array(
              "exito" => false,
              "mensaje" => "Ha ocurrido un error, inténtelo de nuevo más tarde"
            );
          }
        }else{
          return array(
            "exito" => false,
            "mensaje" => "El parámetro 'tipo' es incorrecto, valores permitidos ['co', 'cl']"
          );
        }

      }else{
        return array(
          "exito" => false,
          "mensaje" => "Parámetros incompletos"
        );
      }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      $actor = Actor::find($id);
      if ($actor)
        return $actor;
      else
        return array(
          "exito" => false,
          "mensaje" => "No se ha encontrado ningún Actor con el id " . $id
        );
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
      if ($request->tipo) {
        if ($request->tipo != 'co' && $request->tipo != 'cl') {
          return array(
            "exito" => false,
            "mensaje" => "El parámetro 'tipo' es incorrecto, valores permitidos ['co', 'cl']"
          );
        }
      }
      $actor = Actor::find($id);
      if ($actor) {
        $actor->update($request->all());
        $actor = Actor::find($id);
        return array(
          "exito" => true,
          "actor" => $actor
        );
      }else{
        return array(
          "exito" => false,
          "mensaje" => "No se ha encontrado ningún Actor con el id " . $id
        );
      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $actor = Actor::find($id);
      if ($actor) {
        $actor->delete();
        return array(
          "exito" => true
        );
      }else{
        return array(
          "exito" => false,
          "mensaje" => "No se ha encontrado ningún Actor con el id " . $id
        );
      }
    }
}
