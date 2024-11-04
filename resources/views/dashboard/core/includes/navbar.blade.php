<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        </li>
    </ul>
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Messages Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="fas fa-globe"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                    <a rel="alternate" hreflang="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}" class="dropdown-item">
                        <div class="media">
                            <div class="media-body">
                                <h3 class="dropdown-item-title">
                                    {{ $properties['native'] }}
                                </h3>
                            </div>
                        </div>
                    </a>
                    <div class="dropdown-divider"></div>
                @endforeach
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('auth.logout') }}">
                <i class="fas fa-arrow-circle-right"></i>
                <span>{{ __('dashboard.Logout') }}</span>
            </a>
        </li>
    </ul>
</nav>
<!-- /.navbar -->
