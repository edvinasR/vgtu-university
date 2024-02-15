<?php

namespace App\Http\Controllers\Administratorius;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Paskaita;
use Illuminate\Http\Request;
use App\Helpers\Helpers;

class PaskaitaController extends Controller
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
            $paskaita = Paskaita::where('pavadinimas', 'LIKE', "%$keyword%")
                ->orWhere('vieta', 'LIKE', "%$keyword%")
                ->orWhere('praktine_paskaita', 'LIKE', "%$keyword%")
                ->orWhere('pradzia', 'LIKE', "%$keyword%")
                ->orWhere('pabaiga', 'LIKE', "%$keyword%")
                ->orWhere('aprasymas', 'LIKE', "%$keyword%")
                ->orWhere('instruktorius', 'LIKE', "%$keyword%")
                ->orWhere('mokinys', 'LIKE', "%$keyword%")
                ->paginate($perPage);
        } else {
            $paskaita = Paskaita::paginate($perPage);
        }

        $mokiniai = Helpers::getMokiniaiNamesArray();
        $instruktoriai = Helpers::getInstruktoriaiNamesArray();
        return view('administratorius.paskaita.index', compact('paskaita','mokiniai', 'instruktoriai'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
    	$mokiniai = Helpers::getMokiniaiNamesArray();
    	$instruktoriai = Helpers::getInstruktoriaiNamesArray();
        return view('administratorius.paskaita.create', compact('mokiniai', 'instruktoriai'));
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
			'pavadinimas' => 'required|max:30',
			'aprasymas' => 'required|max:255',
			'praktine_paskaita' => 'required',
			'vieta' => 'required',
			'instruktorius' => 'required',
			'pradzia' => 'required',
			'pabaiga' => 'required'
		]);
        $requestData = $request->all();
        
        Paskaita::create($requestData);

        return redirect('administratrius/paskaita')->with('flash_message', 'Paskaitos įrašas sukurtas sėkmingai');
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
        $paskaita = Paskaita::findOrFail($id);
        $mokiniai = Helpers::getMokiniaiNamesArray();
        $instruktoriai = Helpers::getInstruktoriaiNamesArray();
        return view('administratorius.paskaita.show', compact('paskaita','mokiniai', 'instruktoriai'));
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
        $paskaita = Paskaita::findOrFail($id);
        $mokiniai = Helpers::getMokiniaiNamesArray();
        $instruktoriai = Helpers::getInstruktoriaiNamesArray();
        return view('administratorius.paskaita.edit', compact('paskaita','mokiniai', 'instruktoriai'));
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
			'pavadinimas' => 'required|max:30',
			'aprasymas' => 'required|max:255',
			'praktine_paskaita' => 'required',
			'vieta' => 'required',
			'instruktorius' => 'required',
			'pradzia' => 'required',
			'pabaiga' => 'required'
		]);
        $requestData = $request->all();
        
        $Paskaita = Paskaita::findOrFail($id);
        $Paskaita->update($requestData);

        return redirect('administratrius/paskaita')->with('flash_message', 'Paskaitos įrašas sėkmingai atnaujintas');
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
        Paskaita::destroy($id);

        return redirect('administratrius/paskaita')->with('flash_message', 'Paskaitos įrašas sėkmingai ištrintas');
    }
}
