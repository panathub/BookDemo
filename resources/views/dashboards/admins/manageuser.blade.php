@extends('dashboards.admins.layouts.admin-dash-layout')
@section('title','Dashboard')

@section('content')

<div class="header bg-primary pb-4">
	<div class="container-fluid">
		<div class="header-body">
			<div class="row align-items-center py-4">
				<div class="col-lg-6 col-7">
					<h6 class="h2 text-white d-inline-block mb-0">ผู้ใช้งาน</h6>
					<nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
						<ol class="breadcrumb breadcrumb-links breadcrumb-dark">
							<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">หน้าหลัก</a></li>
							<li class="breadcrumb-item active" aria-current="page">ผู้ใช้งาน</li>
						</ol>
					</nav>
				</div>
			</div>
		</div>
	</div>
</div>
</div>

<div class="container-fluid mt--6">
	<div class="row">
		<div class="col-md-8">
			<div class="card">
				<div class="card-header">ตารางแผนก</div>
				<div class="card-body">

					<table class="table table-hover table-condensed" id="departments-table">
						<thead>
							<th>#</th>
							<th>ชื่อแผนก</th>
							<th>Action</th>
						</thead>
						<tbody></tbody>
					</table>

				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="card">
				<div class="card-header">เพิ่มแผนก</div>
				<div class="card-body">
					<form action="{{ route('add.department') }}" method="POST" enctype="multipart/form-data" id="addDepartment">
						@csrf
						<div class="form-group">
							<label for="">ชื่อแผนก</label>
							<input type="text" class="form-control" name="DName" placeholder="Enter Department name">
							<span class="text-danger error-text DName_error"></span>
						</div>
						<div class="form-group">
							<button type="submit" class="btn btn-block btn-success">บันทึก</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<!--Modal Edit -->
	<div class="modal fade editDepartment" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">แก้ไขแผนก</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form action="<?= route('update.department.details') ?>" method="POST" id="update-department-form">
						@csrf
						<input type="hidden" name="did">
						<div class="form-group">
							<label for="">ชื่อแผนก</label>
							<input type="text" class="form-control" name="DName" placeholder="Enter Department name">
							<span class="text-danger error-text DName_error"></span>
						</div>
						<div class="form-group">
							<button type="submit" class="btn btn-block btn-success">บันทึกการเปลี่ยนแปลง</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<!---------------------------------------------------------- USER ---------------------------------------------------------------->

	<div class="row justify-content-center">
		<div class="col">
			<div class="card">
				<div class="card-header">
					<div class="row align-items-center">
						<div class="col-8">
							ตารางผู้ใช้งาน
						</div>
						<div class="col-4 text-right">
							<button type="button" class="btn btn-default" data-toggle="modal" data-target="#user_add">
								เพิ่มผู้ใช้งาน
							</button>
						</div>

					</div>
				</div>
				<div class="card-body">

					<table class="table table-hover table-condensed" id="users-table">
						<thead>
							<th>#</th>
							<th>ชื่อผู้ใช้งาน</th>
							<th>ตำแหน่ง</th>
							<th>แผนก</th>
							<th>อีเมล</th>
							<th>Action</th>
						</thead>
						<tbody></tbody>
					</table>

				</div>
			</div>
		</div>


	</div>

	<!--Modal User Add -->
	<div class="modal fade" id="user_add" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">เพิ่มผู้ใช้งาน</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">

					<form action="{{ route('add.user') }}" method="POST" enctype="multipart/form-data" id="addUser">
						@csrf
						<div class="form-group">
							<label for="Name">ชื่อ</label>
							<input type="text" class="form-control" name="Name" placeholder="Enter name">
							<span class="text-danger error-text Name_error"></span>
						</div>
						<div class="form-group">
							<label for="email">อีเมล</label>
							<input type="email" class="form-control" name="email" placeholder="Enter email">
							<span class="text-danger error-text email_error"></span>
						</div>
						<div class="form-group">
							<label for="password">รหัสผ่าน</label>
							<input type="text" class="form-control" name="password" placeholder="Enter password">
							<span class="text-danger error-text email_error"></span>
						</div>
						<!--div class="form-group">
                                    <label for="picture">รูป</label>
                                    <input type="file" class="form-control" name="picture">
                                    <span class="text-danger error-text picture_error"></span>
                                </-div>
                                <div-- class="img-holder"></div-->
						<!-- php  -->
						<?php $partsD = DB::connection('mysql')->select('select * from department'); ?>
						<!-- end php  -->

						<div class="form-group">
							<label for="DepartmentID">แผนก</label>
							<select class="form-control" name="DepartmentID" id="DepartmentID">
								<option value="{{ isset($user->DepartmentID) ? $user->DepartmentID : ''}}">{{ isset($user->DepartmentID) ? $user->DepartmentID : 'เลือกแผนก'}}</option>
								@foreach($partsD as $row)
								<option value="{{$row->DepartmentID}}">{{$row->DepartmentName}}</option>
								@endforeach
							</select>
							<span class="text-danger error-text DepartmentID_error"></span>
						</div>

						<!-- php  -->
						<?php $partsR = DB::connection('mysql')->select('select * from role'); ?>
						<!-- end php  -->

						<div class="form-group">
							<label for="roleID">ตำแหน่ง</label>
							<select class="form-control" name="roleID" id="roleID">
								<option value="{{ isset($user->roleID) ? $user->roleID : ''}}">{{ isset($user->roleID) ? $user->roleID : 'เลือกตำแหน่ง'}}</option>
								@foreach($partsR as $row)
								<option value="{{$row->roleID}}">{{$row->roleName}}</option>
								@endforeach
							</select>
							<span class="text-danger error-text roleID_error"></span>
						</div>

						<div class="form-group">
							<button type="submit" class="btn btn-block btn-success">
								<i class="loading-icon fa fa-spinner fa-spin text-hide"></i>
								<span class="btn-txt">SAVE</span>
							</button>
						</div>
					</form>


				</div>
			</div>
		</div>
	</div>

	<!--Modal Edit -->
	<div class="modal fade editUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header pb-0">
					<h5 class="modal-title" id="exampleModalLabel">แก้ไขผู้ใช้งาน</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form action="<?= route('update.user.details') ?>" method="POST" enctype="multipart/form-data" id="update-user-form">
						@csrf
						<input type="hidden" name="uid">
						<div class="form-group">
							<label for="Name">ชื่อผู้ใช้งาน</label>
							<input type="text" class="form-control" name="Name" placeholder="Enter user name">
							<span class="text-danger error-text Name_error"></span>
						</div>
						<div class="form-group">
							<label for="email">อีเมล</label>
							<input type="email" class="form-control" name="email" placeholder="Enter user email">
							<span class="text-danger error-text email_error"></span>
						</div>
						<div class="form-group">
							<label for="password">รหัสผ่านใหม่</label>
							<input type="text" class="form-control" name="password" placeholder="Enter new password">
							<span class="text-danger error-text password_error"></span>
						</div>

						<!-- php  -->
						<?php $partsD = DB::connection('mysql')->select('select * from department'); ?>
						<!-- end php  -->

						<div class="form-group">
							<label for="DepartmentID">แผนก</label>
							<select class="form-control" name="DepartmentID" id="DepartmentID">
								<option value=""></option>
								<option value="{{ isset($user->DepartmentID) ? $user->DepartmentID : ''}}">{{ isset($user->DepartmentID) ? $user->DepartmentID : 'เลือกแผนก'}}</option>
								@foreach($partsD as $row)
								<option value="{{$row->DepartmentID}}">{{$row->DepartmentName}}</option>
								@endforeach
							</select>
							<span class="text-danger error-text DepartmentID_error"></span>
						</div>

						<!-- php  -->
						<?php $partsR = DB::connection('mysql')->select('select * from role'); ?>
						<!-- end php  -->

						<div class="form-group">
							<label for="roleID">ตำแหน่ง</label>
							<select class="form-control" name="roleID" id="roleID">
								<option value=""></option>
								<option value="{{ isset($user->roleID) ? $user->roleID : ''}}">{{ isset($user->roleID) ? $user->roleID : 'เลือกตำแหน่ง'}}</option>
								@foreach($partsR as $row)
								<option value="{{$row->roleID}}">{{$row->roleName}}</option>
								@endforeach
							</select>
							<span class="text-danger error-text roleID_error"></span>
						</div>

						<div class="form-group">
							<button type="submit" class="btn btn-block btn-success">Save Changes</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<!--Modal Info -->
	<div class="modal fade infoUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">รายละเอียดผู้ใช้งาน</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<input type="hidden" name="uid">

					<div class="row">
						<div class="col-6 col-md-5">
							<h3 class="text-center">ชื่อผู้ใช้งาน</h3>
						</div>
						<div class="col-6 col-md-6">
							<span class="text-center Name"></span>
						</div>
					</div>
					<p></p>
					<div class="row">
						<div class="col-6 col-md-5">
							<h3 class="text-center">อีเมล</h3>
						</div>
						<div class="col-6 col-md-6">
							<span class="text-center email"></span>
						</div>
					</div>
					<p></p>
					<div class="row">
						<div class="col-6 col-md-5">
							<h3 class="text-center">ตำแหน่ง</h3>
						</div>
						<div class="col-6 col-md-6">
							<span class="text-center roleName"></span>
						</div>
					</div>
					<p></p>
					<div class="row">
						<div class="col-6 col-md-5">
							<h3 class="text-center">แผนก</h3>
						</div>
						<div class="col-6 col-md-6">
							<span class="text-center DepartmentName"></span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


@endsection

@section('scripts')
<script>
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	$(function() {

		//ADD NEW DEPARTMENT
		$('#addDepartment').on('submit', function(e) {
			e.preventDefault();
			var form = this;
			$.ajax({
				url: $(form).attr('action'),
				method: 'POST',
				data: new FormData(form),
				processData: false,
				dataType: 'json',
				contentType: false,
				beforeSend: function() {
					$(form).find('span.error-text').text('');
				},
				success: function(data) {
					//console.log('hi',data);
					if (data.code == 0) {
						$.each(data.error, function(prefix, val) {
							$(form).find('span.' + prefix + '_error').text(val[0]);
						});
					} else {
						$(form)[0].reset();
						//  alert(data.msg);
						$('#departments-table').DataTable().ajax.reload(null, false);
						Swal.fire({
							position: 'top-end',
							icon: 'success',
							title: (data.msg),
							showConfirmButton: false,
							timerProgressBar: true,
							timer: 1500
						})
					}
				}
			});
		});


		//GET ALL DEPARTMENT
		var table = $('#departments-table').DataTable({
			responsive: true,
			processing: true,
			info: true,
			ajax: "{{ route('get.department.list') }}",
			"pageLength": 5,
			"aLengthMenu": [
				[5, 10, 25, 50, -1],
				[5, 10, 25, 50, "All"]
			],
			columns: [
				//{data:'RoomID', name:'RoomID'},
				{
					data: 'DT_RowIndex',
					name: 'DT_RowIndex'
				},
				{
					data: 'DepartmentName',
					name: 'DepartmentName'
				},
				{
					data: 'actions',
					name: 'actions'
				},
			]
		});

		$(document).on('click', '#editDBtn', function() {
			var d_id = $(this).data('id');
			$('.editDepartment').find('form')[0].reset();
			$('.editDepartment').find('span.error-text').text('');
			$.post('<?= route("get.department.details") ?>', {
				d_id: d_id
			}, function(data) {
				// alert(data.details.RoomNumber);   
				$('.editDepartment').find('input[name="did"]').val(data.details.DepartmentID);
				$('.editDepartment').find('input[name="DName"]').val(data.details.DepartmentName);
				$('.editDepartment').modal('show');
			}, 'json');
		});

		//UPDATE DEPARTMENT DETAILS
		$('#update-department-form').on('submit', function(e) {
			e.preventDefault();
			var form = this;
			$.ajax({
				url: $(form).attr('action'),
				method: 'POST',
				data: new FormData(form),
				processData: false,
				dataType: 'json',
				contentType: false,
				beforeSend: function() {
					$(form).find('span.error-text').text('');
				},
				success: function(data) {
					if (data.code == 0) {
						$.each(data.error, function(prefix, val) {
							$(form).find('span.' + prefix + '_error').text(val[0]);
						});
					} else {
						$('#departments-table').DataTable().ajax.reload(null, false);
						$('.editDepartment').modal('hide');
						$('.editDepartment').find('form')[0].reset();
						Swal.fire({
							position: 'top-end',
							icon: 'success',
							title: (data.msg),
							showConfirmButton: false,
							timerProgressBar: true,
							timer: 1500
						})
					}
				}
			});
		});

		//DELETE DEPARTMENT RECORD
		$(document).on('click', '#deleteDBtn', function() {
			var d_id = $(this).data('id');
			var url = '<?= route("delete.department") ?>';
			swal.fire({
				title: 'Are you sure?',
				html: 'ลบแผนกหรือไม่',
				icon: 'warning',
				showCancelButton: true,
				showCloseButton: true,
				cancelButtonText: 'ยกเลิก',
				confirmButtonText: 'ใช่, ลบเลย',
				cancelButtonColor: '#d33',
				confirmButtonColor: '#556ee6',
				allowOutsideClick: false
			}).then(function(result) {
				if (result.value) {
					$.post(url, {
						d_id: d_id
					}, function(data) {
						if (data.code == 1) {
							$('#departments-table').DataTable().ajax.reload(null, false);
							$('#users-table').DataTable().ajax.reload(null, false);
							Swal.fire({
								position: 'top-end',
								icon: 'success',
								title: (data.msg),
								showConfirmButton: false,
								timerProgressBar: true,
								timer: 1500
							})
						} else {
							Swal.fire({
								position: 'top-end',
								icon: 'error',
								title: (data.msg),
								showConfirmButton: false,
								timerProgressBar: true,
								timer: 1500
							})
						}
					}, 'json');
				}
			});
		});

	});

	//!-------------------------------------USER----------------------------------------------------------------------------------------------

	$(function() {

		//ADD NEW USER
		$('#addUser').on('submit', function(e) {
			e.preventDefault();
			var form = this;
			var img_holder = $('.img-holder');
			$(".loading-icon").removeClass("text-hide");
			$(".button").attr("disabled", true);
			$(".btn-txt").text("Please Wait...");
			$.ajax({
				url: $(form).attr('action'),
				method: 'POST',
				data: new FormData(form),
				processData: false,
				dataType: 'json',
				contentType: false,
				beforeSend: function() {
					$(form).find('span.error-text').text('');
				},
				success: function(data) {
					//console.log('hi',data);
					if (data.code == 0) {
						$.each(data.error, function(prefix, val) {
							$(form).find('span.' + prefix + '_error').text(val[0]);
						});
					} else {
						$(".loading-icon").addClass("text-hide");
						$(".button").attr("disabled", false);
						$(".btn-txt").text("Save");
						$(form)[0].reset();
						img_holder.empty();
						$('#users-table').DataTable().ajax.reload(null, false);
						Swal.fire({
							position: 'top-end',
							icon: 'success',
							title: (data.msg),
							showConfirmButton: false,
							timerProgressBar: true,
							timer: 1500
						})
						$('#user_add').modal('hide');

					}
				}
			});
		});


		//GET ALL USER
		var table = $('#users-table').DataTable({
			responsive: true,
			processing: true,
			info: true,
			ajax: "{{ route('get.user.list') }}",
			"pageLength": 5,
			"aLengthMenu": [
				[5, 10, 25, 50, -1],
				[5, 10, 25, 50, "All"]
			],
			columns: [
				//{data:'RoomID', name:'RoomID'},
				{
					data: 'DT_RowIndex',
					name: 'DT_RowIndex'
				},
				{
					data: 'name',
					name: 'name'
				},
				{
					data: 'roleName',
					name: 'roleName'
				},
				{
					data: 'DepartmentName',
					name: 'DepartmentName'
				},
				{
					data: 'email',
					name: 'email'
				},
				{
					data: 'actions',
					name: 'actions'
				},
			]
		});

		$(document).on('click', '#editUserBtn', function() {
			var user_id = $(this).data('id');
			$('.editUser').find('form')[0].reset();
			$('.editUser').find('span.error-text').text('');
			$.post('<?= route("get.user.details") ?>', {
				user_id: user_id
			}, function(data) {
				// alert(data.details.RoomNumber);   
				$('.editUser').find('input[name="uid"]').val(data.details.id);
				$('.editUser').find('input[name="Name"]').val(data.details.name);
				$('.editUser').find('input[name="email"]').val(data.details.email);
				$('.editUser').find('select[name=roleID] option').filter(':selected').text(data.details.roleName);
				$('.editUser').find('select[name=roleID] option').filter(':selected').val(data.details.roleID);
				$('.editUser').find('select[name=DepartmentID] option').filter(':selected').text(data.details.DepartmentName);
				$('.editUser').find('select[name=DepartmentID] option').filter(':selected').val(data.details.DepartmentID);
				$('.editUser').modal('show');
			}, 'json');

		});

		//UPDATE USER DETAILS
		$('#update-user-form').on('submit', function(e) {
			e.preventDefault();
			var form = this;
			$.ajax({
				url: $(form).attr('action'),
				method: 'POST',
				data: new FormData(form),
				processData: false,
				dataType: 'json',
				contentType: false,
				beforeSend: function() {
					$(form).find('span.error-text').text('');
				},
				success: function(data) {
					if (data.code == 0) {
						$.each(data.error, function(prefix, val) {
							$(form).find('span.' + prefix + '_error').text(val[0]);
						});
					} else {
						$('#users-table').DataTable().ajax.reload(null, false);
						$('.editUser').modal('hide');
						$('.editUser').find('form')[0].reset();
						Swal.fire({
							position: 'top-end',
							icon: 'success',
							title: (data.msg),
							showConfirmButton: false,
							timerProgressBar: true,
							timer: 1500
						})
					}
				}
			});
		});

		//DELETE USER RECORD
		$(document).on('click', '#deleteUserBtn', function() {
			var u_id = $(this).data('id');
			var url = '<?= route("delete.user") ?>';
			swal.fire({
				title: 'Are you sure?',
				html: 'ลบผู้ใช้นี้หรือไม่',
				icon: 'warning',
				showCancelButton: true,
				showCloseButton: true,
				cancelButtonText: 'ยกเลิก',
				confirmButtonText: 'ใช่, ลบเลย',
				cancelButtonColor: '#d33',
				confirmButtonColor: '#556ee6',
				allowOutsideClick: false
			}).then(function(result) {
				if (result.value) {
					$.post(url, {
						u_id: u_id
					}, function(data) {
						if (data.code == 1) {
							$('#users-table').DataTable().ajax.reload(null, false);
							Swal.fire({
								position: 'top-end',
								icon: 'success',
								title: (data.msg),
								showConfirmButton: false,
								timerProgressBar: true,
								timer: 1500
							})
						} else {
							Swal.fire({
								position: 'top-end',
								icon: 'error',
								title: (data.msg),
								showConfirmButton: false,
								timerProgressBar: true,
								timer: 1500
							})
						}
					}, 'json');
				}
			});
		});

		$(document).on('click', '#infoUserBtn', function() {
			var user_id = $(this).data('id');
			//console.log(user_id);

			$.post('<?= route("get.user.details") ?>', {
				user_id: user_id
			}, function(data) {
				// console.log(data.details.roleName);

				$('.infoUser').find('input[name="uid"]').val(data.details.id);
				$('.infoUser').find('.Name').text(data.details.name);
				$('.infoUser').find('.email').text(data.details.email);
				$('.infoUser').find('.roleName').text(data.details.roleName);
				$('.infoUser').find('.DepartmentName').text(data.details.DepartmentName);
				// $("#Image_User").html(`<img src="img/Image_User/${data.details.picture}" width="300" class="img-fluid img-thumbnail img-center">`); 
				$('.infoUser').modal('show');
			}, 'json');
		});

		$('input[type="file"][name="picture"]').val('');
		//Image preview
		$('input[type="file"][name="picture"]').on('change', function() {
			var img_path = $(this)[0].value;
			var img_holder = $('.img-holder');
			var extension = img_path.substring(img_path.lastIndexOf('.') + 1).toLowerCase();
			if (extension == 'jpeg' || extension == 'jpg' || extension == 'png') {
				if (typeof(FileReader) != 'undefined') {
					img_holder.empty();
					var reader = new FileReader();
					reader.onload = function(e) {
						$('<img/>', {
							'src': e.target.result,
							'class': 'img-fluid',
							'style': 'max-width:300;margin-bottom:10px;'
						}).appendTo(img_holder);
					}
					img_holder.show();
					reader.readAsDataURL($(this)[0].files[0]);
				} else {
					$(img_holder).html('This browser does not support FileReader');
				}
			} else {
				$(img_holder).empty();
			}
		});



	});
</script>



@endsection