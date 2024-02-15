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
class MokinysController extends Controller
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
            $mokinys = Mokiny::where('kategorija', 'LIKE', "%$keyword%")
                ->orWhere('grupe', 'LIKE', "%$keyword%")
                ->orWhere('vairavimo_instruktorius', 'LIKE', "%$keyword%")
                ->orWhere('naudotojas', 'LIKE', "%$keyword%")
                ->paginate($perPage);
        } else {
            $mokinys = Mokiny::paginate($perPage);
        }

         $users =  Helpers::getNaudotojaiNamesArray('Mokinys');
         $inst = Helpers::getInstruktoriaiNamesArray();
         $grupe = Helpers::getGrupesNames(null);
        return view('administratorius.mokinys.index', compact('mokinys','users','inst','grupe'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
    	$users =  Helpers::getNaudotojaiWithoutRoles('Mokinys');
    	$inst = Helpers::getInstruktoriaiNamesArray();
    	$grupe = Helpers::getGrupesNames(null);
        return view('administratorius.mokinys.create',compact('users','inst','grupe'));
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
			'kategorija' => 'required|max:3',
			'naudotojas' => 'required|unique:mokiniai,naudotojas',
			'grupe' => 'required'
		]);
        $requestData = $request->all();
        
        Mokiny::create($requestData);

        return redirect('administratrius/mokinys')->with('flash_message', 'Mokinys sukurtas sėkmingai!');
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
        $mokiny = Mokiny::findOrFail($id);
        $users =  Helpers::getNaudotojaiNamesArray('Mokinys');
        $inst = Helpers::getInstruktoriaiNamesArray();
        $grupe = Helpers::getGrupesNames($mokiny->kategorija);
       
        		
        return view('administratorius.mokinys.show', compact('mokiny','users','inst','grupe'));
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
    	
         $mokiny = Mokiny::findOrFail($id);

         $users =  Helpers::getNaudotojaiNamesArray('Mokinys');
         $inst = Helpers::getInstruktoriaiNamesArray();
         $grupe = Helpers::getGrupesNames(null);
        return view('administratorius.mokinys.edit', compact('mokiny','users','inst','grupe'));
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
			'kategorija' => 'required|max:3',
			'naudotojas' => 'required',
			'grupe' => 'required'
		]);
        $requestData = $request->all();
        
        $mokiny = Mokiny::findOrFail($id);
        $mokiny->update($requestData);

        return redirect('administratrius/mokinys')->with('flash_message', 'Mokinys atnaujintas sėkmingai!');
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
        Mokiny::destroy($id);

        return redirect('administratrius/mokinys')->with('flash_message', 'Mokinys ištrintas!');
    }
}
