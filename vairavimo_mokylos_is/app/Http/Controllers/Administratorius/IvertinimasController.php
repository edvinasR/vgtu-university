<?php

namespace App\Http\Controllers\Administratorius;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Ivertinimas;
use Illuminate\Http\Request;
use App\Helpers\Helpers;

class IvertinimasController extends Controller
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
             $ivertinimas = Ivertinimas::where('Ivertinimass', 'LIKE', "%$keyword%")
                ->orWhere('aprasymas', 'LIKE', "%$keyword%")
                ->orWhere('mokinys', 'LIKE', "%$keyword%")
                ->orWhere('paskaita', 'LIKE', "%$keyword%")
                ->paginate($perPage);
        } else {
             $ivertinimas = Ivertinimas::paginate($perPage);
        }

        $paskaitos = Helpers::getPaskaitosInfo();
        $mokiniai = Helpers::getMokiniaiNamesArray();
        
        return view('administratorius.ivertinimas.index', compact('ivertinimas','paskaitos', 'mokiniai'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
    	$paskaitos = Helpers::getPaskaitosInfo();
    	$mokiniai = Helpers::getMokiniaiNamesArray();
        return view('administratorius.ivertinimas.create',compact('paskaitos', 'mokiniai'));
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
    	$paskaita =  $request ->get('paskaita');
    	$mokinys =  $request ->get('mokinys');
    
        $this->validate($request, [
			'aprasymas' => 'required|max:255',
			'ivertinimas' => 'required|digits_between:0,10',
			'mokinys' => 'required|required|unique:ivertinimai,mokinys,NULL,id,paskaita,' . $paskaita,
			'paskaita' => 'required|unique:ivertinimai,paskaita,NULL,id,mokinys,' . $mokinys
		]);
        $requestData = $request->all();
        
        Ivertinimas::create($requestData);

        return redirect('administratrius/ivertinimas')->with('flash_message', 'Ivertinimas sukurtas sėkmingai!');
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
         $ivertinimas = Ivertinimas::findOrFail($id);
         $paskaitos = Helpers::getPaskaitosInfo();
         $mokiniai = Helpers::getMokiniaiNamesArray();
        return view('administratorius.ivertinimas.show', compact('ivertinimas','paskaitos', 'mokiniai'));
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
        $ivertinimas = Ivertinimas::findOrFail($id);
        $paskaitos = Helpers::getPaskaitosInfo();
        $mokiniai = Helpers::getMokiniaiNamesArray();
        return view('administratorius.ivertinimas.edit', compact('ivertinimas','paskaitos', 'mokiniai'));
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
			'aprasymas' => 'required|max:255',
			'ivertinimas' => 'required|digits_between:0,10',
			'mokinys' => 'required',
			'paskaita' => 'required'
		]);
        $requestData = $request->all();
        
        $Ivertinimas = Ivertinimas::findOrFail($id);
        $Ivertinimas->update($requestData);

        return redirect('administratrius/ivertinimas')->with('flash_message', 'Ivertinimas atnaujintas sėkmingai!');
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
        Ivertinimas::destroy($id);

        return redirect('administratrius/ivertinimas')->with('flash_message', 'Įvertinimas ištrintas sėkmingai!');
    }
}
