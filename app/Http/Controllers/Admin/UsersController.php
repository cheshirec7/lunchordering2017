<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUsersRequest;
use App\Http\Requests\Admin\UpdateUsersRequest;
use App\Models\User;
use App\Repositories\AccountRepository;
use App\Repositories\GradeLevelRepository;
use App\Repositories\UserRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;

class UsersController extends Controller
{
    /**
     * @var UserRepository
     * @var AccountRepository
     * @var GradeLevelRepository
     */
    protected $users;
    protected $accounts;
    protected $gradelevels;

    /**
     * @param UserRepository $users
     * @param AccountRepository $accounts
     * @param GradeLevelRepository $gradelevels
     */
    public function __construct(UserRepository $users,
                                AccountRepository $accounts,
                                GradeLevelRepository $gradelevels)
    {
        $this->users = $users;
        $this->accounts = $accounts;
        $this->gradelevels = $gradelevels;
    }

    /**
     * @param  \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function getDatatable(Request $request)
    {
        $aid = $request->input('aid', 0);
        return DataTables::of($this->users->getForDataTable($aid))
            ->escapeColumns(['first_name', 'last_name'])
            ->addColumn('actions', function ($user) {
                return $user->action_buttons;
            })
            ->editColumn('user_type', function ($user) {
                switch ($user->user_type) {
                    case 3:
                        return 'Student';
                        break;
                    case 4:
                        return 'Teacher';
                        break;
                    case 5:
                        return 'Staff';
                        break;
                    case 6:
                        return 'Parent';
                        break;
                    case 2:
                        return 'Admin';
                        break;
                    default:
                        return 'Undefined';
                        break;
                }
            })
            ->editColumn('allowed_to_order', function ($user) {
                return ($user->allowed_to_order) ? 'Yes' : 'No';
            })
            ->editColumn('grade_desc', function ($user) {
                if ($user->user_type != 3) {
                    return 'n/a';
                }
                return $user->grade_desc;
            })
            ->make(true);
    }

    /**
     * Display a listing of Users.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $firstItem = array('0' => '- Show All Users for All Accounts -');
        $accounts = $firstItem + $this->accounts->getForSelect(false);
        $aid = $request->input('aid', 0);

        return view('admin.users.index')
            ->withAccounts($accounts)
            ->withAccountid($aid);
    }

    /**
     * Show the form for creating new User.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     * @throws AuthorizationException
     */
    public function create(Request $request)
    {
        $aid = $request->input('aid', 0);
        $account = $this->accounts->getForSelectForAccount($aid);

        if (!$account)
            return redirect()->route('admin.users.index', ['aid' => 0])
                ->withFlashWarning('Unable to create a user for account ' . $aid);

        $gradelevels = $this->gradelevels->getForSelect();

        return view('admin.users.create')
            ->withAccount($account)
            ->withGradelevels($gradelevels)
            ->withAccountid($aid);
    }

    /**
     * Store a newly created User in storage.
     *
     * @param  StoreUsersRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUsersRequest $request)
    {
        $req = $request->only('account_id', 'last_name', 'first_name', 'allowed_to_order', 'user_type', 'grade_id', 'teacher_id');

        // currently only students can be assigned a grade level
        if ($req['user_type'] != 3) {
            $req['grade_id'] = 1;
        }

        try {
            $user = User::create($req);
        } catch (\Exception $e) {

            $msg = "Unable to create user '" . $req['last_name'] . ', ' . $req['first_name'] . "': ";
            if ($e->getCode() == 23000) {
                $msg .= 'the user already exists.';
            } else {
                $msg .= $e->getMessage();
            }

            Log::error($msg);
            return redirect()->route('admin.users.index', ['aid' => $req['account_id']])
                ->withFlashWarning($msg);
        }

        return redirect()->route('admin.users.index', ['aid' => $req['account_id']])
            ->withFlashSuccess("User '" . $req['last_name'] . ', ' . $req['first_name'] . "' created.");
    }


    /**
     * Show the form for editing a User.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     * @throws AuthorizationException
     */
    public function edit($id)
    {
        $user = User::find($id);
        if (!$user) {
            $msg = 'Unable to edit user with id ' . $id;
            Log::error($msg);
            return redirect()->route('admin.users.index')
                ->withFlashWarning($msg);
        }

        $account = $this->accounts->getForSelectForAccount($user->account_id);
        $gradelevels = $this->gradelevels->getForSelect(false);

        return view('admin.users.edit')
            ->withUser($user)
            ->withAccount($account)
            ->withGradelevels($gradelevels);
    }

    /**
     * Update User in storage.
     *
     * @param  UpdateUsersRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUsersRequest $request, $id)
    {
        $req = $request->only('last_name', 'first_name', 'allowed_to_order', 'user_type', 'grade_id', 'teacher_id');

        // currently only students can be assigned a grade level
        if ($req['user_type'] != 3) {
            $req['grade_id'] = 1;
        }

        $user = User::find($id);
        if (!$user) {
            $msg = 'Unable to update user with id ' . $id;
            Log::error($msg);
            return redirect()->route('admin.users.index')
                ->withFlashWarning($msg);
        }

        try {
            $user->update($req);
        } catch (\Exception $e) {

            $msg = "Unable to update user '" . $user->last_name . ', ' . $user->first_name . "': ";
            if ($e->getCode() == 23000) {
                $msg .= 'the user already exists.';
            } else {
                $msg .= $e->getMessage();
            }

            Log::error($msg);
            return redirect()->route('admin.users.index', ['aid' => $user->account_id])
                ->withFlashWarning($msg);
        }

        return redirect()->route('admin.users.index', ['aid' => $user->account_id])
            ->withFlashSuccess("User '" . $req['last_name'] . ', ' . $req['first_name'] . "' updated.");
    }

    /**
     * Remove a User from storage using AJAX. The client refreshes
     * itself, so no redirect. Flash a status message to the session.
     *
     * @param  int $id
     * @return mixed
     * @throws  AuthorizationException
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            $msg = 'Unable to delete user with id ' . $id;
            session()->flash('flash_warning', $msg);
            return;
        }

        try {
            $user->delete();
            session()->flash('flash_success', "User '" . $user->full_name . "' was deleted.");
        } catch (\Exception $e) {

            if ($e->getCode() == 23000) {
                $msg = "Unable to delete user' " . $user->full_name . "': related orders must be removed first.";
            } else {
                $msg = $e->getMessage();
            }

            Log::error($msg);
            session()->flash('flash_warning', $msg);
        }
    }
}
