<?php

namespace App\Http\Controllers\Administratorius;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\GrupiuPaskaitos;
use Illuminate\Http\Request;
use App\Helpers\Helpers;

class GrupiuPaskaitosController extends Controller
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
            $grupiupaskaito = GrupiuPaskaitos::where('paskaita', 'LIKE', "%$keyword%")
                ->orWhere('grupe', 'LIKE', "%$keyword%")
                ->paginate($perPage);
        } else {
            $grupiupaskaito = GrupiuPaskaitos::paginate($perPage);
        }

        $grupes = Helpers::getGrupesNames(null);
        $paskaitos = Helpers::getPaskaitosInfo();
        return view('administratorius.grupiu-paskaitos.index', compact('grupiupaskaito','grupes', 'paskaitos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
    	$grupes = Helpers::getGrupesNames(null);
    	$paskaitos = Helpers::getPaskaitosInfo();
        return view('administratorius.grupiu-paskaitos.create',compact('grupes', 'paskaitos'));
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
    	$grupe =  $request ->get('grupe');
    	$paskaita =  $request ->get('paskaita');
    	
        $this->validate($request, [
			'paskaita' => 'required|unique:grupiu_paskaitos,paskaita,NULL,id,grupe,' . $grupe,
			'grupe' => 'required|unique:grupiu_paskaitos,grupe,NULL,id,paskaita,' . $paskaita
		]);
        $requestData = $request->all();
        
        GrupiuPaskaitos::create($requestData);

        return redirect('administratrius/grupiu-paskaitos')->with('flash_message', 'Grupės paskaitos priskyrymas išsaugotas!');
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
        $grupiupaskaito= GrupiuPaskaitos::findOrFail($id);
        	$grupes = Helpers::getGrupesNames(null);
        	$paskaitos = Helpers::getPaskaitosInfo();
        return view('administratorius.grupiu-paskaitos.show', compact('grupiupaskaito','grupes', 'paskaitos'));
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
        $grupiupaskaito = GrupiuPaskaitos::findOrFail($id);
        $grupes = Helpers::getGrupesNames(null);
        $paskaitos = Helpers::getPaskaitosInfo();
        return view('administratorius.grupiu-paskaitos.edit', compact('grupiupaskaito','grupes','paskaitos'));
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
    	$grupe =  $request ->get('grupe');
    	$paskaita =  $request ->get('paskaita');
    	
        $this->validate($request, [
			'paskaita' => 'required|unique:grupiu_paskaitos,paskaita,NULL,id,grupe,' . $grupe,
			'grupe' => 'required|unique:grupiu_paskaitos,grupe,NULL,id,paskaita,' . $paskaita
		]);
        $requestData = $request->all();
        
        $GrupiuPaskaitos = GrupiuPaskaitos::findOrFail($id);
        $GrupiuPaskaitos->update($requestData);

        return redirect('administratrius/grupiu-paskaitos')->with('flash_message', 'Grupės paskaitos priskyrymas atnaujintas sėkmingai');
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
        GrupiuPaskaitos::destroy($id);

        return redirect('administratrius/grupiu-paskaitos')->with('flash_message', 'Grupės paskaitos įrašas sėkmingai ištrintas...');
    }
}
