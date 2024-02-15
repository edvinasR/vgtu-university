<?php

namespace App\Http\Controllers\Administratorius;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\KET_grupe;
use Illuminate\Http\Request;

class KET_grupeController extends Controller
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
            $ket_grupe = KET_grupe::where('kategorija', 'LIKE', "%$keyword%")
                ->orWhere('pavadinimas', 'LIKE', "%$keyword%")
                ->paginate($perPage);
        } else {
            $ket_grupe = KET_grupe::paginate($perPage);
        }

        return view('administratorius.k-e-t_grupe.index', compact('ket_grupe'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('administratorius.k-e-t_grupe.create');
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
			'kategorija' => 'required|max:3'
		]);
        $requestData = $request->all();
        
        KET_grupe::create($requestData);

        return redirect('administratrius/ket_grupe')->with('flash_message', 'KET grupė sukurta sėkmingai!');
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
        $ket_grupe = KET_grupe::findOrFail($id);

        return view('administratorius.k-e-t_grupe.show', compact('ket_grupe'));
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
        $ket_grupe = KET_grupe::findOrFail($id);

        return view('administratorius.k-e-t_grupe.edit', compact('ket_grupe'));
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
			'kategorija' => 'required|max:3'
		]);
        $requestData = $request->all();
        
        $ket_grupe = KET_grupe::findOrFail($id);
        $ket_grupe->update($requestData);

        return redirect('administratrius/ket_grupe')->with('flash_message', 'KET grupė atnaujnita sėkmingai!');
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
        KET_grupe::destroy($id);

        return redirect('administratrius/ket_grupe')->with('flash_message', 'KET grupė ištrinta sėkmingai!');
    }
}
