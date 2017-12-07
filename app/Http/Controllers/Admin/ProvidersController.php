<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProvidersRequest;
use App\Http\Requests\Admin\UpdateProvidersRequest;
use App\Models\Provider;
use App\Repositories\ProviderRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;

class ProvidersController extends Controller
{
    /**
     * @var ProviderRepository
     */
    protected $providers;

    /**
     * @param ProviderRepository $providers
     */
    public function __construct(ProviderRepository $providers)
    {
        $this->providers = $providers;
    }

    /**
     * @return array
     */
    private function getImageFiles(): array
    {
        $valid_extensions = ['png', 'jpg', 'svg'];
        $files = \File::files(config('app.provider_image_directory'));
        $validfiles = array();
        $validfiles[''] = '- Select -';
        foreach ($files as $file) {
            $extension = \File::extension($file);
            if (in_array($extension, $valid_extensions))
                $validfiles[basename($file)] = basename($file);
        }

        return $validfiles;
    }

    /**
     * Display a listing of Providers.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.providers.index');
    }

    /**
     * Display a listing of Providers.
     */
    public function show($id)
    {
        return DataTables::of($this->providers->getForDataTable())
            ->escapeColumns(['provider_name', 'provider_image', 'provider_url', 'provider_includes'])
            ->addColumn('actions', function ($provider) {
                return $provider->action_buttons;
            })
            ->editColumn('allow_orders', function ($user) {
                return ($user->allow_orders) ? 'Yes' : 'No';
            })
            ->make(true);
    }

    /**
     * Show the form for creating new Providers.
     *
     * @return \Illuminate\Http\Response
     * @throws AuthorizationException
     */
    public function create()
    {
        return view('admin.providers.create')
            ->withImagefiles($this->getImageFiles());
    }

    /**
     * Store a newly created Provider in storage.
     *
     * @param  StoreProvidersRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProvidersRequest $request)
    {
        $req = $request->only('provider_name', 'provider_image', 'provider_url', 'allow_orders', 'provider_includes');
        $provider = Provider::create($req);
        return redirect()->route('admin.providers.index')
            ->withFlashSuccess("Provider '" . $req['provider_name'] . "' created.");
    }

    /**
     * Show the form for editing a Provider.
     *
     * @param  int
     * @return \Illuminate\Http\Response
     * @throws AuthorizationException
     */
    public function edit($id)
    {
        $provider = Provider::find($id);
        if (!$provider) {
            $msg = 'Unable to edit provider with id ' . $id;
            Log::error($msg);
            return redirect()->route('admin.providers.index')
                ->withFlashWarning($msg);
        }

        return view('admin.providers.edit')
            ->withProvider($provider)
            ->withImagefiles($this->getImageFiles());
    }

    /**
     * Update Provider in storage.
     *
     * @param  UpdateProvidersRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProvidersRequest $request, $id)
    {
        $req = $request->only('provider_name', 'provider_image', 'provider_url', 'allow_orders', 'provider_includes');

        $provider = Provider::find($id);
        if (!$provider) {
            $msg = 'Unable to update provider with id ' . $id;
            Log::error($msg);
            return redirect()->route('admin.providers.index')
                ->withFlashWarning($msg);
        }

        $provider->update($req);

        return redirect()->route('admin.providers.index')
            ->withFlashSuccess("Provider '" . $req['provider_name'] . "' updated.");
    }

    /**
     * Remove a Provider from storage using AJAX. The client refreshes
     * itself, so no redirect. Flash a status message to the session.
     *
     * @param  int $id
     * @return mixed
     * @throws  AuthorizationException
     */
    public function destroy($id)
    {
        $provider = Provider::find($id);
        if (!$provider) {
            $msg = 'Unable to delete provider with id ' . $id;
            session()->flash('flash_warning', $msg);
            return;
        }

        try {
            $provider->delete();
            session()->flash('flash_success', "Provider '" . $provider->provider_name . "' was deleted.");
        } catch (\Exception $e) {

            if ($e->getCode() == 23000) {
                $msg = "Unable to delete provider '" . $provider->provider_name . "'': related menu items must be deleted first.";
            } else {
                $msg = $e->getMessage();
            }

            Log::error($msg);
            session()->flash('flash_warning', $msg);
        }
    }
}
