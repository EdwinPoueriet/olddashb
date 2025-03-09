
<!DOCTYPE html>
<html>
<head>
    @include('partials._head')
</head>

<body class="fixed-header fixed-leftmenu">
<div id="theme-wrapper">
    <header class="navbar" id="header-navbar">
        @include('partials._navbartop')
    </header>
    <div id="page-wrapper" class="container">
        <div class="row">
            @include('partials._sidebar')
            <div id="content-wrapper">
                @yield('content')
                <footer id="footer-bar" class="row">
                    <p id="footer-copyright"  class="col-xs-12">
                        ADGSystems - 2017
                    </p>
                </footer>
            </div>
        </div>
    </div>
</div>

<script src="/js/bootstrap.js"></script>
<script src="/js/jquery.maskedinput.min.js "></script>
<script src="/js/jquery.nanoscroller.min.js"></script>
<script src="/js/scripts.js"></script>
<script src="/js/select2.min.js"></script>

@yield('scripts')

<script src="/js/pace.min.js"></script>

</body>
</html>