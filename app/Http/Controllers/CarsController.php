<?php

namespace App\Http\Controllers;

use App\Models\Cars;
use App\Http\Requests\CarsRequest;
use \Illuminate\Http\Response;

class CarsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Cars::paginate(10);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CarsRequest $request)
    {
        return Cars::create($request->validated());
    }

    public function show($id)
    {
        return Cars::findOrFail($id);
    }

    public function update(CarsRequest $request, Cars $cars)
    {
        $cars->fill($request->validated());
        return $cars->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\cars  $cars
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cars $cars)
    {
        if ($cars->delete()) {
            return response(null, Response::HTTP_NO_CONTENT);
        }
        return null;
    }
}
