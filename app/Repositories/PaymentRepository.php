<?php

namespace App\Repositories;

use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Class PaymentRepository.
 */
class PaymentRepository extends BaseRepository
{
    /**
     * Associated Repository Model.
     */
    const MODEL = Payment::class;

    /**
     * @return mixed
     */
    public function getForDataTable($aid)
    {
        return $this->query()
            ->select('los_payments.id', 'pay_method', 'credit_amt', 'fee', 'credit_date', 'credit_desc')
            ->where('account_id', $aid);
    }

    public function myAccountAggregate($account_id)
    {
        return $this->query()
            ->select(DB::raw('count(id) as payment_count'),
                DB::raw('coalesce(sum(credit_amt),0) as credit_amt'),
                DB::raw('coalesce(sum(fee),0) as fees'))
            ->where('account_id', $account_id)
            ->first();
    }

    public function getForMyAccountPaymentsDatatable()
    {
        return $this->query()
            ->select('id', 'pay_method', 'credit_desc', 'credit_date', 'credit_amt')
            ->where('account_id', Auth::id())
            ->orderBy('credit_date', 'asc');
    }

    public function adminAccountDetailReport($account_id)
    {
        return $this->query()
            ->where('account_id', $account_id)
            ->get();
    }

    /**
     * @return array
     */
    public function getForSelectDatesWithPayments()
    {
        return $this->query()
            ->select(DB::raw('distinct credit_date'))
            ->orderBy('credit_date', 'desc')
            ->get()
            ->pluck('credit_date')
            ->all();
    }

    public function adminPaymentsByDateReport($date)
    {
        return $this->query()
            ->select('account_name', 'credit_amt', 'credit_desc')
            ->join('los_accounts as a', 'a.id', '=', 'los_payments.account_id')
            ->where('credit_date', $date)
            ->orderBy('account_name')
            ->get();
    }
}
