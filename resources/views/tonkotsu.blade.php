<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta http-equiv="refresh" content="900">
	<title>Noble-MeetingsRoom</title>
	<base href="{{ \URL::to('/') }}">
	<link rel="icon" href="img/Noble.webp">
	<link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous" />
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
	<link rel="stylesheet" href="css_welcome/Style.css" />
	<link rel="stylesheet" href="css_welcome/mobile-style.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
	<link rel="stylesheet" href="sweetalert2/sweetalert2.min.css" type="text/css">
</head>

<body>
	<div class="loader-wrapper">
		<span class="loader">
			<span class="loader-inner"></span>
		</span>
	</div>
	<header style="background-color: #00DBDE;background-image: linear-gradient(90deg, #00DBDE 0%, #FC00FF 100%);">
		<div class="container-fluid p-3">
			<nav class="navbar navbar-expand-lg">
				<a class="navbar-brand" href="#">
					<i class="fas fa-book-reader fa-2x mx-3"></i>Noble-MeetingRoom</a>
				<div class="collapse navbar-collapse" id="navbarNav">
					<div class="mr-auto"></div>
					<ul class="navbar-nav text-center">
						<li class="nav-item active">
							<a class="nav-link" href="/">หน้าหลัก
								<span class="sr-only">(current)</span>
							</a>
						</li>
						<li class="nav-item dropdown">
							<div class="dropdown">
								<a class="nav-link" style="color: #F5F5F5">ห้องประชุม</a>
								<span class="sr-only">(current)</span>
								<div class="dropdown-content">
									<a href="{{ route('get.karamiso') }}">Karamiso</a>
									<a href="{{ route('get.sukiyaki') }}">Sukiyaki</a>
									<a href="{{ route('get.shabu') }}">ShabuShabu</a>
									<a href="{{ route('get.kinoko') }}">Kinoko</a>
								</div>
							</div>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="{{ route('login') }}">เข้าสู่ระบบ</a>
						</li>
					</ul>
				</div>
			</nav>
		</div>
		<div class="container text-center" style="padding-top:20px; padding-bottom:40px;">
			<div class="col-md-8 col-sm-12  text-white">
				<h5>{{\Carbon\Carbon::now()->thaidate('lที่ j F พ.ศ. Y')}}<span id='ct5'></span></h5>
				<form action="<?= route('verify.meeting') ?>" method="POST" enctype="multipart/form-data" id="verify-booking-form">
					@csrf
					<div class="row justify-content-center">
						<input type="hidden" name="bkid">
						<input type="hidden" name="rid">
						<h2>ห้องประชุม</h2>&nbsp;<h2>Tonkotsu</h2>
					</div>
					<h6>สถานะ</h6>
					<h3 class="BookingStatus"></h3>
			</div>
		</div>
	</header>
	<main>
		<section class="section-1">
			<div class="container text-center text-white">
				<div class="col-md-12 col-14">
					<div class="panel text-left">
						<h3 class="text-left" style="background-color: #0093E9; background-image: linear-gradient(160deg, #0093E9 0%, #80D0C7 100%);">
							Upcoming Meeting
						</h3>
						<div>
							<h1 class="Booking_start">-</h1>
							<h1 class="Booking_end">-</h1>
						</div>
						<h3>By</h3>
						<div class="row col">
							<h3 class="name">-</h3>
							&nbsp;&nbsp;&nbsp;
							<h3 class="DepartmentName"><span style="display:none">d</span></h3>
						</div>
					</div>
				</div>
			</div>
			</form>
			</div>
		</section>

	</main>

	@include('managemodal')

	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<script src="sweetalert2/sweetalert2.min.js"></script>
	<script src="assets/vendor/moment/min/moment-with-locales.min.js"></script>

	<script>
		function displayNowDate() {
			var x = new Date();
			var x1 = " - " + ('0' + x.getHours()).slice(-2) + ":" + ('0' + x.getMinutes()).slice(-2) + ":" + ('0' + x.getSeconds()).slice(-2);

			document.getElementById('ct5').innerHTML = x1;

			display_c5();
		}

		function display_c5() {
			var refresh = 1000; // Refresh rate in milli seconds
			mytime = setTimeout('displayNowDate()', refresh);
		}
		display_c5();
	</script>

	<script>
		$(window).on("load", function() {
			$(".loader-wrapper").fadeOut("slow");
		});
	</script>

	<script>
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$(function() {
			$(document).ready(function() {
				var booking_id = $(this).data('id');
				var today = new Date();
				var presentTime = today.getFullYear() + "-" + ('0' + (today.getMonth() + 1)).slice(-2) + "-" + ('0' + today.getDate()).slice(-2) + " " + ('0' + today.getHours()).slice(-2) + ":" + ('0' + today.getMinutes()).slice(-2);
				// var timetest = ('0' + today.getDate()).slice(-2) + "/" + ('0' + (today.getMonth()+1)).slice(-2) + "/" + today.getFullYear() + " "+ ('0' + today.getHours()).slice(-2) + ":" + ('0'+today.getMinutes()).slice(-2);
				// let timeAfter30Mins = new Date();
				times = new Date(today.setMinutes(today.getMinutes() + 30));
				var timeAfter30Mins = moment(times).locale('th').format('LT');
				//* -----------------------------------------------------------------------------
				$.get('<?= route("get.booking.tonkotsu") ?>', {
					booking_id: booking_id
				}, function(data) {
					$('input[name="bkid"]').val(data.details.BookingID);
					$('input[name="rid"]').val(data.details.RoomID);
					$('.name').text(data.details.name);
					$('.DepartmentName').text(data.details.DepartmentName);
					$('.Booking_start').text(moment(data.details.Booking_start).locale('th').format('DD-MM-YYYY เวลา LT'));
					$('.Booking_end').text(moment(data.details.Booking_end).locale('th').format('DD-MM-YYYY เวลา LT'));
					if (data.details.BookingStatus == 0) {
						$('.BookingStatus').html('<span class="text-white">' + '<i class="fas fa-check text-white"></i>' + ' รอยืนยันการใช้ห้องประชุม' + '</span>');
					} else {
						$('.BookingStatus').html('<span class="text-white">' + '<i class="fas fa-clock text-white"></i>' + ' กำลังดำเนินการประชุม' + '</span>');
					}
				});
				displayModal()
			});


			// function displayData() {
			// 	var booking_id = $(this).data('id');
			// 	//* -----------------------------------------------------------------------------
			// 	$.get('<?= route("get.booking.tonkotsu") ?>', {
			// 		booking_id: booking_id
			// 	}, (function(data) {

			// 		//alert(room_id);  

			// 		$('input[name="bkid"]').val(data.details.BookingID);
			// 		$('input[name="rid"]').val(data.details.RoomID);
			// 		$('.name').text(data.details.name);
			// 		$('.DepartmentName').text(data.details.DepartmentName);
			// 		$('.Booking_start').text(moment(data.details.Booking_start).locale('th').format('DD-MM-YYYY เวลา LT'));
			// 		$('.Booking_end').text(moment(data.details.Booking_end).locale('th').format('DD-MM-YYYY เวลา LT'));
			// 		if (data.details.BookingStatus == 0) {
			// 			$('.BookingStatus').html('<span class="text-white">' + '<i class="fas fa-check text-white"></i>' + ' รอยืนยันการใช้ห้องประชุม' + '</span>');
			// 		} else {
			// 			$('.BookingStatus').html('<span class="text-white">' + '<i class="fas fa-clock text-white"></i>' + ' กำลังดำเนินการประชุม' + '</span>');
			// 		}
			// 	}), 'json');
			// }

			function displayModal() {
				var m_id = $(this).data('id');
				//* -----------------------------------------------------------------------------
				$.get('<?= route("karamiso.noti.modal") ?>', {
					m_id: m_id
				}, function(data) {
					//alert(room_id);   
					$('.NotiModal').find('input[name="mid"]').val(data.details.id);
					$('.NotiModal').find('.text').text(data.details.text);
					$("#image").html(`<img src="img/Image_Room/${data.details.image}" width="55%" height="55%" class="img-center">`);
					$('.NotiModal').modal('show');
				}, 'json');
				setTimeout(function() {
					$('.NotiModal').modal('hide')
				}, 5000);

			};

		});
	</script>
</body>

</html>