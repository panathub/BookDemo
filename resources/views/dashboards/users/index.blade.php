@extends('dashboards.users.layouts.user-dash-layout')
@section('title','Dashboard')

@section('content')
<!-- php  -->
<?php $partsR = DB::connection('mysql')->select('select * from rooms'); ?>
<!-- end php  -->
<div class="header pb-4" style="background: rgb(2,0,36);
background: linear-gradient(90deg, rgba(2,0,36,1) 0%, rgba(9,9,121,1) 35%, rgba(0,212,255,1) 100%);">
	<div class="container-fluid">
		<div class="header-body">
			<div class="row align-items-center py-4">
				<div class="col-lg-6 col-7">
					<h6 class="h2 text-white d-inline-block mb-0">ปฏิทินห้องประชุม</h6>
					<nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
						<ol class="breadcrumb breadcrumb-links breadcrumb-dark">
							<li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">หน้าหลัก</a></li>
							<li class="breadcrumb-item active" aria-current="page">ปฏิทินการจองห้องประชุม</li>
						</ol>
					</nav>
				</div>
			</div>
		</div>
	</div>
</div>
</div>

<div class="container-fluid mt--6">
	<div class="row justify-content-center">
		<div class="col">
			<div class="card card-calendar">
				<div class="card-header">
					<h5 class="h3 mb-0">ปฏิทินห้องประชุม</h5>
				</div>
				<div class="card-body p-0">
					<div id='calendar'></div>
				</div>
			</div>
			<div class="card">
				<div class="card-header">
					<div class="row align-items-center">
						<div class="col-8 mb-0">
							<h3>รายการจองใช้ห้องประจำเดือน {{\Carbon\Carbon::now()->thaidate('F')}}</h3>
						</div>
						<div class="col-4 text-right">
							<select data-column="0" class="form-control filter-select">
								<option value="">Select Room</option>
								@foreach($partsR as $row)
								<option value="{{$row->RoomName}}">{{$row->RoomName}}</option>
								@endforeach
							</select>
						</div>
					</div>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-condensed align-items-center" id="bookings-table">
							<thead>
								<tr>
									<th>ห้อง</th>
									<th>ผู้จอง</th>
									<th>เริ่มเวลา</th>
									<th>สิ้นสุดเวลา</th>
									<th>สถานะ</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-------------------------------Modal Info ------------------------------------------>
<div class="modal fade infoBooking" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header pb-0">
				<h5 class="modal-title" id="exampleModalLabel">รายละเอียดห้องประชุม</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">

				<input type="hidden" name="bkid">

				<div class="row">
					<div class="col-6 col-md-5">
						<h3 class="text-center">หน่วยงานที่จัดประชุม</h3>
					</div>
					<div class="col-6 col-md-6">
						<span class="text-center DepartmentName"></span>
					</div>
				</div>
				<p></p>
				<div class="row">
					<div class="col-6 col-md-5">
						<h3 class="text-center">ประชุมเรื่อง</h3>
					</div>
					<div class="col-6 ">
						<span class="text-center BookingTitle"></span>
					</div>
				</div>
				<p></p>
				<div class="row">
					<div class="col-6 col-md-5">
						<h3 class="text-center">จำนวนผู้เข้าร่วมประชุม</h3>
					</div>
					<div class="col-6 ">
						<span class="text-center BookingAmount"></span>
					</div>
				</div>
				<p></p>

				<div class="row">
					<div class="col-6 col-md-5">
						<h3 class="text-center">ห้องที่ใช้ประชุม</h3>
					</div>
					<div class="col-6">
						<span class="text-center RoomName"></span>
					</div>
				</div>
				<p></p>
				<div class="row">
					<div class="col-6 col-md-5">
						<h3 class="text-center">เริ่มเวลา</h3>
					</div>
					<div class="col-6">
						<span class="text-center Booking_start"></span>
					</div>
				</div>
				<p></p>
				<div class="row">
					<div class="col-6 col-md-5">
						<h3 class="text-center">สิ้นสุดเวลา</h3>
					</div>
					<div class="col-6">
						<span class="text-center Booking_end"></span>
					</div>
				</div>
				<p></p>
				<div class="row">
					<div class="col-6 col-md-5">
						<h3 class="text-center">รายละเอียดการประชุม</h3>
					</div>
					<div class="col-6 ">
						<span class="text-center BookingDetail"></span>
					</div>
				</div>
				<p></p>
				<div class="row">
					<div class="col-6 col-md-5">
						<h3 class="text-center">สถานะ</h3>
					</div>
					<div class="col-6 ">
						<span class="text-center VerifyStatus"></span>
					</div>
				</div>
				<div class="mt-2" id="Image_Room"></div>
			</div>
		</div>
	</div>
</div>

@include('managemodal')

@endsection



@section('scripts')

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>
<script src="assets/vendor/moment/min/moment-with-locales.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/locales-all.min.js"></script>


@if (Session::has('success'))
<script>
	var m_id = $(this).data('id');
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
</script>
@endif

<script>
	$(document).ready(function() {
		var calendarEl = document.getElementById('calendar');
		var text = "Hello world";
		var calendar = new FullCalendar.Calendar(calendarEl, {
			locale: 'th',
			allDaySlot: true,
			fixedWeekCount: false,
			headerToolbar: {
				left: 'prev,next today',
				center: 'title',
				right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
			},
			eventTimeFormat: { // like '14:30:00'
				hour: '2-digit',
				minute: '2-digit',
			},
			timeZone: 'Asia/Bangkok',
			dayMaxEvents: true,
			eventMaxStack: true,
			events: "{{route('index')}}",
			eventClick: function() {
				$('html, body').animate({
					scrollTop: $("#bookings-table").offset().top
				}, 750);

			},
		});

		calendar.render();
	});
</script>

<script>
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

	$(function() {

		//GET ALL BOOKIng
		var table = $('#bookings-table').DataTable({
			processing: true,
			info: true,
			ajax: "{{ route('get.booking.index') }}",
			"pageLength": 5,
			"aLengthMenu": [
				[5, 10, 25, 50, -1],
				[5, 10, 25, 50, "All"]
			],
			columns: [{
					data: 'RoomName',
					render: function(data, type) {
						switch (data) {
							case 'Karamiso':
								return '<span class="badge badge-md badge-karamiso">Karamiso</span>';
								break;
							case 'Sukiyaki':
								return '<span class="badge badge-md badge-sukiyaki">Sukiyaki</span>';
								break;
							case 'Tonkotsu':
								return '<span class="badge badge-md badge-tonkotsu">Tonkotsu</span>';
								break;
							case 'Kinoko':
								return '<span class="badge badge-md badge-kinoko">Kinoko</span>';
								break;
							case 'Shabushabu':
								return '<span class="badge badge-md badge-shabushabu">Shabushabu</span>';
								break;
						}
					}
				},
				{
					data: 'RoomName',
					render: function(data, type, row) {
						if (row.DepartmentName == null) {
							return row.name
						} else {
							return row.name + '<p></p><b>แผนก </b>' + row.DepartmentName
						}
					}
				},
				{
					data: "Booking_start",
					render: function(data) {
						return moment(data).locale('th').format('DD MMM YY, HH:mm')
					}
				},
				{
					data: 'Booking_end',
					render: function(data) {
						return moment(data).locale('th').format('DD MMM YY, HH:mm')
					}
				},
				{
					data: 'VerifyStatus',
					render: function(data) {
						if (data == 1) {
							return '<span class="badge badge-md badge-success">อนุมัติแล้ว</span>';
						} else if (data == 2) {
							return '<span class="badge badge-md badge-danger">ไม่อนุมัติ</span>';
						} else
							return '<span class="badge badge-md badge-warning">รอยืนยัน</span>';

					}
				},
				{
					data: 'actions',
					name: 'actions'
				},

			],


		});

		$('.filter-select').change(function() {
			table.column($(this).data('column'))
				.search($(this).val())
				.draw();
		});

		$(document).on('click', '#infoBookingBtn', function() {
			var booking_id = $(this).data('id');
			$.get('<?= route("get.booking.index.details") ?>', {
				booking_id: booking_id
			}, function(data) {

				$('.infoBooking').find('input[name="bkid"]').val(data.details.BookingID);
				$('.infoBooking').find('.DepartmentName').text(data.details.DepartmentName == null ? '-' : data.details.DepartmentName);
				$('.infoBooking').find('.BookingTitle').text(data.details.BookingTitle);
				$('.infoBooking').find('.BookingAmount').text(data.details.BookingAmount + ' คน');
				$('.infoBooking').find('.RoomName').text(data.details.RoomName);
				$('.infoBooking').find('.Booking_start').text(moment(data.details.Booking_start).locale('th').format('Do MMM YY, HH:mm'));
				$('.infoBooking').find('.Booking_end').text(moment(data.details.Booking_end).locale('th').format('Do MMM YY, HH:mm'));
				$('.infoBooking').find('.BookingDetail').text(data.details.BookingDetail == null ? '-' : data.details.BookingDetail);
				if (data.details.VerifyStatus == 1) {
					$('.infoBooking').find('.VerifyStatus').html('<span class="badge badge-lg badge-success">อนุมัติแล้ว</span>');
				} else if (data.details.VerifyStatus == 2) {
					$('.infoBooking').find('.VerifyStatus').html('<span class="badge badge-lg badge-danger">ไม่อนุมัติ</span>');
				} else
					$('.infoBooking').find('.VerifyStatus').html('<span class="badge badge-lg badge-warning">รอยืนยัน</span>');
				$("#Image_Room").html(`<img src="img/Image_Room/${data.details.Image_room}" width="300" class="img-fluid img-center">`);
				$('.infoBooking').modal('show');
				//console.log(data.details.DepartmentName);   
			}, 'json');
		});

	});
</script>

@endsection