<?php

namespace App\Http\Controllers\Administratorius;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\MokinioBusena;
use Illuminate\Http\Request;
use App\Helpers\Helpers;

class MokinioBusenaController extends Controller
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
            $mokiniobusena = MokinioBusena::where('teorinio_egzamino_ivertinimas', 'LIKE', "%$keyword%")
                ->orWhere('praktinio_egzamino_ivertinimas', 'LIKE', "%$keyword%")
                ->orWhere('mokinys', 'LIKE', "%$keyword%")
                ->paginate($perPage);
        } else {
            $mokiniobusena = MokinioBusena::paginate($perPage);
        }
		$mokiniai = Helpers::getMokiniaiNamesArray();
        return view('administratorius.mokinio-busena.index', compact('mokiniobusena','mokiniai'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
    	$mokiniai = Helpers::getMokiniaiNamesArray();
        return view('administratorius.mokinio-busena.create',compact('mokiniai'));
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
    			'teorinio_egzamino_ivertinimas' => 'required|integer|between:0,100',
    			'praktinio_egzamino_ivertinimas' => 'required|integer|between:0,100',	
    	]);
        $requestData = $request->all();
        MokinioBusena::create($requestData);
        return redirect('administratrius/mokinio-busena')->with('flash_message', 'Mokinio Būsena sėkmingai išsaugota!');
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
        $mokiniobusena = MokinioBusena::findOrFail($id);
        $mokiniai = Helpers::getMokiniaiNamesArray();
        return view('administratorius.mokinio-busena.show', compact('mokiniobusena', 'mokiniai'));
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
        $mokiniobusena = MokinioBusena::findOrFail($id);
        $mokiniai = Helpers::getMokiniaiNamesArray();
        return view('administratorius.mokinio-busena.edit', compact('mokiniobusena','mokiniai'));
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
    			'teorinio_egzamino_ivertinimas' => 'required|integer|between:0,100',
    			'praktinio_egzamino_ivertinimas' => 'required|integer|between:0,100',

    	]);
        $requestData = $request->all();     
        $mokiniobusena = MokinioBusena::findOrFail($id);
        $mokiniobusena->update($requestData);

        return redirect('administratrius/mokinio-busena')->with('flash_message', 'Mokinio būsena atnaujnita!');
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
        MokinioBusena::destroy($id);

        return redirect('administratrius/mokinio-busena')->with('flash_message', 'Mokinio būsena ištrinta!');
    }
}
