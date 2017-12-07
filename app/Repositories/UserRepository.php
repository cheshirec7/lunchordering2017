<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * Class UserRepository.
 */
class UserRepository extends BaseRepository
{
    /**
     * Associated Repository Model.
     */
    const MODEL = User::class;

    /**
     * @return mixed
     */
    public function getForDataTable($aid)
    {
        $q = $this->query()
            ->select('los_users.id', 'last_name', 'first_name', 'grade_id', 'grade_desc', 'teacher_id', 'user_type', 'allowed_to_order', DB::raw('count(o.user_id) as numorders'))
            ->join('los_gradelevels as gl', 'gl.id', '=', 'grade_id')
            ->leftJoin('los_orders as o', 'o.user_id', '=', 'los_users.id')
            ->where('los_users.account_id', '!=', 1)
            ->groupBy('los_users.id')
            ->groupBy('last_name')
            ->groupBy('first_name')
            ->groupBy('grade_id')
            ->groupBy('grade_desc')
            ->groupBy('teacher_id')
            ->groupBy('user_type')
            ->groupBy('allowed_to_order');

        if ($aid > 0) {
            $q->where('los_users.account_id', $aid);
        }

        return $q;
    }

    /**
     * @return mixed
     */
    public function getForOrders($account_id)
    {
        return $this->query()
            ->select('id', 'first_name', 'last_name', 'allowed_to_order', 'teacher_id', 'grade_id', 'user_type')
            ->where('account_id', $account_id)
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();
    }

    /**
     * @return mixed
     */
    public function getForSelect($account_id)
    {
        return $this->query()
            ->select('id', 'last_name', 'first_name')
            ->where('account_id', $account_id)
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get()
            ->pluck('full_name', 'id')
            ->all();
    }

}
