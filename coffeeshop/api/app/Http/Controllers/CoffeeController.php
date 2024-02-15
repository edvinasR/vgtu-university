<?php

namespace App\Http\Controllers;

use App\Http\Resources\CoffeeResource;
use App\Coffee;
use App\Http\Requests\CoffeeStoreRequest;
use App\Http\Requests\CoffeeUpdateRequest;
use App\Http\Requests\CoffeListRequest;
use App\Http\Responses\ForbiddenResponse;
use Illuminate\Http\Request;

// All exceptions thrown inside this controller will be handled by global exception hendler inside App\Exceptions\Handler class
class CoffeeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param  \App\Http\Requests\CoffeListRequest  $request
     */
    public function index(CoffeListRequest $request)
    {
        return CoffeeResource::collection(Coffee::where('user_id',$request -> user()->id)->orderBy('updated_at','DESC')->paginate($request->getPagination()));
    }

    /**
     * Store a newly created resource in storage.
     * @param  \App\Http\Requests\CoffeeStoreRequest  $request
     * @return \App\Http\Resources\CoffeeResource
     */
    public function store(CoffeeStoreRequest $request)
    {
        // Validation already done inside CoffeeStoreRequest class
        $coffee = Coffee::create($request -> getData());
        return new CoffeeResource($coffee);
    }

    /**
     * Display the specified resource.
     * @param  Coffee  $coffee
     * @return \App\Http\Resources\CoffeeResource
     */
    public function show(Request $request, Coffee $coffee)
    {
        if ($request->user()->id !== $coffee->user_id) {
            return (new ForbiddenResponse())->setMessage('You can view only our own created coffee')->send();
        }
        return new CoffeeResource($coffee);
    }

    /**
     * Update the specified resource in storage.
     * @param  \App\Http\Requests\CoffeeUpdateRequest  $request
     * @param  Coffee  $coffee
     * @return \App\Http\Resources\CoffeeResource
     */
    public function update(CoffeeUpdateRequest $request, Coffee $coffee)
    {
        // Validation already done inside CoffeeUpdateRequest class
        if ($request->user()->id !== $coffee->user_id) {
            return (new ForbiddenResponse())->setMessage('You can edit only our own coffe list.')->send();
        }
        $coffee->update($request->getData());
        return new CoffeeResource($coffee);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Coffee  $coffee
     * @return \App\Http\Resources\CoffeeResource
     */
    public function destroy(Coffee $coffee)
    {
        $coffee->delete();
        return new CoffeeResource($coffee);
    }
}
