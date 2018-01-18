<?php

namespace App\Repositories;

use App\Models\Account;
use Illuminate\Support\Facades\DB;

/**
 * Class AccountRepository.
 */
class AccountRepository extends BaseRepository
{
    /**
     * Associated Repository Model.
     */
    const MODEL = Account::class;

    public function findByPasswordResetToken($token)
    {
        foreach (DB::table(config('auth.passwords.accounts.table'))->get() as $row) {
            if (password_verify($token, $row->token)) {
                return $this->query()->where('email', $row->email)->first();
            }
        }
        return false;
    }

    /**
     * @return mixed
     */
    public function getForDataTable()
    {
        return $this->query()
            ->with('roles')
            ->with('users')
            ->select('id', 'account_name', 'email', 'active', 'allow_new_orders', 'confirmed_credits',
                'total_debits', 'total_orders')
            ->where('id', '>', config('app.default_account_id'));
    }

    /**
     * @param boolean $addSelectText
     * @return array
     */
    public function getForSelect($addSelectText = false)
    {
        $arr = $this->query()
            ->select('id', 'account_name')
            ->where('id', '>', config('app.default_account_id'))
            ->where('active', 1)
            ->orderBy('account_name')
            ->get()
            ->pluck('account_name', 'id')
            ->all();

        if ($addSelectText)
            return array('' => '- Select -') + $arr;

        return $arr;
    }

    /**
     * @return mixed
     */
    public function createCurBalAdjRecsForAllAccounts($req)
    {
        //        insert into los_payments (account_id, pay_method, credit_amt, fee, credit_date, credit_desc, created_at)
        //select id, 4, cast(total_debits as signed) + cast(fees as signed) - cast(confirmed_credits as signed), 0, '2017-12-4', 'test', now()
        //from los_accounts
        //where  cast(total_debits as signed) + cast(fees as signed) - cast(confirmed_credits as signed) > 0

        $insert = "INSERT INTO los_payments (account_id, pay_method, credit_amt, fee, credit_date, credit_desc, created_at)";
        $insert .= " select id, 4, cast(total_debits as signed) + cast(fees as signed) - cast(confirmed_credits as signed), 0, :date, :desc, now()";
        $insert .= " from los_accounts";
        $insert .= " where cast(total_debits as signed) + cast(fees as signed) - cast(confirmed_credits as signed) > 0";
        $affected = DB::affectingStatement($insert, array('date' => $req['credit_date']->toDateString(), 'desc' => $req['credit_desc']));

        $this->updateAccountAggregates(0);
        return $affected;
    }

    /**
     * @param integer $account_id
     * @return void
     */
    public function updateAccountAggregates($account_id = 0)
    {
        // update los_accounts
        // set confirmed_credits=0, confirmed_debits=0,total_debits=0,fees=0

        // update los_accounts a
        // set confirmed_credits = (select SUM(credit_amt) from los_payments p where p.account_id = 2)
        // where a.id=2

        if ($account_id == 0) {
            DB::statement('UPDATE los_accounts a SET confirmed_credits=(SELECT coalesce(SUM(credit_amt),0) FROM los_payments p WHERE a.id = p.account_id)');
            DB::statement('UPDATE los_accounts a SET fees=(SELECT coalesce(SUM(fee),0) FROM los_payments p WHERE a.id=p.account_id)');
            DB::statement('UPDATE los_accounts a SET total_debits=(SELECT coalesce(SUM(total_price),0) FROM los_orders o WHERE a.id = o.account_id AND o.status_code < 2)');
            DB::statement('UPDATE los_accounts a SET confirmed_debits=(SELECT coalesce(SUM(total_price),0) FROM los_orders o WHERE a.id = o.account_id AND o.status_code = 1)');
            DB::statement('UPDATE los_accounts a SET total_orders=(SELECT coalesce(SUM(qty),0) FROM los_orderdetails o WHERE a.id = o.account_id)');
        } else {
            $update = 'update los_accounts ';
            $update .= ' set confirmed_credits=(select coalesce(sum(credit_amt),0) from los_payments where account_id=:aid1)';
            $update .= ' where id=:aid2';
            DB::statement($update, array('aid1' => $account_id, 'aid2' => $account_id));

            $update = 'update los_accounts ';
            $update .= ' set fees=(select coalesce(sum(fee),0) from los_payments where account_id=:aid1)';
            $update .= ' where id=:aid2';
            DB::statement($update, array('aid1' => $account_id, 'aid2' => $account_id));

            $update = 'update los_accounts ';
            $update .= ' set total_debits=(select coalesce(sum(total_price),0) from los_orders where account_id=:aid1 AND status_code < 2)';
            $update .= ' where id=:aid2';
            DB::statement($update, array('aid1' => $account_id, 'aid2' => $account_id));

            $update = 'update los_accounts ';
            $update .= ' set confirmed_debits=(select coalesce(sum(total_price),0) from los_orders where account_id=:aid1 AND status_code = 1)';
            $update .= ' where id=:aid2';
            DB::statement($update, array('aid1' => $account_id, 'aid2' => $account_id));

            $update = 'update los_accounts ';
            $update .= ' set total_orders=(select coalesce(sum(qty),0) from los_orderdetails where account_id=:aid1)';
            $update .= ' where id=:aid2';
            DB::statement($update, array('aid1' => $account_id, 'aid2' => $account_id));
        }
    }

    /**
     * @return array
     */
    public function getForSelectForAccount($aid)
    {
        $account = $this->query()
            ->select('id', 'account_name', 'confirmed_credits', 'fees', 'total_debits')
            ->where('id', $aid)
            ->first();

        $a = array();
        $bal = $account->total_debits + $account->fees - $account->confirmed_credits;
        if ($bal > 0)
            $a[$account->id] = $account->account_name . '&nbsp;&nbsp;(DUE: ' . money_format('$%.2n', $bal / 100) . ')&nbsp;';
        else if ($bal == 0)
            $a[$account->id] = $account->account_name . '&nbsp;&nbsp;(Bal: $0.00)&nbsp;';
        else
            $a[$account->id] = $account->account_name . '&nbsp;&nbsp;(Credit: ' . money_format('$%.2n', -$bal / 100) . ')&nbsp;';

        return $a;
    }

    /**
     * @return array
     */
    public function getForSelectHasOrders()
    {
        $accounts = $this->query()
            ->select(['id', 'account_name', 'confirmed_credits', 'fees', 'total_debits'])
            ->where('id', '>', config('app.default_account_id'))
//            ->where('active', 1)
            ->where('total_orders', '>', 0)
            ->orderBy('account_name')
            ->get();

        $a1 = array('0' => '- Select -');
//        $a2 = array();
//        $a3 = array();
        foreach ($accounts as $account) {
            $bal = $account->total_debits + $account->fees - $account->confirmed_credits;
            if ($bal > 0)
                $a1[$account->id] = $account->account_name . '&nbsp;&nbsp;(DUE: ' . money_format('$%.2n', $bal / 100) . ')&nbsp;';
            else if ($bal == 0)
                $a1[$account->id] = $account->account_name . '&nbsp;&nbsp;(Bal: $0.00)&nbsp;';
            else
                $a1[$account->id] = $account->account_name . '&nbsp;&nbsp;(Credit: ' . money_format('$%.2n', -$bal / 100) . ')&nbsp;';
        }

        return $a1;// + $a2 + $a3;
    }

    /**
     * @param integer $account_id
     * @return integer
     */
    public function currentBalance($account_id)
    {
        $account = $this->query()
            ->select('total_debits', 'confirmed_credits', 'fees')
            ->where('id', $account_id)
            ->first();

        if ($account)
            //returned integer can be positive (an account credit) or negative (balance due)
            return round($account->confirmed_credits - $account->fees - $account->total_debits);
        else
            return 0;
    }

    public function adminAccountBalancesReport()
    {
        return $this->query()
            ->where('total_orders', '>', 0)
            ->orderBy('account_name', 'asc')
            ->get();
    }

    public function adminAccountDetailReport($account_id)
    {
        return $this->query()
            ->where('id', $account_id)
            ->first();
    }

}
