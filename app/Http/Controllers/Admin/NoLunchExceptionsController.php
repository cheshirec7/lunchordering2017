<?php

namespace App\Http\Controllers\Admin;

use App\Models\NoLunchException;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Repositories\NoLunchExceptionRepository;
use App\Repositories\GradeLevelRepository;
use App\Repositories\LunchDateRepository;
use App\Repositories\OrderRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Log;

class NoLunchExceptionsController extends Controller
{
    /**
     * @var NoLunchExceptionRepository $nolunchexceptions
     * @var GradeLevelRepository $gradelevels
     * @var LunchDateRepository $lunchdates
     * @var OrderRepository $orders
     * @var string $userDateFormat
     */
    protected $nolunchexceptions;
    protected $gradelevels;
    protected $lunchdates;
    protected $orders;
    protected $userDateFormat = 'm/d/Y';

    /**
     * @param NoLunchExceptionRepository $nolunchexceptions
     * @param GradeLevelRepository $gradelevels
     * @param LunchDateRepository $lunchdates
     * @param OrderRepository $orders
     */
    public function __construct(NoLunchExceptionRepository $nolunchexceptions,
                                GradeLevelRepository $gradelevels,
                                LunchDateRepository $lunchdates,
                                OrderRepository $orders)
    {
        $this->nolunchexceptions = $nolunchexceptions;
        $this->gradelevels = $gradelevels;
        $this->lunchdates = $lunchdates;
        $this->orders = $orders;
    }

    /**
     * Display a listing of No Lunch Exceptions.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.nolunchexceptions.index');
    }

    /**
     * Display the No Lunch Exceptions datatable.
     */
    public function show($id)
    {
        return DataTables::of($this->nolunchexceptions->getForDataTable($id))
            ->escapeColumns(['reason', 'description'])
            ->addColumn('actions', function ($nle) {
                return $nle->action_buttons;
            })
            ->editColumn('exception_date', function ($nle) {
                return $nle->exception_date->format('Y-m-d');
            })
            ->make(true);
    }

    /**
     * Show the form for creating new No Lunch Exceptions.
     *
     * @return \Illuminate\Http\Response
     * @throws AuthorizationException
     */
    public function create()
    {
        $tomorrow = Carbon::tomorrow()
            ->format($this->userDateFormat);

        $dt = Carbon::today()
            ->addDay(10)
            ->startOfWeek()
            ->format($this->userDateFormat);

        $gradelevels = $this->gradelevels->getForSelect(true);

        return view('admin.nolunchexceptions.create')
            ->withTomorrow($tomorrow)
            ->withDate($dt)
            ->withGradelevels($gradelevels);
    }

    public function doRedirectBackWithInputDanger($msg)
    {
        return redirect()
            ->back()
            ->withInput()
            ->withFlashDanger($msg);
    }

    /**
     * Store a newly created Lunch Exception in storage.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $exception_date = Carbon::createFromFormat($this->userDateFormat, $request['exception_date'])->setTime(0, 0, 0);
        } catch (\Exception $err) {
            return $this->doRedirectBackWithInputDanger('Invalid exception date: ' . $err->getMessage());
        }

        $request['exception_date'] = $exception_date;

        $rules = [
            'reason' => 'required|max:30',
            'description' => 'required|max:50',
            'exception_date' => 'required|date|after:today',
            'grade_id' => 'required|integer|min:2', //1=undefined
        ];

        $messages = [
            'grade_id.min' => 'Please assign a grade to this exception.',
            'grade_id.required' => 'Please assign a grade to this exception.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages)->validate();

        if ($this->lunchdates->ordersPlaced($exception_date)) {
            return $this->doRedirectBackWithInputDanger('Unable to create exception: Orders have been placed for this date.');
        }

        $ordercount = $this->orders->numOrdersDateGrade($exception_date, $request['grade_id']);
        if ($ordercount > 0) {
            return $this->doRedirectBackWithInputDanger('Unable to create exception: (' . $ordercount . ') orders already exist for this date / grade.');
        }

        try {
            $nle = NoLunchException::create($request->all());
        } catch (\Exception $e) {
            if ($e->getCode() == 23000) {
                $msg = "Unable to create exception '" . $request['reason'] . "' on " . $exception_date->format($this->userDateFormat) . ": the exception already exists.";
            } else {
                $msg = $e->getMessage();
            }

            Log::error($msg);
            return $this->doRedirectBackWithInputDanger($msg);
        }

        return redirect()->route('admin.nolunchexceptions.index')
            ->withFlashSuccess("Created lunch exception '" . $nle->reason . "' for " . $nle->exception_date->format($this->userDateFormat) . ".");
    }

    /**
     * Show the form for editing a Lunch Exception.
     *
     * @param  int
     * @return \Illuminate\Http\Response
     * @throws AuthorizationException
     */
    public function edit($id)
    {
        $nle = NoLunchException::find($id);
        if (!$nle) {
            $msg = 'Unable to edit lunch exception with id ' . $id;
            Log::error($msg);
            return redirect()->route('admin.nolunchexceptions.index')
                ->withFlashWarning($msg);
        }

        $gradelevels = $this->gradelevels->getForSelect(true);
        $disabled = $this->lunchdates->ordersPlaced($nle->exception_date);
        $tomorrow = Carbon::tomorrow()->format($this->userDateFormat);

        return view('admin.nolunchexceptions.edit')
            ->withNle($nle)
            ->withGradelevels($gradelevels)
            ->withDisabled($disabled)
            ->withTomorrow($tomorrow);
    }

    /**
     * Update No Lunch Exception in storage.
     *
     * @param  Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $nle = NoLunchException::find($id);
        if (!$nle) {
            $msg = 'Unable to update a lunch exception with id ' . $id;
            Log::error($msg);
            return redirect()->route('admin.nolunchexceptions.index')
                ->withFlashWarning($msg);
        }

        if ($this->lunchdates->ordersPlaced($nle->exception_date)) {
            $req = $request->only('reason', 'description');

            $validator = Validator::make($req, [
                'reason' => 'required|max:30',
                'description' => 'required|max:50',
            ])->validate();

            $nle->update($req);
            return redirect()->route('admin.nolunchexceptions.index')
                ->withFlashSuccess("Lunch exception '" . $req['reason'] . "' on " . $nle->exception_date->format($this->userDateFormat) . " updated.");
        }


        try {
            $exception_date = Carbon::createFromFormat($this->userDateFormat, $request['exception_date'])->setTime(0, 0, 0);
        } catch (\Exception $err) {
            return $this->doRedirectBackWithInputDanger('Invalid exception date: ' . $err->getMessage());
        }

        if ($this->lunchdates->ordersPlaced($exception_date)) {
            return $this->doRedirectBackWithInputDanger('Unable to update exception: Orders have been placed for the selected date.');
        }

        $ordercount = $this->orders->numOrdersDateGrade($exception_date, $request['grade_id']);
        if ($ordercount > 0) {
            return $this->doRedirectBackWithInputDanger('Unable to update exception: (' . $ordercount . ') orders have been placed for this date / grade.');
        }

        $request['exception_date'] = $exception_date;

        try {
            $nle->update($request->only('reason', 'description', 'exception_date', 'grade_id', 'teacher_id'));
        } catch (\Exception $e) {

            if ($e->getCode() == 23000) {
                $msg = "Unable to update exception '" . $request['reason'] . "' on " . $request['exception_date']->format($this->userDateFormat) . ": the exception already exists.";
            } else {
                $msg = $e->getMessage();
            }

            Log::error($msg);
            return $this->doRedirectBackWithInputDanger($msg);
        }

        return redirect()->route('admin.nolunchexceptions.index')
            ->withFlashSuccess("Lunch exception '" . $request['reason'] . "' on " . $request['exception_date']->format($this->userDateFormat) . " updated.");
    }

    /**
     * Remove No Lunch Exception from storage. This is done using an AJAX request. The client UI is refreshed by
     * the client, so flash status messages to the session.
     *
     * @param  int $id
     * @return mixed
     * @throws  AuthorizationException
     */
    public function destroy($id)
    {
        $nle = NoLunchException::find($id);
        if (!$nle) {
            $msg = 'Unable to delete no lunch exception with id ' . $id;
            session()->flash('flash_warning', $msg);
            return;
        }

        if ($this->lunchdates->ordersPlaced($nle->exception_date)) {
            $msg = 'Unable to delete exception: Orders have been placed for ' . $nle->exception_date->format($this->userDateFormat) . '.';
            session()->flash('flash_warning', $msg);
            return;
        }

        $nle->delete();
        session()->flash('flash_success', "Lunch exception '" . $nle->reason . "' on " . $nle->exception_date->format($this->userDateFormat) . " was deleted.");
    }
}
