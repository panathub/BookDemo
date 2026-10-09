<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta http-equiv="refresh" content="900">
	<title>{{ $room->RoomName }} - Noble-MeetingsRoom</title>
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
	<header style="background: {{ $room->theme }};">
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
								<a class="nav-link" style="color:#F5F5F5">ห้องประชุม</a>
								<div class="dropdown-content">
									@foreach ($rooms as $other)
									<a href="{{ route('room.show', $other) }}">{{ $other->RoomName }}</a>
									@endforeach
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
				<form action="{{ route('room.verify', $room) }}" method="POST" id="verify-booking-form">
					@csrf
					<div class="row justify-content-center">
						<h2>ห้องประชุม</h2>&nbsp;<h2>{{ $room->RoomName }}</h2>
					</div>
					<h6>สถานะ</h6>
					<h3 class="BookingStatus">-</h3>
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
							<h1 class="BookingTitle">-</h1>
						</div>
						<div>
							<h3>By</h3>
							<div class="row col">
								<h3 class="name">-</h3>
								&nbsp;&nbsp;&nbsp;
								<h3 class="DepartmentName">-</h3>
							</div>
						</div>
					</div>
				</div>
				<div class="container text-center">
					<button type="submit" class="btn btn-light px-5 py-2 primary-btn" id="verifyHomeBtn" hidden>ยืนยันการใช้ห้อง</button>
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
		function display_ct5() {
			var x = new Date();
			var x1 = " - " + ('0' + x.getHours()).slice(-2) + ":" + ('0' + x.getMinutes()).slice(-2) + ":" + ('0' + x.getSeconds()).slice(-2);
			document.getElementById('ct5').innerHTML = x1;

			display_c5();
		}

		function display_c5() {
			var refresh = 1000;
			mytime = setTimeout('display_ct5()', refresh);
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
			function renderUpcoming(details) {
				$('#verifyHomeBtn').prop('hidden', details === null);
				if (details === null) {
					$('.name, .DepartmentName, .BookingTitle, .Booking_start, .Booking_end, .BookingStatus').text('-');
					return;
				}
				$('.name').text(details.name);
				$('.DepartmentName').text(details.DepartmentName);
				$('.BookingTitle').text(details.BookingTitle);
				$('.Booking_start').text(moment(details.Booking_start).locale('th').format('DD-MM-YYYY เวลา LT'));
				$('.Booking_end').text(moment(details.Booking_end).locale('th').format('DD-MM-YYYY เวลา LT'));
				if (details.BookingStatus == 0) {
					$('.BookingStatus').html('<span class="text-white"><i class="fas fa-check text-white"></i> รอยืนยันการใช้ห้องประชุม</span>');
				} else {
					$('.BookingStatus').html('<span class="text-white"><i class="fas fa-clock text-white"></i> กำลังดำเนินการประชุม</span>');
				}
			}

			function loadUpcoming() {
				$.get('{{ route('room.upcoming', $room) }}', function(data) {
					renderUpcoming(data.details);
				}, 'json');
			}

			$('#verify-booking-form').on('submit', function(e) {
				e.preventDefault();
				$.post($(this).attr('action'), $(this).serialize(), function(data) {
					Swal.fire({ icon: 'success', title: data.msg, showConfirmButton: false, timer: 2000 });
					loadUpcoming();
				}, 'json');
			});

			function displayModal() {
				$.get('{{ route('notice.modal') }}', function(data) {
					if (data.details === null) {
						return;
					}
					$('.NotiModal').find('input[name="mid"]').val(data.details.id);
					$('.NotiModal').find('.text').text(data.details.text);
					$("#image").html(`<img src="{{ asset('storage/img/Image_Room') }}/${data.details.image}" width="55%" height="55%" class="img-center">`);
					$('.NotiModal').modal('show');
					setTimeout(function() {
						$('.NotiModal').modal('hide')
					}, 5000);
				}, 'json');
			}

			loadUpcoming();
			displayModal();
		});
	</script>

</body>

</html>
