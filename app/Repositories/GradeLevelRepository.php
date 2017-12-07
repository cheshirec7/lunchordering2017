<?php

namespace App\Repositories;

use App\Models\GradeLevel;
use Illuminate\Support\Facades\DB;

/**
 * Class GradeLevelRepository.
 */
class GradeLevelRepository extends BaseRepository
{
    /**
     * Associated Repository Model.
     */
    const MODEL = GradeLevel::class;

    /**
     * @return mixed
     */
    public function getForDataTable()
    {
        return $this->query()
            ->select('los_gradelevels.id', 'grade', 'grade_desc', 'report_order', DB::raw('count(grade_id) as numusers'))
            ->leftJoin('los_users as u', 'u.grade_id', '=', 'los_gradelevels.id')
            ->where('los_gradelevels.id', '>', config('app.gradelevel_na_id'))
            ->groupBy('los_gradelevels.id')
            ->groupBy('grade')
            ->groupBy('grade_desc')
            ->groupBy('report_order');
    }

    /**
     * @param boolean $addSelectText
     * @return array
     */
    public function getForSelect($addSelectText = false)
    {
        $arr = $this->query()
            ->where('id', '>', config('app.gradelevel_na_id'))
            ->orderBy('report_order')
            ->get()
            ->pluck('grade_desc', 'id')
            ->all();

        if ($addSelectText)
            return array('' => '- Select -') + $arr;

        return $arr;
    }
}
