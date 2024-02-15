<?php

namespace App\Http\Controllers\Administratorius;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Zinute;
use Illuminate\Http\Request;
use App\Helpers\Helpers;

class ZinuteController extends Controller
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
            $zinute = Zinute::where('tema', 'LIKE', "%$keyword%")
                ->orWhere('perskaitytas', 'LIKE', "%$keyword%")
                ->orWhere('zinute', 'LIKE', "%$keyword%")
                ->orWhere('instruktorius', 'LIKE', "%$keyword%")
                ->orWhere('mokinys', 'LIKE', "%$keyword%")
                ->paginate($perPage);
        } else {
            $zinute = Zinute::paginate($perPage);
        }

        $mokiniai = Helpers::getMokiniaiNamesArray();
        $instruktoriai = Helpers::getInstruktoriaiNamesArray();
        
        return view('administratorius.zinute.index', compact('zinute','mokiniai','instruktoriai'));
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
        return view('administratorius.zinute.create',compact('mokiniai','instruktoriai'));
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
			'zinute' => 'required|max:255',
			'tema' => 'required|max:30',
			'perskaitytas' => 'required',
			'mokinys' => 'required',
			'instruktorius' => 'required'
		]);
        $requestData = $request->all();
        
        Zinute::create($requestData);

        return redirect('administratrius/zinute')->with('flash_message', 'Žinutė sukurta sėkmingai added!');
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
        $zinute = Zinute::findOrFail($id);

        $mokiniai = Helpers::getMokiniaiNamesArray();
        $instruktoriai = Helpers::getInstruktoriaiNamesArray();
        return view('administratorius.zinute.show', compact('zinute', 'mokiniai', 'instruktoriai'));
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
        $zinute = Zinute::findOrFail($id);
        $mokiniai = Helpers::getMokiniaiNamesArray();
        $instruktoriai = Helpers::getInstruktoriaiNamesArray();
        return view('administratorius.zinute.edit', compact('zinute', 'mokiniai', 'instruktoriai'));
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
			'zinute' => 'required|max:255',
			'tema' => 'required|max:30',
			'perskaitytas' => 'required',
			'mokinys' => 'required',
			'instruktorius' => 'required'
		]);
        $requestData = $request->all();
        
        $zinute = Zinute::findOrFail($id);
        $zinute->update($requestData);

        return redirect('administratrius/zinute')->with('flash_message', 'Žinutė atnaujinta sėkmingai');
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
        Zinute::destroy($id);

        return redirect('administratrius/zinute')->with('flash_message', 'Žinutė ištrinta sėkmingai!');
    }
}
