<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAccountsRequest;
use App\Http\Requests\Admin\UpdateAccountsRequest;
use App\Models\Account;
use App\Models\User;
use App\Repositories\AccountRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Silber\Bouncer\Database\Role;
use Yajra\DataTables\Facades\DataTables;

class AccountsController extends Controller
{
    /**
     * @var AccountRepository
     */
    protected $accounts;

    /**
     * @param AccountRepository $accounts
     */
    public function __construct(AccountRepository $accounts)
    {
        $this->accounts = $accounts;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getDatatable()
    {
        return DataTables::of($this->accounts->getForDataTable())
            ->escapeColumns(['account_name'])
            ->addColumn('roles', function ($account) {
                return $account->roles->count() ?
                    implode('<br/>', $account->roles->pluck('name')->toArray()) : ' ';
            })
            ->addColumn('actions', function ($account) {
                return $account->action_buttons;
            })
            ->addColumn('attached_users', function ($account) {
                return $account->users->count();
            })
            ->editColumn('active', function ($account) {
                return ($account->active) ? 'Active' : 'Disabled';
            })
            ->editColumn('allow_new_orders', function ($account) {
                return ($account->allow_new_orders) ? 'Yes' : 'No';
            })
            ->editColumn('confirmed_credits', function ($account) {
                return money_format('$%.2n', $account->confirmed_credits / 100);
            })
            ->editColumn('total_debits', function ($account) {
                return money_format('$%.2n', $account->total_debits / 100);
            })
            ->make(true);
    }

    /**
     * Display a listing of Accounts.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.accounts.index');
    }

    /**
     * Show the form for creating new Account.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::get()->pluck('name', 'id')->reverse()->all();

        return view('admin.accounts.create', compact('roles'));
    }

    /**
     * Store a newly created Account in storage.
     *
     * @param  StoreAccountsRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAccountsRequest $request)
    {
        $req = $request->only('last_name', 'first_names', 'email', 'active', 'role_id', 'allow_new_orders');
        $req['password'] = 'NQVdcFAfxc7yJiW1m5CKL33LL5yR3/C54UJxKZBcNGo=';
        $req['account_name'] = $req['last_name'] . ', ' . $req['first_names'];

        $account = Account::create($req);
        $account->assign($req['role_id']);

        $u['account_id'] = $account->id;
        $u['last_name'] = $req['last_name'];
        $u['first_name'] = '(not set)';
        $user = User::create($u);

        return redirect()->route('admin.accounts.index');
    }

    /**
     * Show the form for editing an Account.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     * @throws AuthorizationException
     */
    public function edit($id)
    {
        $account = Account::find($id);
        if (!$account) {
            $msg = 'Unable to edit account with id ' . $id;
            Log::error($msg);
            return redirect()->route('admin.accounts.index')
                ->withFlashWarning($msg);
        }

        $roles = Role::get()->pluck('name', 'id')->reverse()->all();
        $account_role = $account->roles()->pluck('id')->first();

        return view('admin.accounts.edit', compact('account', 'roles', 'account_role'));
    }

    /**
     * Update Account in storage.
     *
     * @param  UpdateAccountsRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAccountsRequest $request, $id)
    {
        $req = $request->only('account_name', 'email', 'active', 'allow_new_orders');

        $account = Account::find($id);
        if (!$account) {
            $msg = 'Unable to update account with id ' . $id;
            Log::error($msg);
            return redirect()->route('admin.accounts.index')
                ->withFlashWarning($msg);
        }

        $account->update($req);

        foreach ($account->roles as $role) {
            $account->retract($role);
        }
        $account->assign($request->input('role_id'));

        return redirect()->route('admin.accounts.index')
            ->withFlashSuccess("Account '" . $req['account_name'] . "' updated.");
    }

    /**
     * Remove an Account from storage using AJAX. The client refreshes
     * itself, so no redirect. Flash a status message to the session.
     *
     * @param  int $id
     * @return mixed
     * @throws  AuthorizationException
     */
    public function destroy($id)
    {
        $account = Account::find($id);
        if (!$account) {
            $msg = 'Unable to delete account with id ' . $id;
            session()->flash('flash_warning', $msg);
            return;
        }

        try {
            $account->delete();
            session()->flash('flash_success', "Account '" . $account->account_name . "' was deleted.");
        } catch (\Exception $e) {

            if ($e->getCode() == 23000) {
                $msg = "Unable to delete account '" . $account->account_name . "': related orders and users must be removed first.";
            } else {
                $msg = $e->getMessage();
            }

            Log::error($msg);
            session()->flash('flash_warning', $msg);
        }
    }
}
