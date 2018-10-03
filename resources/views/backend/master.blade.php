<!DOCTYPE html>
<html>
<head>
<base href="{{ asset('bower_components/layout/backend') }}/">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title') | {{ trans('remember.Shop') }}</title>
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/datepicker3.css" rel="stylesheet">
<link href="css/styles.css" rel="stylesheet">
<script type="text/javascript" src="ckeditor/ckeditor.js"></script>
<script src="js/lumino.glyphs.js"></script>
</head>
<body>
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">  
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="#">{{ trans('remember.Administrator') }}</a>
                <ul class="user-menu">
                    <li class="dropdown pull-right">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><svg class="glyph stroked male-user"><use xlink:href="#stroked-male-user"></use></svg>{{ Auth::user()->email }}<span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="{{ asset('logout') }}"><svg class="glyph stroked cancel"><use xlink:href="#stroked-cancel"></use></svg>{{ trans('remember.Logout') }}</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div><!-- /.container-fluid -->
    </nav>
    <div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar">
        <ul class="nav menu">
            <li role="presentation" class="divider"></li>
            <li class="active"><a href="index.html"><svg class="glyph stroked dashboard-dial"><use xlink:href="#stroked-dashboard-dial"></use></svg> {{ trans('remember.Home') }}</a></li>
            <li><a href="widgets.html"><svg class="glyph stroked calendar"><use xlink:href="#stroked-calendar"></use></svg> {{ trans('remember.Product') }}</a></li>
            <li><a href="{{ asset('admin/category') }}"><svg class="glyph stroked line-graph"><use xlink:href="#stroked-line-graph"></use></svg>{{ trans('remember.Category') }}</a></li>
            <li><a href="#"><svg class="glyph stroked bag"><use xlink:href="#stroked-bag"></use></svg>{{ trans('remember.Order') }}</a></li>
            <li role="presentation" class="divider"></li>
        </ul>
    </div><!--/.sidebar-->

    @yield('main');

    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/chart.min.js"></script>
    <script src="js/chart-data.js"></script>
    <script src="js/easypiechart.js"></script>
    <script src="js/easypiechart-data.js"></script>
    <script src="js/bootstrap-datepicker.js"></script>
    <script type="text/javascript" src="js/lich.js"></script>
</body>
</html>
