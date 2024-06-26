<nav class="sidebar">
    <div class="sidebar-header">
        <a href="#" class="sidebar-brand" style="width:90%">
        <img src="{{ asset('/logo.png') }}" alt="" style="max-width:65%">
        </a>
        <div class="sidebar-toggler not-active">
        <span></span>
        <span></span>
        <span></span>
        </div>
    </div>
    <div class="sidebar-body">
        <ul class="nav">
            <li class="nav-item nav-category">Main</li>
            <li class="nav-item {{ active_class(['dashboard']) }}">
                <a href="{{ url('dashboard') }}" class="nav-link">
                <i class="link-icon" data-feather="box"></i>
                <span class="link-title">Dashboard</span>
                </a>
            </li>
            <li class="nav-item nav-category">Analytics Data</li>
            <li class="nav-item {{ active_class(['employee/*']) }}">
                <a class="nav-link" data-bs-toggle="collapse" href="#employee" role="button" aria-expanded="{{ is_active_route(['employee/*']) }}" aria-controls="employee">
                    <i class="link-icon" data-feather="user"></i>
                    <span class="link-title">Employee</span>
                    <i class="link-arrow" data-feather="chevron-down"></i>
                </a>
                <div class="collapse {{ show_class(['employee/*']) }}" id="employee">
                    <ul class="nav sub-menu">
                        <li class="nav-item">
                            <a href="{{ route('demographic.employee') }}" class="nav-link {{ active_class(['employee/demographic']) }}">Demographics</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('#') }}" class="nav-link {{ active_class(['advanced-ui/sweet-alert']) }}">Employee Performance</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('#') }}" class="nav-link {{ active_class(['advanced-ui/owl-carousel']) }}">Training and Development</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('#') }}" class="nav-link {{ active_class(['advanced-ui/sortablejs']) }}">Turnover</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item {{ active_class(['attendance/analytic-data']) }}">
                <a class="nav-link" data-bs-toggle="collapse" href="#attendance" role="button" aria-expanded="{{ is_active_route(['attendance/*']) }}" aria-controls="attendance">
                    <i class="link-icon" data-feather="user"></i>
                    <span class="link-title">Attendance</span>
                    <i class="link-arrow" data-feather="chevron-down"></i>
                </a>
                <div class="collapse {{ show_class(['attendance/*']) }}" id="attendance">
                    <ul class="nav sub-menu">
                        <li class="nav-item">
                            <a href="{{ url('attendance/analytic-data') }}" class="nav-link {{ active_class(['attendance/analytic-data']) }}">Attendance Master</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('attendance/time-off') }}" class="nav-link {{ active_class(['attendance/time-off']) }}">Time Off</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item {{ active_class(['payrol/analytic-data']) }}">
                <a href="{{ url('payrol/analytic-data') }}" class="nav-link">
                <i class="link-icon" data-feather="dollar-sign"></i>
                <span class="link-title">Payroll</span>
                </a>
            </li>
            <li class="nav-item {{ active_class(['']) }}">
                <a href="{{ url('') }}" class="nav-link">
                <i class="link-icon" data-feather="credit-card"></i>
                <span class="link-title">Koperasi</span>
                </a>
            </li>
            <li class="nav-item {{ active_class(['']) }}">
                <a href="{{ url('#') }}" class="nav-link">
                <i class="link-icon" data-feather="user-plus"></i>
                <span class="link-title">Reqruitment</span>
                </a>
            </li>
            <li class="nav-item {{ active_class(['']) }}">
                <a href="{{ url('#') }}" class="nav-link">
                <i class="link-icon" data-feather="alert-triangle"></i>
                <span class="link-title">Work accident incidents</span>
                </a>
            </li>
            <li class="nav-item {{ active_class(['']) }}">
                <a href="{{ url('#') }}" class="nav-link">
                <i class="link-icon" data-feather="user-check"></i>
                <span class="link-title">Feedback</span>
                </a>
            </li>
        </ul>
    </div>
</nav>