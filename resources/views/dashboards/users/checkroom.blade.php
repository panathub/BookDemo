@extends('dashboards.users.layouts.user-dash-layout')
@section('title','Dashboard')

@section('content')

<div class="header pb-4" style="background: rgb(2,0,36);
background: linear-gradient(90deg, rgba(2,0,36,1) 0%, rgba(9,9,121,1) 35%, rgba(0,212,255,1) 100%);">
	<div class="container-fluid">
		<div class="header-body">
			<div class="row align-items-center py-4">
				<div class="col-lg-6 col-7">
					<h6 class="h2 text-white d-inline-block mb-0">เช็คห้องประชุม</h6>
					<nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
						<ol class="breadcrumb breadcrumb-links breadcrumb-dark">
							<li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">หน้าหลัก</a></li>
							<li class="breadcrumb-item active" aria-current="page">{{$view2->RoomName}}</li>
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
			<div class="card">
				<div class="card-header bg-transparent">
					<h2 class="fullcalendar-title mb-0 text-center"><i class="far fa-calendar-alt"></i>&nbsp;รายการจองห้องประชุม {{$view2->RoomName}} [จำกัดผู้เข้าประชุม {{$view2->RoomAmount}} คน]</h2>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table align-items-center">
							<tr>
								<th>ชื่อผู้จอง</th>
								<th>แผนก</th>
								<th>หัวข้อการประชุม</th>
								<th>วัน/เวลาเริ่ม - วัน/เวลาสิ้นสุด</th>
								<th>สถานะห้องประชุม</th>
								<th>สถานะการอนุมัติ</th>
							</tr>
							@foreach($view as $row)
							<tr>
								<td>{{$row->name}}</td>
								<td>{{isset($row->DepartmentName) ? $row->DepartmentName : '-'}}</td>
								<td>{{$row->BookingTitle}}</td>
								<td>{{\Carbon\Carbon::parse($row->Booking_start)->thaidate('j M y H:i')}} - {{\Carbon\Carbon::parse($row->Booking_end)->thaidate('j M y H:i')}}</td>
								<td>
									@if($row->RoomStatus == 0)
									<span class="badge badge-dot mr-4">
										<i class="bg-success"></i>
										ว่าง
									</span>
									@elseif($row->RoomStatus == 1)
									<span class="badge badge-dot mr-4">
										<i class="bg-warning"></i>
										รออนุมัติ
									</span>
									@elseif($row->RoomStatus == 2)
									<span class="badge badge-dot mr-4">
										<i class="bg-danger"></i>
										ไม่ว่าง
									</span>
									@elseif($row->RoomStatus == null)
									@endif
								</td>
								<td>
									@if($row->VerifyStatus == 0)
									<span class="badge badge-dot mr-4">
										<i class="bg-warning"></i>
										รอยืนยัน
									</span>
									@elseif($row->VerifyStatus == 1)
									<span class="badge badge-dot mr-4">
										<i class="bg-success"></i>
										อนุมัติแล้ว
									</span>
									@elseif($row->VerifyStatus == 2)
									<span class="badge badge-dot mr-4">
										<i class="bg-danger"></i>
										ไม่อนุมัติ
									</span>
									@elseif($row->VerifyStatus == null)
									@endif
								</td>
							</tr>

							@endforeach
						</table>
					</div>
				</div>




			</div>
		</div>
	</div>
</div>


@endsection



@section('scripts')

<script src="assets/vendor/moment/min/moment.min.js"></script>
<script src="assets/vendor/fullcalendar/dist/fullcalendar.min.js"></script>

<script>
	var d = new Date();
	document.getElementById("demo").innerHTML = d.toLocaleString('th-TH', {
		month: 'long',
	});
</script>


@endsection