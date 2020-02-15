<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="{{ config('app.locale') }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <meta name="csrf-token" content="{{ csrf_token() }}">
   <title>RAS</title>
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('css/bootstrapValidator.css') }}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}">
  <!-- PNotify -->
  <link href="{{asset('js/pnotify/pnotify.custom.min.css')}}" rel="stylesheet">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('css/AdminLTE.min.css') }}">
  <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">

  <link rel="stylesheet" href="{{ asset('css/skin-blue.min.css') }}">

@yield('css')

</head>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <!-- Main Header -->
  <header class="main-header">

    <!-- Logo -->
    <a href="{{ route('app.dashboard') }}" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>RA</b>S</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Ramos Academy</b></span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                <span id="NombrePeriodo" style="font-weight: bold;">
                  @if(\Session::has('periodo'))
                    {{ \Session::get('periodo') }}
                  @else
                    <?php
                      $defecto = App\Period::select('nombre')->where('id',
                        \Session::get('idPeriodo'))->first();
                    ?>
                      @if(is_null($defecto))
                        Registre periódos
                      @else
                        {{ $defecto->nombre }}
                      @endif
                  @endif
            </span>
                <span class="caret"></span>
              </a>
              <ul class="dropdown-menu" role="menu" id="Periodos">
                @foreach(App\Period::orderBy('año','DESC')->get() as $periodo)
                  <li>
                    <a href="{{ route('app.set.periodo',$periodo->id) }}">
                      {{ $periodo->nombre }}
                    </a>
                  </li>
                @endforeach
              </ul>
          </li>

          <!-- User Account Menu -->
          <li class="dropdown user user-menu">
            <!-- Menu Toggle Button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <!-- The user image in the navbar-->
              <img src="{{asset('/'.Auth::user()->foto) }}" class="user-image" alt=""/>
              <!-- hidden-xs hides the username on small devices so only the image appears. -->
              <span class="hidden-xs">{{ Auth::user()->nombre }} {{ Auth::user()->apellidos }}</span>
            </a>
            <ul class="dropdown-menu">
              <!-- The user image in the menu -->
              <li class="user-header">
              <img src="{{ asset('/'.Auth::user()->foto) }}" class="img-circle" alt=""/>

                <p>
                  {{ Auth::user()->nombre }} {{ Auth::user()->apellidos }}
                  <small>{{ Auth::user()->rol }}</small>
                  <small>Miembro desde {{ Auth::user()->created_at->format('d-m-Y') }}</small>
                </p>
              </li>

              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="{{ route('app.logout') }}" onclick="event.preventDefault();document.getElementById('salir-form').submit();" class="btn btn-default btn-flat btn-salir">Salir</a>
                  <form action="{{ route('app.logout') }}" method="POST" style="display: none;" id="salir-form">
                    {{ csrf_field() }}
                  </form>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

      <!-- Sidebar user panel (optional) -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="{{asset('/'.Auth::user()->foto)}}" style="height:37px !important;" class="img-circle" alt="">
        </div>
        <div class="pull-left info">
          <p>{{ Auth::user()->nombre }}</p>
          <a href="#"><i class="fa fa-circle text-success"></i> Conectado</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <ul class="sidebar-menu">
        <li class="header">MENU</li>
        <!-- Optionally, you can add icons to the links -->
        <li class="active"><a href="{{ route('app.dashboard') }}"><i class="fa fa-tachometer"></i> <span>Dashboard</span></a></li>
        <li class="treeview">
          <a href="#"><i class="fa fa-calendar-plus-o"></i> <span>Periódos</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{ route('app.period.page') }}">Configuración</a></li>
            <li><a href="{{ route('app.range.page') }}">Rangos</a></li>
          </ul>
        </li>
        <li><a href="{{ route('app.course.page') }}"><i class="fa fa-book"></i> <span>Cursos</span></a></li>
        <li><a href="{{ route('app.student.page') }}"><i class="fa fa-child"></i> <span>Alumnos</span></a></li>
        <li><a href="{{ route('app.class.page') }}"><i class="fa fa-navicon"></i> <span>Clases</span></a></li>
        <li class="treeview">
          <a href="#"><i class="fa fa-bullseye"></i> <span>Notas</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{ route('app.grade.page') }}">Configuración</a></li>
            <li><a href="{{ route('app.grade.ingreso.page') }}">
              Ingreso de notas</a></li>
          </ul>
        </li>
        <li><a href="{{ route('app.user.page') }}"><i class="fa fa-users"></i> <span>Usuarios</span></a></li>
        <li><a href="{{ route('app.report.page') }}"><i class="fa fa-table"></i> <span>Reportes</span></a></li>

      </ul>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        @yield('title')
        <small>@yield('description')</small>
      </h1>

    </section>

    <!-- Main content -->
    <section class="content">
      @yield('container')
      <!-- Your Page Content Here -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="pull-right hidden-xs">
      Version 1.1
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2019 <a href="#">devEineek</a>.</strong> All rights reserved.
  </footer>

  <!-- Control Sidebar -->

  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <!--<div class="control-sidebar-bg"></div>-->
</div>
<!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->
<script src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>
<!-- Bootstrap 3.3.6 -->
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<!--Bootstrap-->
<script src="{{ asset('plugins/validator/bootstrapValidator.min.js') }}"></script>
<!--Slim scroll-->
<script src="{{ asset('plugins/slimScroll/jquery.slimscroll.min.js') }}"></script>
<!--Fastclick-->
<script src="{{ asset('plugins/fastclick/fastclick.min.js') }}"></script>
<!-- Select2 -->
<script src="{{ asset('plugins/select2/select2.full.min.js') }}"></script>
<!-- PNotify -->
<script src="{{asset('js/pnotify/pnotify.custom.min.js')}}"></script>

<!--serialize-->
<script src="{{ asset('js/jquery.serializeObject.min.js') }}"></script>
<!-- DataTables -->
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
<!--Excel-->
<script src="{{ asset('js/jquery.btechco.excelexport.js') }}"></script>
<script src="{{ asset('js/jquery.base64.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('js/app.min.js') }}"></script>
<script type="text/javascript">
   $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

</script>
<script src="{{ asset('js/index.js') }}"></script>
@yield('scripts')

</body>
</html>
