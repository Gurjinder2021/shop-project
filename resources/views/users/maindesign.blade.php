<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">
    <!-- Bootstrap CSS-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('/vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/vendor/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/font.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/style.default.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/custom.css') }}">
    <!-- Google fonts - Muli-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Muli:300,400,700">
    <!-- theme stylesheet-->

    <!-- Tweaks for older IEs--><!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
</head>

<body>
    <header class="header">
        <nav class="navbar navbar-expand-lg">

            <div class="container-fluid d-flex align-items-center justify-content-between">
                <div class="navbar-header">
                    <!-- Navbar Header -->
                    <a href="" class="navbar-brand">
                        <div class="brand-text brand-big visible text-uppercase">
                            <strong class="text-primary">{{ auth()->user()->name }}</strong>
                        </div>
                        <div class="brand-text brand-sm">
                            <strong class="text-primary">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </strong>
                        </div>
                    </a>
                    <!-- Sidebar Toggle Btn-->
                    <button class="sidebar-toggle"><i class="fa fa-long-arrow-left"></i></button>
                </div>
                <div class="right-menu list-inline no-margin-bottom">
                    <!-- Edit Profile -->
                    <div class="list-inline-item">
                        <a href="{{ route('profile.edit') }}" class="nav-link nav-action">
                            <i class="icon-user"></i> Edit Profile
                        </a>
                    </div>

                    <!-- Logout -->
                    <div class="list-inline-item logout">
                        <form method="POST" action="{{ route('logout') }}" id="logout-form">
                            @csrf
                            <a href="#" class="nav-link nav-action"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="icon-logout"></i> Log Out
                            </a>
                        </form>
                    </div>
                </div>

            </div>
        </nav>
    </header>
    <div class="d-flex align-items-stretch">
        <!-- Sidebar Navigation-->
        <nav id="sidebar">
            <!-- Sidebar Header-->
            <ul class="list-unstyled">
                <li class="{{ request()->is('userdashboard') ? 'active' : '' }}">
                    <a href="{{ url('/dashboard') }}">
                        <i class="fa-solid fa-house"></i> Home
                    </a>
                </li>

                <li class="{{ request()->is('user/shops') ? 'active' : '' }}">
                    <a href="{{ route('user.shops') }}">
                        <i class="fa-solid fa-store"></i> Stall List
                    </a>
                </li>

                <li class="{{ request()->is('user/shop-collection') ? 'active' : '' }}">
                    <a href="{{ route('daily.collection.create') }}">
                        <i class="fa-solid fa-coins"></i> Daily Collections
                    </a>
                </li>

                <li class="{{ request()->is('user/collectionsreport') ? 'active' : '' }}">
                    <a href="{{ route('user.shop.collections') }}">
                        <i class="fa-solid fa-file-lines"></i> Collections Report
                    </a>
                </li>

            </ul>
        </nav>
        <!-- Sidebar Navigation end-->
        <div class="page-content">
       <div class="page-header">
    <div class="container-fluid">
        @if(Route::currentRouteName() == 'user.dashboard')
            <h2 class="h5 no-margin-bottom">Dashboard</h2>
        @endif
    </div>
</div>
            @yield('content')
            <footer class="footer">
                <div class="footer__block block no-margin-bottom">
                    <div class="container-fluid text-center">
                        <!-- Please do not remove the backlink to us unless you support us at https://bootstrapious.com/donate. It is part of the license conditions. Thank you for understanding :)
                        <p class="no-margin-bottom">2018 &copy; Your company. Download From <a target="_blank"
                                href="https://templateshub.net">Templates Hub</a>.</p>-->
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <!-- JavaScript files-->
    <script src="{{ asset('/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('/vendor/popper.js/umd/popper.min.js') }}"></script>
    <script src="{{ asset('/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('/vendor/jquery.cookie/jquery.cookie.js') }}"></script>

    <script src="{{ asset('/vendor/jquery-validation/jquery.validate.min.js') }}"></script>
   <!--
    <script src="{{ asset('/vendor/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('/js/charts-home.js') }}"></script>
-->

    <script src="{{ asset('/js/front.js') }}"></script>
</body>

</html>
