<div class="sidebar">
    @auth
        <div class="user-panel mb-2">
            <div class="mt-3 mr-auto ml-auto text-center mb-3">
                <img src="{!! Auth::user()->picture !!}" class="img-circle" alt="User Image"/>
                <div class="mt-2 mr-3" style="font-size:12px;"><i
                            class="fa fa-circle text-success mr-2"></i>{!! Auth::user()->account_name !!}</div>
            </div>
        </div>
    @endauth
    <nav class="sidebar-nav">
        <ul class="nav">
            <li class="nav-item" style="margin-top: 5px;"><a
                        class="nav-link {!! active_class(Active::checkUriPattern('/')) !!}"
                        href="{!! url('/') !!}"><i class="fa fa-home"></i>Home</a></li>
            <li class="nav-item"><a class="nav-link {!! active_class(Active::checkUriPattern('orders*')) !!}"
                                    href="{!! route('orders.index') !!}"><i class="fa fa-cutlery"></i>Order Lunches</a>
            </li>

            <li class="nav-item"><a class="nav-link {!! active_class(Active::checkUriPattern('lunchreport*')) !!}"
                                    href="{!! route('lunchreport') !!}"><i class="fa fa-file-text"></i>Lunch Report</a>
            </li>

            <li class="nav-item"><a class="nav-link {!! active_class(Active::checkUriPattern('myaccount*')) !!}"
                                    href="{!! route('myaccount') !!}"><i class="fa fa-id-card"></i>My Account</a>
            </li>
            @show_contact
            <li class="nav-item"><a class="nav-link {!! active_class(Active::checkUriPattern('contact')) !!}"
                                    href="{!! route('contact') !!}"><i class="fa fa-envelope"></i>Contact Us</a>
            </li>
            @endshow_contact
            @viewmanage_backend
            <li class="nav-item nav-dropdown {{ active_class(Active::checkUriPattern('admin/*'), 'open') }}">
                <a class="nav-link nav-dropdown-toggle" href="#">
                    <i class="fa fa-user-circle"></i>Administrator
                </a>
                <ul class="nav-dropdown-items">
                    <li class="nav-item">
                        <a class="nav-link {!! active_class(Active::checkUriPattern('admin/gradelevels*')) !!}"
                           href="{!! route('admin.gradelevels.index') !!}"><i class="fa fa-circle-o"></i>Grade
                            Levels</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {!! active_class(Active::checkUriPattern('admin/accounts*')) !!}"
                           href="{!! route('admin.accounts.index') !!}"><i class="fa fa-circle-o"></i>Accounts</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {!! active_class(Active::checkUriPattern('admin/users*')) !!}"
                           href="{!! route('admin.users.index') !!}"><i class="fa fa-circle-o"></i>Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {!! active_class(Active::checkUriPattern('admin/providers*')) !!}"
                           href="{!! route('admin.providers.index') !!}"><i class="fa fa-circle-o"></i>Providers</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {!! active_class(Active::checkUriPattern('admin/menuitems*')) !!}"
                           href="{!! route('admin.menuitems.index') !!}"><i class="fa fa-circle-o"></i>Menu Items</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {!! active_class(Active::checkUriPattern('admin/nolunchexceptions*')) !!}"
                           href="{!! route('admin.nolunchexceptions.index') !!}"><i class="fa fa-circle-o"></i>No Lunch
                            Exceptions</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {!! active_class(Active::checkUriPattern('admin/lunchdates*')) !!}"
                           href="{!! route('admin.lunchdates.index') !!}"><i class="fa fa-circle-o"></i>Schedule Lunches</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {!! active_class(Active::checkUriPattern('admin/payments*')) !!}"
                           href="{!! route('admin.payments.index') !!}"><i class="fa fa-circle-o"></i>Receive
                            Payments</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {!! active_class(Active::checkUriPattern('admin/ordermaint*')) !!}"
                           href="{!! route('admin.ordermaint.index') !!}"><i class="fa fa-circle-o"></i>Order
                            Maintenance</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {!! active_class(Active::checkUriPattern('admin/reports*')) !!}"
                           href="{!! route('admin.reports.index') !!}"><i class="fa fa-circle-o"></i>Admin Reports</a>
                    </li>
                    {{--<li class="nav-item">--}}
                    {{--<a class="nav-link {!! active_class(Active::checkUriPattern('admin/utilities*')) !!}"--}}
                    {{--href="#"><i class="fa fa-circle-o"></i>Utilities</a>--}}
                    {{--</li>--}}
                </ul>
            </li>
            @endviewmanage_backend
            @auth
                <hr/>
                <li class="nav-item" style="padding-bottom:10px;">
                    <a href="{!! route('logout') !!}"
                       onclick="event.preventDefault();document.getElementById('logout-form').submit();"
                       class="nav-link"><i class="fa fa-sign-out"></i> Logout
                    </a>
                    {!! Form::open(['route' => 'logout', 'id' => 'logout-form']) !!}
                    {!! Form::close() !!}
                </li>
            @endauth
        </ul>
    </nav>
</div>
