warning: LF will be replaced by CRLF in app/Http/Controllers/OrdersController.php.
The file will have its original line endings in your working directory.
[1mdiff --git a/app/Http/Controllers/OrdersController.php b/app/Http/Controllers/OrdersController.php[m
[1mindex 50e6c24..3609921 100644[m
[1m--- a/app/Http/Controllers/OrdersController.php[m
[1m+++ b/app/Http/Controllers/OrdersController.php[m
[36m@@ -19,6 +19,7 @@[m [muse Illuminate\Http\Request;[m
 use Illuminate\Support\Facades\Auth;[m
 use Illuminate\Support\Facades\Gate;[m
 use Illuminate\Database\Eloquent\Collection;[m
[32m+[m[32muse Illuminate\Support\Facades\DB;[m
 [m
 class OrdersController extends Controller[m
 {[m
[36m@@ -79,18 +80,14 @@[m [mclass OrdersController extends Controller[m
         $lunchdates = $this->lunchdates->getForOrders($start_week, $end_week);[m
         $nles = $this->nles->getForOrders($start_week, $end_week);[m
         $orders = $this->orders->getForOrders($start_week, $end_week);[m
[31m-[m
         $today = Carbon::today();[m
[31m-[m
         $providers_row = '';[m
         $lunchdates_row = '';[m
[31m-//        $addl_row = '';[m
[31m-//        $has_addl = false;[m
         $providerIDs = array(0, 0, 0, 0, 0);[m
         $res = '';[m
         $loopDate = $start_week->copy();[m
 [m
[31m-        //build header, loop mon-fri[m
[32m+[m[32m        // build header, loop mon-fri[m
         for ($i = 0; $i < 5; $i++) {[m
             $todayclass = '';[m
             if ($loopDate->eq($today))[m
[36m@@ -101,22 +98,12 @@[m [mclass OrdersController extends Controller[m
                     $providers_row .= '<a target="_blank" href="' . $lunchdate->provider_url . '">';[m
                     $providers_row .= '<img src="/img/providers/' . $lunchdate->provider_image . '" alt="' . $lunchdate->provider_name . '" title="' . $lunchdate->provider_name . '"></a>';[m
                     $providerIDs[$i] = $lunchdate->prov_id;[m
[31m-[m
[31m-//                    if ($lunchdate->additional_text || $lunchdate->extended_care_text)[m
[31m-//                        $has_addl = true;[m
[31m-//                    $addl_row .= '<th' . $todayclass . '>';[m
[31m-//                    if ($lunchdate->additional_text)[m
[31m-//                        $addl_row .= '<div class="addl">' . $lunchdate->additional_text . '</div>';[m
[31m-//                    if ($lunchdate->extended_care_text)[m
[31m-//                        $addl_row .= '<div class="ext">' . $lunchdate->extended_care_text . '</div>';[m
[31m-//                    $addl_row .= '</th>';[m
                     break;[m
                 }[m
             }[m
 [m
             if ($providerIDs[$i] == 0) {[m
                 $providers_row .= '<img src="/img/providers/nolunches2017.png" alt="No Lunches Scheduled" title="No Lunches Scheduled">';[m
[31m-//                $addl_row .= '<th' . $todayclass . '></th>';[m
             }[m
 [m
             $providers_row .= '</th>';[m
[36m@@ -126,8 +113,6 @@[m [mclass OrdersController extends Controller[m
 [m
         $res .= '<tr class="providers"><th class="usercol"></th>' . $providers_row . '</tr>';[m
         $res .= '<tr class="lunchdates"><th class="usercol">Name</th>' . $lunchdates_row . '</tr>';[m
[31m-//        if ($has_addl)[m
[31m-//            $res .= '<tr class="addlmsg"><th class="usercol"></th>' . $addl_row . '</tr>';[m
 [m
         // build body[m
         try {[m
[36m@@ -139,10 +124,6 @@[m [mclass OrdersController extends Controller[m
         }[m
 [m
         foreach ($users as $user) {[m
[31m-//            dd($user);[m
[31m-//            if ($has_addl)[m
[31m-//                $res .= '<tr><td colspan="6" class="userrow dark"><i><small>Orders for</small></i> ' . $user->first_last . '</td></tr>';[m
[31m-//            else[m
             $res .= '<tr><td colspan="6" class="userrow">' . $user->first_last . '</td></tr>';[m
             $res .= '<tr>';[m
             $res .= '<td class="usercol">' . $user->first_name . '<br />' . $user->last_name . '</td>';[m
[36m@@ -166,7 +147,7 @@[m [mclass OrdersController extends Controller[m
      */[m
     private function getOrderCellHTML(int $aid, Carbon $cur_date, Carbon $today,[m
                                       Collection $lunchdates, Collection $nles, User $user,[m
[31m-                                      Collection $orders, Account $account, bool $animate) : string[m
[32m+[m[32m                                      Collection $orders, Account $account, bool $animate): string[m
     {[m
         $lunchdate_ptr = null;[m
         $nle_ptr = null;[m
[36m@@ -503,6 +484,8 @@[m [mclass OrdersController extends Controller[m
         }[m
 [m
         if ($mi_count > 0) {[m
[32m+[m[32m            DB::beginTransaction();[m
[32m+[m
             $order = Order::create([[m
                 'account_id' => $user->account_id,[m
                 'user_id' => $uid,[m
[36m@@ -526,6 +509,7 @@[m [mclass OrdersController extends Controller[m
             }[m
             $this->orderdetails->updateProvidersAndPrices($order->id);[m
             $this->orders->updateDescAndTotalPrice($order->id);[m
[32m+[m[32m            DB::commit();[m
         }[m
 [m
         $this->accounts->updateAccountAggregates($user->account_id);[m
