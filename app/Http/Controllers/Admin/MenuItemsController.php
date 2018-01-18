<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreMenuItemsRequest;
use App\Http\Requests\Admin\UpdateMenuItemsRequest;
use App\Models\MenuItem;
use App\Repositories\MenuItemRepository;
use App\Repositories\ProviderRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class MenuItemsController extends Controller
{
    /**
     * @var MenuItemRepository
     * @var ProviderRepository
     */
    protected $menuitems;
    protected $providers;

    /**
     * @param MenuItemRepository $menuitems
     * @param ProviderRepository $providers
     */
    public function __construct(MenuItemRepository $menuitems,
                                ProviderRepository $providers)
    {
        $this->menuitems = $menuitems;
        $this->providers = $providers;
    }

    /**
     * Display a listing of MenuItems.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $providers = $this->providers->getForSelect(true, true);
        $pid = $request->input('pid', 0);

        return view('admin.menuitems.index')
            ->withProviders($providers)
            ->withProviderid($pid);
    }

    /**
     * Display the MenuItems datatable.
     *
     */
    public function show($id)
    {
        return DataTables::of($this->menuitems->getForDataTable($id))
            ->escapeColumns(['item_name'])
            ->editColumn('price', function ($menuitem) {
                return money_format('$%.2n', $menuitem->price / 100);
            })
            ->editColumn('active', function ($menuitem) {
                return ($menuitem->active) ? 'Yes' : 'No';
            })
            ->addColumn('actions', function ($menuitem) {
                return $menuitem->action_buttons;
            })
            ->make(true);
    }

    /**
     * Show the form for creating new MenuItems.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     * @throws AuthorizationException
     */
    public function create(Request $request)
    {
        $pid = $request->input('pid', 0);
        $provider = $this->providers->getForSelectForProvider($pid);

        if (!$provider)
            return redirect()->route('admin.menuitems.index', ['pid' => 0])
                ->withFlashWarning('Unable to create a menu item for provider ' . $pid);

        return view('admin.menuitems.create')
            ->withProvider($provider)
            ->withProviderid($pid);
    }

    /**
     * Store a newly created Menu Item in storage.
     *
     * @param  StoreMenuItemsRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMenuItemsRequest $request)
    {
        $req = $request->only('provider_id', 'item_name', 'description', 'price', 'active');
        $req['price'] = $request->input('price', 0) * 100;

        $msg = '';
        try {
            $menuitem = MenuItem::create($req);
        } catch (\Exception $e) {
            $msg = "Unable to create menu item '" . $req['item_name'] . "': ";

            if ($e->getCode() == 23000) {
                $msg .= 'the item already exists.';
            } else {
                $msg .= $e->getMessage();
            }
        }

        if ($msg) {
            Log::error($msg);
            return redirect()
                ->back()
                ->withInput()
                ->withFlashDanger($msg);
        }

        return redirect()->route('admin.menuitems.index', ['pid' => $req['provider_id']])
            ->withFlashSuccess("Menu item '" . $req['item_name'] . "' created.");
    }


    /**
     * Show the form for editing a Menu Item.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     * @throws AuthorizationException
     */
    public function edit($id)
    {
        $menuitem = MenuItem::find($id);
        if (!$menuitem) {
            $msg = 'Unable to edit menu item with id ' . $id;
            Log::error($msg);
            return redirect()->route('admin.menuitems.index')
                ->withFlashWarning($msg);
        }

        $providers = $this->providers->getForSelectForProvider($menuitem->provider_id);

        return view('admin.menuitems.edit')
            ->withMenuitem($menuitem)
            ->withProviders($providers);
    }

    /**
     * Update Menu Item in storage.
     *
     * @param  UpdateMenuItemsRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMenuItemsRequest $request, $id)
    {
        $req = $request->only('item_name', 'description', 'price', 'active');
        $req['price'] = $request->input('price', 0) * 100;

        $menuitem = MenuItem::find($id);
        if (!$menuitem) {
            $msg = 'Unable to update menu item with id ' . $id;
            Log::error($msg);
            return redirect()->route('admin.menuitems.index')
                ->withFlashWarning($msg);
        }

        $msg = '';

        try {
            $menuitem->update($req);
        } catch (\Exception $e) {
            $msg = "Unable to update menu item '" . $menuitem->item_name . "': ";

            if ($e->getCode() == 23000) {
                $msg .= 'the item already exists.';
            } else {
                $msg .= $e->getMessage();
            }
        }

        if ($msg) {
            Log::error($msg);
            return redirect()
                ->back()
                ->withInput()
                ->withFlashDanger($msg);
        }

        return redirect()->route('admin.menuitems.index', ['pid' => $menuitem->provider_id])
            ->withFlashSuccess("Menu item '" . $req['item_name'] . "' updated.");
    }

    /**
     * Remove a Menu Item from storage using AJAX. The client refreshes
     * itself, so no redirect. Flash a status message to the session.
     *
     * @param  int $id
     * @return mixed
     * @throws  AuthorizationException
     */
    public function destroy($id)
    {
        $menuitem = MenuItem::find($id);
        if (!$menuitem) {
            $msg = 'Unable to delete menu item with id ' . $id;
            session()->flash('flash_warning', $msg);
            return;
        }

        try {
            DB::table('los_lunchdate_menuitems')
                ->where('menuitem_id', $id)
                ->delete();
            $menuitem->delete();
            session()->flash('flash_success', "Menu item '" . $menuitem->item_name . "' was deleted.");
        } catch (\Exception $e) {

            if ($e->getCode() == 23000) {
                $msg = "Unable to delete menu item' " . $menuitem->item_name . "': the menu item is in use.";
            } else {
                $msg = $e->getMessage();
            }

            Log::error($msg);
            session()->flash('flash_warning', $msg);
        }
    }
}
