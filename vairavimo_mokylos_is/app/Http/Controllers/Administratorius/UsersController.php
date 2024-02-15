<?php

namespace App\Http\Controllers\Administratorius;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Mokiny;
use Illuminate\Http\Request;
use Log;
use App\User;
use App\Instruktorius;
use App\KET_grupe;
use App\Helpers\Helpers;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
	
	
    public function index(Request $request)
    {
    	

        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $naudotojas = User::where('name', 'LIKE', "%$keyword%")
                ->orWhere('surename', 'LIKE', "%$keyword%")
                ->orWhere('email', 'LIKE', "%$keyword%")
                ->paginate($perPage);
        } else {
            $naudotojas = User::orderBy('teises_FK')->paginate($perPage);
        }

         $teises =  Helpers::getTeisesArray();

        return view('administratorius.naudotojas.index', compact('naudotojas','teises'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
    
        $teises =  Helpers::getTeisesArray();
        return view('administratorius.naudotojas.create',compact('teises'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->validate($request, [
			'name' => 'required|max:50',
			'surename' => 'required|max:50',
            'email' =>	'email|required|unique:users,email',
        	'password'  => 'required'
		]);
        
        
        $requestData = $request->all();
        $requestData['password']  = bcrypt( $requestData['password']);
        Log::info($requestData);
        User::create($requestData);
		
        return redirect('administratrius/naudotojas')->with('flash_message', 'Naudotojas sukurtas sėkmingai!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $naudotojas = User::findOrFail($id);
        $teises =  Helpers::getTeisesArray();		
        return view('administratorius.naudotojas.show',  compact('naudotojas','teises'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
    	
        $naudotojas = User::findOrFail($id);
        $teises =  Helpers::getTeisesArray();	
        return view('administratorius.naudotojas.edit', compact('naudotojas','teises'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
			'name' => 'required|max:50',
			'surename' => 'required|max:50',
			'email' => 'required|email',
        	'password'  => 'required'
		]);
        $requestData = $request->all();
       unset($requestData['password']);
        
        $mokiny = User::findOrFail($id);
        $mokiny->update($requestData);

        return redirect('administratrius/naudotojas')->with('flash_message', 'Naudotojas atnaujintas sėkmingai!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        User::destroy($id);

        return redirect('administratrius/naudotojas')->with('flash_message', 'Naudotojas ištrintas!');
    }
}
