<?php

namespace App\Http\Controllers\Administratorius;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Instruktorius;
use Illuminate\Http\Request;
use App\Helpers\Helpers;

class InstruktoriusController extends Controller
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
            $instruktorius = Instruktorius::where('transporto_priemones_numeris', 'LIKE', "%$keyword%")
                ->orWhere('telefonas', 'LIKE', "%$keyword%")
                ->orWhere('naudotojas', 'LIKE', "%$keyword%")
                ->paginate($perPage);
        } else {
            $instruktorius = Instruktorius::paginate($perPage);
        }

        $instruktoriai = Helpers::getNaudotojaiNamesArray(['Praktinio vairavimo instruktorius', 'KET dėstytojas']);
        return view('administratorius.instruktorius.index', compact('instruktorius','instruktoriai'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
    	$instruktoriai = Helpers::getNaudotojaiNamesArray(['Praktinio vairavimo instruktorius', 'KET dėstytojas']);
        return view('administratorius.instruktorius.create',compact('instruktoriai'));
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
			'transporto_priemones_numeris' => 'required|max:10',
			'telefonas' => 'required',
        	'naudotojas' => 'required|unique:instruktoriai,naudotojas'
		]);
        $requestData = $request->all();
        
        Instruktorius::create($requestData);

        return redirect('administratrius/instruktorius')->with('flash_message', 'Instruktorius sukurtas sėkmingai');
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
        $instruktorius = Instruktorius::findOrFail($id);
        $instruktoriai = Helpers::getNaudotojaiNamesArray(['Praktinio vairavimo instruktorius', 'KET dėstytojas']);
        return view('administratorius.instruktorius.show', compact('instruktorius','instruktoriai'));
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
        $instruktorius = Instruktorius::findOrFail($id);
        $instruktoriai = Helpers::getNaudotojaiNamesArray(['Praktinio vairavimo instruktorius', 'KET dėstytojas']);
        return view('administratorius.instruktorius.edit', compact('instruktorius','instruktoriai'));
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
			'transporto_priemones_numeris' => 'required|max:10',
			'telefonas' => 'required'
		]);
        $requestData = $request->all();
        
        $instruktorius = Instruktorius::findOrFail($id);
        $instruktorius->update($requestData);

        return redirect('administratrius/instruktorius')->with('flash_message', 'Instruktorius atnaujintas sėkmingai!');
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
        Instruktorius::destroy($id);

        return redirect('administratrius/instruktorius')->with('flash_message', 'Instruktorius ištrintias sėkmingiai!');
    }
}
