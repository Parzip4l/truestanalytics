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
            <li class="nav-item {{ active_class(['/']) }}">
                <a href="{{ url('/') }}" class="nav-link">
                <i class="link-icon" data-feather="box"></i>
                <span class="link-title">Dashboard</span>
                </a>
            </li>
            <li class="nav-item nav-category">Analytics Data</li>
            <li class="nav-item {{ active_class(['apps/chat']) }}">
                <a href="{{ url('/apps/chat') }}" class="nav-link">
                <i class="link-icon" data-feather="user"></i>
                <span class="link-title">Employee</span>
                </a>
            </li>
            <li class="nav-item {{ active_class(['apps/chat']) }}">
                <a href="{{ url('/apps/chat') }}" class="nav-link">
                <i class="link-icon" data-feather="user"></i>
                <span class="link-title">Attendance</span>
                </a>
            </li>
            <li class="nav-item {{ active_class(['apps/chat']) }}">
                <a href="{{ url('/apps/chat') }}" class="nav-link">
                <i class="link-icon" data-feather="user"></i>
                <span class="link-title">Payroll</span>
                </a>
            </li>
            <li class="nav-item {{ active_class(['apps/chat']) }}">
                <a href="{{ url('/apps/chat') }}" class="nav-link">
                <i class="link-icon" data-feather="user"></i>
                <span class="link-title">Reqruitment</span>
                </a>
            </li>
            <li class="nav-item {{ active_class(['apps/chat']) }}">
                <a href="{{ url('/apps/chat') }}" class="nav-link">
                <i class="link-icon" data-feather="user"></i>
                <span class="link-title">Rewards and Punisment</span>
                </a>
            </li>
        </ul>
    </div>
</nav>