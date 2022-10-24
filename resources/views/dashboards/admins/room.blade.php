@extends('dashboards.admins.layouts.admin-dash-layout')
@section('title','Dashboard')

@section('content')

<div class="header bg-primary pb-4">
	<div class="container-fluid">
		<div class="header-body">
			<div class="row align-items-center py-4">
				<div class="col-lg-6 col-7">
					<h6 class="h2 text-white d-inline-block mb-0">ห้องประชุม</h6>
					<nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
						<ol class="breadcrumb breadcrumb-links breadcrumb-dark">
							<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">หน้าหลัก</a></li>
							<li class="breadcrumb-item active" aria-current="page">ห้องประชุม</li>
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
				<div class="card-header">ตารางห้องประชุม</div>
				<div class="card-body">

					<table class="table table-hover table-condensed" id="rooms-table">
						<thead>
							<th>#</th>
							<th>ชื่อห้อง</th>
							<th>เลขที่ห้อง</th>
							<th>Action</th>
						</thead>
						<tbody></tbody>
					</table>

				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="card">
				<div class="card-header">เพิ่มห้องประชุม</div>
				<div class="card-body">
					<form action="{{ route('add.room') }}" method="POST" enctype="multipart/form-data" id="addroom">
						@csrf
						<div class="form-group">
							<label for="">ชื่อห้อง</label>
							<input type="text" class="form-control" name="RoomName" placeholder="Enter room name">
							<span class="text-danger error-text RoomName_error"></span>
						</div>
						<div class="form-group">
							<label for="">เลขที่ห้อง</label>
							<input type="number" class="form-control" name="RoomNumber" placeholder="Enter room no.">
							<span class="text-danger error-text RoomNumber_error"></span>
						</div>
						<div class="form-group">
							<label for="">จำนวนคน</label>
							<input type="number" class="form-control" name="RoomAmount" placeholder="Enter amount">
							<span class="text-danger error-text RoomAmount_error"></span>
						</div>
						<div class="form-group">
							<label for="">รูปภาพ</label>
							<input type="file" class="form-control" name="Image_room">
							<span class="text-danger error-text Image_room_error"></span>
						</div>
						<div class="img-holder"></div>
						<div class="form-group">
							<button type="submit" class="btn btn-block btn-success">SAVE</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

</div>
<!--Modal Edit -->
<div class="modal fade editRoom" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">แก้ไขห้องประชุม</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form action="<?= route('update.room.details') ?>" method="POST" enctype="multipart/form-data" id="update-room-form">
					@csrf
					<input type="hidden" name="rid">
					<div class="form-group">
						<label for="">ชื่อห้อง</label>
						<input type="text" class="form-control" name="RoomName" placeholder="Enter room name">
						<span class="text-danger error-text RoomName_error"></span>
					</div>
					<div class="form-group">
						<label for="">เลขที่ห้อง</label>
						<input type="text" class="form-control" name="RoomNumber" placeholder="Enter room no.">
						<span class="text-danger error-text RoomNumber_error"></span>
					</div>
					<div class="form-group">
						<label for="">จำนวนคน</label>
						<input type="number" class="form-control" name="RoomAmount" placeholder="Enter amount">
						<span class="text-danger error-text RoomAmount_error"></span>
					</div>
					<div class="form-group">
						<label for="">รูปภาพ</label>
						<input type="file" class="form-control" name="Image_room_update">
						<span class="text-danger error-text Image_room_update_error"></span>
					</div>
					<div class="img-holder-update"></div>
					<div class="form-group">
						<button type="submit" class="btn btn-block btn-success">Save Changes</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<!--Modal Info -->
<div class="modal fade infoRoom" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">รายละเอียดห้องประชุม</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">

				<input type="hidden" name="rid">
				<div class="row">
					<div class="col-6 col-md-5">
						<h3 class="text-center">ชื่อห้องประชุม</h3>
					</div>
					<div class="col-6 col-md-6">
						<span class="text-center RoomName"></span>
					</div>
				</div>
				<p></p>
				<div class="row">
					<div class="col-6 col-md-5">
						<h3 class="text-center">เลขที่ห้อง</h3>
					</div>
					<div class="col-6 col-md-6">
						<span class="text-center RoomNumber"></span>
					</div>
				</div>
				<p></p>
				<div class="row">
					<div class="col-6 col-md-5">
						<h3 class="text-center">จำนวนคน</h3>
					</div>
					<div class="col-6 col-md-6">
						<span class="text-center RoomAmount"></span>
					</div>
				</div>

				<div class="mt-2" id="Image_Room"></div>

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

		//ADD NEW ROOM
		$('#addroom').on('submit', function(e) {
			e.preventDefault();
			var form = this;
			var img_holder = $('.img-holder');
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
						$('#rooms-table').DataTable().ajax.reload(null, false);
						img_holder.empty();
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


		//GET ALL ROOM
		var table = $('#rooms-table').DataTable({
			responsive: true,
			processing: true,
			info: true,
			ajax: "{{ route('get.room.list') }}",
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
					data: 'RoomName',
					name: 'RoomName'
				},
				{
					data: 'RoomNumber',
					name: 'RoomNumber'
				},
				{
					data: 'actions',
					name: 'actions'
				},
			]
		});

		$(document).on('click', '#editRoomBtn', function() {
			var room_id = $(this).data('id');
			$('.editRoom').find('form')[0].reset();
			$('.editRoom').find('span.error-text').text('');
			$.post('<?= route("get.room.details") ?>', {
				room_id: room_id
			}, function(data) {
				// alert(data.details.RoomNumber);   
				$('.editRoom').find('input[name="rid"]').val(data.details.RoomID);
				$('.editRoom').find('input[name="RoomName"]').val(data.details.RoomName);
				$('.editRoom').find('input[name="RoomNumber"]').val(data.details.RoomNumber);
				$('.editRoom').find('input[name="RoomAmount"]').val(data.details.RoomAmount);
				$('.editRoom').find('.img-holder-update').html('<img src="/img/Image_Room/' + data.details.Image_room + '" class="img-fluid" style="max-width:300;margin-bottom:10px;">');
				$('.editRoom').find('input[type="file"]').attr('data-value', '<img src="/img/Image_Room/' + data.details.Image_room + '" class="img-fluid" style="max-width:300;margin-bottom:10px;">');
				$('.editRoom').find('input[type="file"]').val('');
				$('.editRoom').modal('show');
			}, 'json');
		});

		$('input[type="file"][name="Image_room_update"]').on('change', function() {
			var img_path = $(this)[0].value;
			var img_holder = $('.img-holder-update');
			var currentImagePath = $(this).data('value');
			var extension = img_path.substring(img_path.lastIndexOf('.') + 1).toLowerCase();
			if (extension == 'jpg' || extension == 'jpeg' || extension == 'png') {
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
					$(img_holder).html('this browser not support FileReader');
				}
			} else {
				$(img_holder).html(currentImagePath);
			}

		});

		//UPDATE ROOM DETAILS
		$('#update-room-form').on('submit', function(e) {
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
						$('#rooms-table').DataTable().ajax.reload(null, false);
						$('.editRoom').modal('hide');
						$('.editRoom').find('form')[0].reset();
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

		//DELETE ROOM RECORD
		$(document).on('click', '#deleteRoomBtn', function() {
			var room_id = $(this).data('id');
			var url = '<?= route("delete.room") ?>';
			swal.fire({
				title: 'Are you sure?',
				html: 'ลบห้องประชุมหรือไม่',
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
						room_id: room_id
					}, function(data) {
						if (data.code == 1) {
							$('#rooms-table').DataTable().ajax.reload(null, false);
							Swal.fire({
								position: 'top-end',
								icon: 'success',
								title: (data.msg),
								showConfirmButton: false,
								timerProgressBar: true,
								timer: 1500
							})
						} else {
							toastr.error(data.msg);
						}
					}, 'json');
				}
			});
		});

		$(document).on('click', '#infoRoomBtn', function() {
			var room_id = $(this).data('id');

			$.post('<?= route("get.room.details") ?>', {
				room_id: room_id
			}, function(data) {
				//alert(room_id);   
				$('.infoRoom').find('input[name="rid"]').val(data.details.RoomID);
				$('.infoRoom').find('.RoomName').text(data.details.RoomName);
				$('.infoRoom').find('.RoomNumber').text(data.details.RoomNumber);
				$('.infoRoom').find('.RoomAmount').text(data.details.RoomAmount);
				$("#Image_Room").html(`<img src="img/Image_Room/${data.details.Image_room}" width="300" class="img-fluid img-center">`);
				$('.infoRoom').modal('show');
			}, 'json');
		});

		//Reset input file
		$('input[type="file"][name="Image_room"]').val('');
		//Image preview
		$('input[type="file"][name="Image_room"]').on('change', function() {
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
							'style': 'max-width:10;margin-bottom:10px;'
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