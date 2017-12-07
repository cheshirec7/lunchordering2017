<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAbilitiesRequest;
use App\Http\Requests\Admin\UpdateAbilitiesRequest;
use Illuminate\Http\Request;
use Silber\Bouncer\Database\Ability;

class AbilitiesController extends Controller
{
    /**
     * Display a listing of Abilities.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $abilities = Ability::all();
        return view('admin.abilities.index', compact('abilities'));
    }

    /**
     * Show the form for creating new Ability.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.abilities.create');
    }

    /**
     * Store a newly created Ability in storage.
     *
     * @param  \App\Http\Requests\StoreAbilitiesRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAbilitiesRequest $request)
    {
        Ability::create($request->all());
        return redirect()->route('admin.abilities.index');
    }


    /**
     * Show the form for editing Ability.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $ability = Ability::findOrFail($id);
        return view('admin.abilities.edit', compact('ability'));
    }

    /**
     * Update Ability in storage.
     *
     * @param  \App\Http\Requests\UpdateAbilitiesRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAbilitiesRequest $request, $id)
    {
        $ability = Ability::findOrFail($id);
        $ability->update($request->all());
        return redirect()->route('admin.abilities.index');
    }


    /**
     * Remove Ability from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ability = Ability::findOrFail($id);
        $ability->delete();

        return redirect()->route('admin.abilities.index');
    }

    /**
     * Delete all selected Ability at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if ($request->input('ids')) {
            $entries = Ability::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
    }

}
