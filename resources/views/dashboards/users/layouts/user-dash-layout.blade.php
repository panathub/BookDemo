<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>User Dashboard</title>
	<base href="{{ \URL::to('/') }}">

	<link rel="icon" href="img/Noble.webp">
	<!-- Google Font: Source Sans Pro -->
	<link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400&display=swap" rel="stylesheet">

	<!-- Icons -->
	<link rel="stylesheet" href="assets/vendor/nucleo/css/nucleo.css" type="text/css">
	<link rel="stylesheet" href="assets/vendor/@fortawesome/fontawesome-free/css/all.min.css" type="text/css">

	<!-- Argon CSS -->
	<link rel="stylesheet" href="assets/css/argon.css?v=1.2.0" type="text/css">

	<!-- Datatable -->
	<link rel="stylesheet" href="DataTables/datatables.min.css" type="text/css">
	<link rel="stylesheet" href="sweetalert2/sweetalert2.min.css" type="text/css">

	<!-- Calendar -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css">

</head>

<body onload=display_ct5();>
	<div class="loader-wrapper">
		<span class="loader"><span class="loader-inner"></span></span>
	</div>
	<!-- Sidenav -->
	<nav class="sidenav navbar navbar-vertical  fixed-left  navbar-expand-xs navbar-light bg-white" id="sidenav-main">
		<div class="scroll-wrapper scrollbar-inner" style="position: relative;">
			<div class="scrollbar-inner scroll-content scroll-scrolly_visible" style="height: auto; margin-bottom: 0px; margin-right: 0px; max-height: 594px;">
				<!-- Brand -->
				<div class="sidenav-header  align-items-center">
					<a class="navbar-brand" href="javascript:void(0)">
						<img src="img/Noble.webp" class="navbar-brand-img" alt="...">
					</a>
				</div>
				<div class="navbar-inner">
					<!-- Collapse -->
					<div class="collapse navbar-collapse" id="sidenav-collapse-main">
						<!-- Nav items -->
						<ul class="navbar-nav">
							<li class="nav-item">
								<a href="{{ route('user.dashboard') }}" is class="nav-link {{ (request()->is('user/dashboard*')) ? 'active' : ''}}">
									<i class="ni ni-tv-2 text-primary"></i>
									<span class="nav-link-text">หน้าหลัก</span>
								</a>
							</li>
							<a class="nav-link collapsed" href="#navbar-examples" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-examples">

								<i class="ni ni-planet text-orange"></i>
								<span class="nav-link-text">เช็คห้องประชุม</span>

							</a>
							<div class="collapse" id="navbar-examples" style="">
								<ul class="nav nav-sm flex-column">
									<!-- php  -->
									<?php $partsR = DB::connection('mysql')->select('select * from rooms'); ?>
									<!-- end php  -->
									@foreach($partsR as $row)
									<li class="nav-item">
										<a href="/user/checkroom/{{$row->RoomID}}" is class="nav-link {{ (request()->is('/user/checkroom/$row->RoomID*')) ? 'active' : ''}}">{{$row->RoomName}}</a>
									</li>
									@endforeach

								</ul>
							</div>
							</li>

							<li class="nav-item">
								<a href="{{ route('user.booking') }}" is class="nav-link {{ (request()->is('user/booking*')) ? 'active' : ''}}">
									<i class="ni ni-pin-3 text-primary"></i>
									<span class="nav-link-text">จองห้องประชุม</span>
								</a>
							</li>

							</li>

							<!--li class="nav-item">
              <a href="{{ route('user.profile') }}" is class="nav-link {{ (request()->is('user/profile*')) ? 'active' : ''}}">
                <i class="ni ni-single-02 text-yellow"></i>
                <span class="nav-link-text">โปรไฟล์</span>
              </a>
            </li-->
							<li class="nav-item">
								<a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
									<i class="ni ni-bullet-list-67 text-default"></i>
									<span class="nav-link-text">ล็อคเอ้าท์</span>
								</a>
								<form action="{{ route('logout') }}" method="post" class="d-none" id="logout-form">@csrf</form>
							</li>
						</ul>
						<!-- Divider -->
						<hr class="my-3">
						<!-- Heading -->
						<h6 class="navbar-heading p-0 text-muted">
							<span class="docs-normal">Demo</span>
						</h6>
						<!-- Navigation -->
					</div>
					<span class="badge badge-dot badge-lg mr-4">
						<i class="badge-tonkotsu"></i>
						Tonkotsu
					</span>
					<span class="badge badge-dot badge-lg mr-4">
						<i class="badge-karamiso"></i>
						Karamiso
					</span>
					<span class="badge badge-dot badge-lg mr-4">
						<i class="badge-sukiyaki"></i>
						Sukiyaki
					</span>
					<span class="badge badge-dot badge-lg mr-4">
						<i class="badge-shabushabu"></i>
						Shabushabu
					</span>
					<span class="badge badge-dot badge-lg mr-4">
						<i class="badge-kinoko"></i>
						Kinoko
					</span>
				</div>
			</div>
	</nav>
	<!-- Main content -->
	<div class="main-content" id="panel">
		<!-- Topnav -->
		<nav class="navbar navbar-top navbar-expand navbar-dark" style="background: rgb(2,0,36);
background: linear-gradient(90deg, rgba(2,0,36,1) 0%, rgba(9,9,121,1) 35%, rgba(0,212,255,1) 100%);">
			<div class="container-fluid">
				<div class="collapse navbar-collapse" id="navbarSupportedContent">
					<!-- Search form -->
					<form class="navbar-search navbar-search-light form-inline mr-sm-3" id="navbar-search-main">
						<div class="form-group mb-0">
							<div class="input-group input-group-alternative input-group-merge">
								<div class="input-group-prepend">
									<!-- <span class="input-group-text"><i class="fas fa-search"></i></span> -->
								</div>
								<!--  <input class="form-control" placeholder="Search" type="text"> -->
							</div>
						</div>
						<button type="button" class="close" data-action="search-close" data-target="#navbar-search-main" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
					</form>
					<!-- Navbar links -->
					<ul class="navbar-nav align-items-center  ml-md-auto ">
						<li class="nav-item d-xl-none">
							<!-- Sidenav toggler -->
							<div class="pr-3 sidenav-toggler sidenav-toggler-dark" data-action="sidenav-pin" data-target="#sidenav-main">
								<div class="sidenav-toggler-inner">
									<i class="sidenav-toggler-line"></i>
									<i class="sidenav-toggler-line"></i>
									<i class="sidenav-toggler-line"></i>
								</div>
							</div>
						</li>
						<li class="nav-item d-sm-none">
							<a class="nav-link" href="#" data-action="search-show" data-target="#navbar-search-main">
								<i class="ni ni-zoom-split-in"></i>
							</a>
						</li>
					</ul>
					<ul class="navbar-nav align-items-center  ml-auto ml-md-0 ">
						<li class="nav-item dropdown">

							<div class="media align-items-center">
								<span class="mb-0 text-sm  font-weight-bold" style="color:white" id='ct5'>&nbsp;</span>
								<span class="mb-0 text-sm  font-weight-bold" style="color:white">&nbsp;Welcome user : {{ Auth::user()->name }}</span>
								<div class="media-body  ml-2  d-none d-lg-block">
									<span class="mb-0 text-sm  font-weight-bold" style="color:white"></span>
								</div>
							</div>

						</li>
					</ul>
				</div>
			</div>
		</nav>
		<!-- Header -->
		<!-- Header -->
		<div class="header pb-6" style="background: rgb(2,0,36);
background: linear-gradient(90deg, rgba(2,0,36,1) 0%, rgba(9,9,121,1) 35%, rgba(0,212,255,1) 100%);">
			@yield('content')
		</div>
		<!-- Page content -->
		<div class="container-fluid mt--6">
		</div>
	</div>

	<!-- Argon Scripts -->
	<!-- Core -->
	<script src="assets/vendor/jquery/dist/jquery.min.js"></script>
	<script src="assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
	<script src="assets/vendor/js-cookie/js.cookie.js"></script>
	<script src="assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js"></script>
	<script src="assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js"></script>
	<!-- Datatable JS -->
	<script src="DataTables/DataTables-1.11.3/js/jquery.dataTables.min.js"></script>
	<script src="sweetalert2/sweetalert2.min.js"></script>

	<!-- Argon JS -->
	<script src="assets/js/argon.js?v=1.2.0"></script>
	<!-- Calendar -->

	@yield('scripts')

	<script>
		function display_ct5() {
			var x = new Date();
			var x1 = " - " + x.getHours() + ":" + ('0' + x.getMinutes()).slice(-2) + ":" + ('0' + x.getSeconds()).slice(-2);
			var result = x.toLocaleDateString('th-TH', {
				year: 'numeric',
				month: 'long',
				day: 'numeric',
				weekday: 'long',
			})
			document.getElementById('ct5').innerHTML = result + x1;

			display_c5();
		}

		function display_c5() {
			var refresh = 1000; // Refresh rate in milli seconds
			mytime = setTimeout('display_ct5()', refresh)
		}
		display_c5()
	</script>

	<script>
		$(window).on("load", function() {
			$(".loader-wrapper").fadeOut("slow");
		});
	</script>

</body>

</html>