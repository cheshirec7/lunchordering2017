<?php

namespace App\Http\Controllers\Admin;

use App\Models\GradeLevel;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Repositories\GradeLevelRepository;
use App\Http\Requests\Admin\StoreGradeLevelsRequest;
use App\Http\Requests\Admin\UpdateGradeLevelsRequest;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Log;

class GradeLevelsController extends Controller
{
    /**
     * @var GradeLevelRepository
     */
    protected $gradelevels;

    /**
     * @param GradeLevelRepository $gradelevels
     */
    public function __construct(GradeLevelRepository $gradelevels)
    {
        $this->gradelevels = $gradelevels;
    }

    /**
     * Display a listing of Grade Levels.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.gradelevels.index');
    }

    /**
     * Display the Grade Levels datatable.
     */
    public function show($id)
    {
        return DataTables::of($this->gradelevels->getForDataTable())
            ->escapeColumns(['grade', 'grade_desc'])
            ->addColumn('actions', function ($gradelevel) {
                return $gradelevel->action_buttons;
            })
            ->make(true);
    }

    /**
     * Show the form for creating new Grade Level.
     *
     * @return \Illuminate\Http\Response
     * @throws AuthorizationException
     */
    public function create()
    {
        return view('admin.gradelevels.create');
    }

    /**
     * Store a newly created Grade Level in storage.
     *
     * @param  StoreGradeLevelsRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreGradeLevelsRequest $request)
    {
        $req = $request->only('grade', 'grade_desc', 'report_order');
        $gradelevel = GradeLevel::create($req);
        return redirect()->route('admin.gradelevels.index')
            ->withFlashSuccess("Grade Level '" . $req['grade_desc'] . "' created.");
    }

    /**
     * Show the form for editing a Grade Level.
     *
     * @param  int
     * @return \Illuminate\Http\Response
     * @throws AuthorizationException
     */
    public function edit($id)
    {
        $gradelevel = GradeLevel::find($id);
        if (!$gradelevel) {
            $msg = 'Unable to edit grade level with id ' . $id;
            Log::error($msg);
            return redirect()->route('admin.gradelevels.index')
                ->withFlashWarning($msg);
        }

        return view('admin.gradelevels.edit')
            ->withGradelevel($gradelevel);
    }

    /**
     * Update Grade Level in storage.
     *
     * @param  UpdateGradeLevelsRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateGradeLevelsRequest $request, $id)
    {
        $req = $request->only('grade', 'grade_desc', 'report_order');

        $gradelevel = GradeLevel::find($id);
        if (!$gradelevel) {
            $msg = 'Unable to update grade level with id ' . $id;
            Log::error($msg);
            return redirect()->route('admin.gradelevels.index')
                ->withFlashWarning($msg);
        }

        $gradelevel->update($req);

        return redirect()->route('admin.gradelevels.index')
            ->withFlashSuccess("Grade Level '" . $req['grade_desc'] . "' updated.");
    }

    /**
     * Remove a Grade Level from storage using AJAX. The client refreshes
     * itself, so no redirect. Flash a status message to the session.
     *
     * @param  int $id
     * @return mixed
     * @throws  AuthorizationException
     */
    public function destroy($id)
    {
        $gradelevel = GradeLevel::find($id);
        if (!$gradelevel) {
            $msg = 'Unable to delete grade level with id ' . $id;
            session()->flash('flash_warning', $msg);
            return;
        }

        try {
            $gradelevel->delete();
            session()->flash('flash_success', "Grade level '" . $gradelevel->grade_desc . "' was deleted.");
        } catch (\Exception $e) {

            if ($e->getCode() == 23000) {
                $msg = "Unable to delete grade level '" . $gradelevel->grade_desc . "'': users are assigned to this grade.";
            } else {
                $msg = $e->getMessage();
            }

            Log::error($msg);
            session()->flash('flash_warning', $msg);
        }
    }
}
