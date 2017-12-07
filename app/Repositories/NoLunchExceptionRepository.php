<?php

namespace App\Repositories;

use App\Models\NoLunchException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

/**
 * Class NoLunchExceptionRepository.
 */
class NoLunchExceptionRepository extends BaseRepository
{
    /**
     * Associated Repository Model.
     */
    const MODEL = NoLunchException::class;

    /**
     * @return mixed
     */
    public function getForDataTable($dts = 0)
    {
        $q = $this->query()
            ->select('los_nolunchexceptions.id', 'reason', 'description', 'grade_desc', 'teacher_id', 'exception_date', 'orders_placed')
            ->join('los_gradelevels as gl', 'gl.id', '=', 'grade_id')
            ->leftJoin('los_lunchdates', 'los_nolunchexceptions.exception_date', '=', 'los_lunchdates.provide_date');
//            ->orderBy('exception_date', 'desc');

        if ($dts == 0) {
            $q->where('exception_date', '>=', Carbon::today());
        }

        return $q;
    }

    /**
     * @return mixed
     */
    public function getForOrders(Carbon $start_date, Carbon $end_date)
    {
        return $this->query()
            ->select('id', 'exception_date', 'reason', 'description', 'grade_id', 'teacher_id')
            ->whereBetween('exception_date', array($start_date, $end_date))
//            ->where('grade_id', '>', config('app.gradelevel_na_id'))
            ->orderBy('exception_date')
            ->get();
    }

    /**
     * @return mixed
     */
    public function getForReport(Carbon $date)
    {
        return $this->query()
            ->select('exception_date', 'first_name', 'last_name', 'reason', 'description')
            ->join('los_users as u', 'u.grade_id', '=', 'los_nolunchexceptions.grade_id')
            ->join('los_accounts as a', 'a.id', '=', 'u.account_id')
            ->where('exception_date', ">=", $date)
            ->where('a.id', Auth::id())
            ->orderBy('exception_date')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();
    }

    /**
     * @return mixed
     */
    public function getGradeExceptionsForScheduleMonth(Carbon $start_date, Carbon $end_date)
    {
        return $this->query()
            ->select('exception_date', 'reason', 'description', 'grade_desc')
            ->join('los_gradelevels as gl', 'gl.id', '=', 'los_nolunchexceptions.grade_id')
            ->whereBetween('exception_date', array($start_date, $end_date))
            ->where('grade_id', '>', config('app.gradelevel_na_id'))
            ->orderBy('report_order')
            ->get();
    }

    /**
     * @return mixed
     */
    public function getTeacherExceptionsForScheduleMonth(Carbon $start_date, Carbon $end_date)
    {
        return $this->query()
            ->select('exception_date', 'reason', 'description', 'first_name', 'last_name')
            ->join('los_users as u', 'u.id', '=', 'los_nolunchexceptions.teacher_id')
            ->whereBetween('exception_date', array($start_date, $end_date))
            ->where('los_nolunchexceptions.teacher_id', '>', config('app.teacher_na_id'))
            ->orderBy('last_name')
            ->get();
    }
}
