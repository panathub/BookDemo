@extends('dashboards.users.layouts.user-dash-layout')
@section('title','Dashboard')

@section('content')

<div class="header pb-4" style = "background: rgb(2,0,36);
background: linear-gradient(90deg, rgba(2,0,36,1) 0%, rgba(9,9,121,1) 35%, rgba(0,212,255,1) 100%);">
      <div class="container-fluid">
        <div class="header-body">
          <div class="row align-items-center py-4">
            <div class="col-lg-6 col-7">
              <h6 class="h2 text-white d-inline-block mb-0">การจอง</h6>
              <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                  <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">หน้าหลัก</a></li>
                  <li class="breadcrumb-item active" aria-current="page">การจอง</li>
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
<div class="col-md-8">
<div class="card">
<div class="card-header bg-transparent">
              <h2 class="mb-0 text-center"><i class="far fa-bookmark">&nbsp;</i>ฟอร์มจองห้องประชุม</h2>
            </div>
            <div class="card-body">
            <form action="{{ route('user.add.booking') }}" method="POST" enctype="multipart/form-data" id="addBooking"> 
            @csrf            
                           <div class="row">
                              <div class="col-6 col-md-5">
                                      <h3 class="text-center">ประชุมเรื่อง</h3>
                              </div>
                           <div class="col-6 ">
                           <input type="text" class="form-control" name="BookingTitle">
                           <span class="text-danger error-text BookingTitle_error"></span>
                                  </div>
                           </div>
                           <p></p>
                           <div class="row">
                              <div class="col-6 col-md-5">
                                      <h3 class="text-center">จำนวนผู้เข้าร่วมประชุม</h3>
                              </div>
                           <div class="col-6 ">
                           <input type="number" class="form-control" min="1" name="BookingAmount">
                           <span class="text-danger error-text BookingAmount_error"></span>
                                  </div>
                           </div>
                           <p></p>
                           <!-- php  -->
                           <?php $partsR = DB::connection('mysql')->select('select * from rooms'); ?> 
                           <!-- end php  -->
                           <div class="row">
                              <div class="col-6 col-md-5">
                                      <h3 class="text-center">ห้องที่ใช้ประชุม</h3>
                              </div>
                           <div class="col-6 ">
                           <select class="form-control" name="RoomID">
                        <option value="{{ isset($addbook->RoomID) ? $addbook->RoomID : ''}}">{{ isset($addbook->RoomID) ? $addbook->RoomID : 'เลือกห้องประชุม'}}</option>
                            @foreach($partsR as $row)
           	                <option value="{{$row->RoomID}}">{{$row->RoomName}}</option>
                            @endforeach
                        </select>
                        <span class="text-danger error-text RoomID_error"></span>
                                  </div>
                           </div>
                           <p></p>
                           <div class="row">
                              <div class="col-6 col-md-5">
                                      <h3 class="text-center">เริ่มเวลา</h3>
                              </div>
                           <div class="col-6 ">
                                <div class="input-group">
                                    <input type="text" id="datetimepicker" class="form-control" name="Booking_start">
                                    <div class="input-group-append">
                                    <span class="input-group-text" id="dateicon"><i class="fas fa-calendar-alt"></i></span>
                                    </div>
                                </div>    
                                <span class="text-danger error-text Booking_start_error"></span>                              
                            </div>
                           </div>
                           <p></p>
                           <div class="row">
                              <div class="col-6 col-md-5">
                                      <h3 class="text-center">สิ้นสุดเวลา</h3>
                              </div>
                              <div class="col-6 ">
                                <div class="input-group">
                                    <input type="text" id="datetimepicker1" class="form-control" name="Booking_end">
                                    <div class="input-group-append">
                                    <span class="input-group-text" id= "dateicon1"><i class="fas fa-calendar-alt"></i></span>
                                    </div>
                                </div>
                                <span class="text-danger error-text Booking_end_error"></span>                                    
                            </div>
                           </div>      
                           <p></p>
                           <div class="row">
                              <div class="col-6 col-md-5">
                                      <h3 class="text-center">รายละเอียดการประชุม</h3>
                              </div>
                           <div class="col-6 ">
                           <textarea class="form-control"  rows="3" name="BookingDetail" placeholder="-"></textarea>
                                  </div>
                           </div>
                           <p></p>
                           <button type="submit" class="btn btn-success btn-lg btn-block">
                               <i class="loading-icon fa fa-spinner fa-spin text-hide"></i>
                                    <span class="btn-txt">บันทึกการจองห้องประชุม</span>
                                </button>               
                           </form>
                        </div>
    </div>
    </div>
    </div>
    <div class="col">
<div class="card">
<div class="card-header bg-transparent">
              <h2 class="mb-0 text-center"><i class="far fa-bookmark">&nbsp;</i>รายการจองของฉัน</h2>
            </div>
            <div class="card-body">
                
                <table class="table table-hover table-condensed" id="user-bookings-table">
                                <thead>
                                    <th>#</th>
                                    <th>ชื่อห้อง</th>
                                    <th>วัน/เวลาเริ่ม</th>
                                    <th>วัน/เวลาสิ้นสุด</th>
                                    <th>สถานะการจอง</th>
                                    <th>Action</th>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
    </div>
    </div>
    </div>

</div>

        <!--Modal Edit -->
        <div class="modal fade editBooking" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header pb-0">
                <h5 class="modal-title" id="exampleModalLabel">แก้ไขการจอง</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <form action="<?= route('user.update.booking.details') ?>" method="POST" enctype="multipart/form-data" id="update-booking-form">
                    @csrf
                     <input type="hidden" name="bkid">
                     <input type="hidden" name="rpid">
                           <div class="row">
                              <div class="col-6 col-md-5">
                                      <h3 class="text-center">ประชุมเรื่อง</h3>
                              </div>
                           <div class="col-6 ">
                           <input type="text" class="form-control" name="BookingTitle">
                           <span class="text-danger error-text BookingTitle_error"></span> 
                                  </div>
                           </div>
                           <p></p>
                           <div class="row">
                              <div class="col-6 col-md-5">
                                      <h3 class="text-center">จำนวนผู้เข้าร่วมประชุม</h3>
                              </div>
                           <div class="col-6 ">
                           <input type="number" class="form-control" min="1" name="BookingAmount">
                           <span class="text-danger error-text BookingAmount_error"></span> 
                                  </div>
                           </div>
                           <p></p>
                           <!-- php  -->
                           <?php $partsR = DB::connection('mysql')->select('select * from rooms'); ?> 
                           <!-- end php  -->
                           <div class="row">
                              <div class="col-6 col-md-5">
                                      <h3 class="text-center">ห้องที่ใช้ประชุม</h3>
                              </div>
                           <div class="col-6 ">
                           <select class="form-control" name="RoomID" id="RoomID">
                            <option value=""></option>
                            <option value="{{ isset($user->RoomID) ? $user->RoomID : ''}}">{{ isset($user->RoomID) ? $user->RoomID : 'เลือกห้องประชุม'}}</option>
                            @foreach($partsR as $row)
           	                <option value="{{$row->RoomID}}">{{$row->RoomName}}</option>
                            @endforeach
                        </select>
                        <span class="text-danger error-text RoomID_error"></span>
                                  </div>
                           </div>
                           <p></p>
                           <div class="row">
                              <div class="col-6 col-md-5">
                                      <h3 class="text-center">เริ่มเวลา</h3>
                              </div>
                           <div class="col-6 ">
                                <div class="input-group">
                                    <input type="text" id="datetimepicker2" class="form-control" name="Booking_start">
                                    <div class="input-group-append">
                                    <span class="input-group-text" id="dateicon2"><i class="fas fa-calendar-alt"></i></span>
                                    </div>
                                </div>    
                                <span class="text-danger error-text Booking_start_error"></span>                              
                            </div>
                           </div>
                           <p></p>
                           <div class="row">
                              <div class="col-6 col-md-5">
                                      <h3 class="text-center">สิ้นสุดเวลา</h3>
                              </div>
                              <div class="col-6 ">
                                <div class="input-group">
                                    <input type="text" id="datetimepicker3" class="form-control" name="Booking_end">
                                    <div class="input-group-append">
                                    <span class="input-group-text" id="dateicon3"><i class="fas fa-calendar-alt"></i></span>
                                    </div>
                                </div>
                                <span class="text-danger error-text Booking_end_error"></span>                                    
                            </div>
                           </div>
                           <p></p>
                           <div class="row">
                              <div class="col-6 col-md-5">
                                      <h3 class="text-center">รายละเอียดการประชุม</h3>
                              </div>
                           <div class="col-6 ">
                           <textarea class="form-control"  rows="3" name="BookingDetail"></textarea>
                                  </div>
                           </div>
                           <p></p>
                     <div class="form-group">
                         <button type="submit" class="btn btn-block btn-success">Save Changes</button>
                     </div>
                 </form>
            </div>
        </div>
    </div>
</div>

            <!--Modal Info -->
            <div class="modal fade infoBooking" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
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
                           
                           <div class="mt-2" id="Image_Room"></div>    
             
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')

<script src="jquery/jquery.datetimepicker.full.min.js"></script>
<link rel="stylesheet" href="jquery/jquery.datetimepicker.css" type="text/css">
<script src="assets/vendor/moment/min/moment-with-locales.min.js"></script>

<script>

 $.ajaxSetup({
             headers:{
                 'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
             }
         });
      $(function() {

jQuery.datetimepicker.setLocale('th');

jQuery('#dateicon').click(function(){
  jQuery('#datetimepicker').datetimepicker('show'); //support hide,show and destroy command
});

jQuery('#dateicon1').click(function(){
  jQuery('#datetimepicker1').datetimepicker('show'); //support hide,show and destroy command
});

jQuery('#dateicon2').click(function(){
  jQuery('#datetimepicker2').datetimepicker('show'); //support hide,show and destroy command
});

jQuery('#dateicon3').click(function(){
  jQuery('#datetimepicker3').datetimepicker('show'); //support hide,show and destroy command
});

jQuery('#datetimepicker').datetimepicker({
    minDate:'-1970/01/01',
    allowTimes:[
    '08:00', '08:15', '08:30','08:45','09:00','09:15', '09:30', '09:45',  
    '10:00', '10:15', '10:30','10:45','11:00','11:15', '11:30', '11:45',
    '12:00', '12:15', '12:30','12:45','13:00','13:15', '13:30', '13:45',
    '14:00', '14:15', '14:30','14:45','15:00','15:15', '15:30', '15:45',
    '16:00', '16:15', '16:30','16:45','17:00','17:15', '17:30', '17:45',
    '18:00',
     ]
});
jQuery('#datetimepicker1').datetimepicker({         
    minDate:'-1970/01/01',
    allowTimes:[
    '08:00', '08:15', '08:30','08:45','09:00','09:15', '09:30', '09:45',  
    '10:00', '10:15', '10:30','10:45','11:00','11:15', '11:30', '11:45',
    '12:00', '12:15', '12:30','12:45','13:00','13:15', '13:30', '13:45',
    '14:00', '14:15', '14:30','14:45','15:00','15:15', '15:30', '15:45',
    '16:00', '16:15', '16:30','16:45','17:00','17:15', '17:30', '17:45',
    '18:00',
     ]
});
jQuery('#datetimepicker2').datetimepicker({
    minDate:'-1970/01/01',
    allowTimes:[
    '08:00', '08:15', '08:30','08:45','09:00','09:15', '09:30', '09:45',  
    '10:00', '10:15', '10:30','10:45','11:00','11:15', '11:30', '11:45',
    '12:00', '12:15', '12:30','12:45','13:00','13:15', '13:30', '13:45',
    '14:00', '14:15', '14:30','14:45','15:00','15:15', '15:30', '15:45',
    '16:00', '16:15', '16:30','16:45','17:00','17:15', '17:30', '17:45',
    '18:00',
     ]
});
jQuery('#datetimepicker3').datetimepicker({         
    minDate:'-1970/01/01',
    allowTimes:[
    '08:00', '08:15', '08:30','08:45','09:00','09:15', '09:30', '09:45',  
    '10:00', '10:15', '10:30','10:45','11:00','11:15', '11:30', '11:45',
    '12:00', '12:15', '12:30','12:45','13:00','13:15', '13:30', '13:45',
    '14:00', '14:15', '14:30','14:45','15:00','15:15', '15:30', '15:45',
    '16:00', '16:15', '16:30','16:45','17:00','17:15', '17:30', '17:45',
    '18:00',
     ]
});

          //GET ALL BOOKIng
          var table =  $('#user-bookings-table').DataTable({
           processing:true,
           info:true,
           ajax:"{{ route('user.get.booking.list') }}",
           "pageLength":5,
           "aLengthMenu":[[5,10,25,50,-1],[5,10,25,50,"All"]],
           columns:[

               {data:'DT_RowIndex', name:'DT_RowIndex'},
               {data:'RoomName', name:'RoomName'},
               {data:'Booking_start',
                    render: function(data){
                       return moment(data).locale('th').format('DD-MM-YYYY เวลา LT')
                    }},
                {data:'Booking_end',
                render: function(data){
                    return moment(data).locale('th').format('DD-MM-YYYY เวลา LT')
                }},
                
               {data:'VerifyStatus', 
                    render:function(data){
                        if(data == 1){                           
                            return '<span style="color:green">'+'อนุมัติแล้ว'+'</span>';
                            
                        }
                        else if(data == 2){
                            return '<span style="color:red">'+'ไม่อนุมัติ'+'</span>';
                        }
                        else return 'รอยืนยัน'
               }},
               {data:'actions', name:'actions'},
           ]     
      });

      //ADD NEW ฺBOOKING
$('#addBooking').on('submit', function(e){
          e.preventDefault();
          var form = this;
          $(".loading-icon").removeClass("text-hide");
          $(".button").attr("disabled", true);
          $(".btn-txt").text("Please Wait...");
          $.ajax({
              url:$(form).attr('action'),
              method:'POST',
              data:new FormData(form),
              processData:false,
              dataType:'json',
              contentType:false,
              beforeSend:function(){
                   $(form).find('span.error-text').text('');
              },
              success:function(data){
                //console.log('hi',data);
                    if(data.code == 0){
                          $.each(data.error, function(prefix, val){
                              $(form).find('span.'+prefix+'_error').text(val[0]);
                          });
                    }else if(data.code == 2){
                        $(".loading-icon").addClass("text-hide");
                        $(".button").attr("disabled", false);
                        $(".btn-txt").text("บันทึกการจองห้องประชุม");
                       Swal.fire({                         
                                    icon: 'error',
                                    title: (data.msg),  
                                    timerProgressBar: true,
                                timer: 1500
                                })
                        }else if(data.code == 3){
                        $(".loading-icon").addClass("text-hide");
                        $(".button").attr("disabled", false);
                        $(".btn-txt").text("บันทึกการจองห้องประชุม");
                       Swal.fire({                         
                                    icon: 'error',
                                    title: (data.msg),  
                                    timerProgressBar: true,
                                timer: 1500
                                })            
                    }else{
                        $(".loading-icon").addClass("text-hide");
                        $(".button").attr("disabled", false);
                        $(".btn-txt").text("บันทึกการจองห้องประชุม");
                        $('#user-bookings-table').DataTable().ajax.reload(null, false);
                        $(form)[0].reset();
                      //  alert(data.msg);
                       Swal.fire({
                                    icon: 'success',
                                    title: (data.msg),
                                    timerProgressBar: true,
                                timer: 1500
                                })
                    }
              }
          });
      });

      $(document).on('click','#editBookingBtn', function(){
          var booking_id = $(this).data('id');
          $('.editBooking').find('form')[0].reset();
          $('.editBooking').find('span.error-text').text('');

         $.post('<?= route("user.get.booking.details") ?>',{booking_id:booking_id}, function(data){
              // alert(data.details.RoomNumber);   
              $('.editBooking').find('input[name="bkid"]').val(data.details.BookingID);
              $('.editBooking').find('input[name="rpid"]').val(data.details.ReportID);
              $('.editBooking').find('input[name="BookingTitle"]').val(data.details.BookingTitle); 
              $('.editBooking').find('input[name="BookingAmount"]').val(data.details.BookingAmount); 
              $('.editBooking').find('select[name=RoomID] option').filter(':selected').text(data.details.RoomName); 
              $('.editBooking').find('select[name=RoomID] option').filter(':selected').val(data.details.RoomID); 
              $('.editBooking').find('input[name="BookingDate"]').val(data.details.BookingDate);
              $('.editBooking').find('input[name="Booking_start"]').val(data.details.Booking_start);
              $('.editBooking').find('input[name="Booking_end"]').val(data.details.Booking_end);   
              $('.editBooking').find('textarea[name="BookingDetail"]').val(data.details.BookingDetail);         
              $('.editBooking').modal('show');
              },'json');

            });

                    //UPDATE ฺBooking DETAILS
                    $('#update-booking-form').on('submit', function(e){
                    e.preventDefault();
                    var form = this;
                    $.ajax({
                        url:$(form).attr('action'),
                        method:'POST',
                        data:new FormData(form),
                        processData:false,
                        dataType:'json',
                        contentType:false,
                        beforeSend: function(){
                             $(form).find('span.error-text').text('');
                        },
                        success: function(data){
                              if(data.code == 0){
                                  $.each(data.error, function(prefix, val){
                                      $(form).find('span.'+prefix+'_error').text(val[0]);
                                  });
                                }else if(data.code == 2){
                                Swal.fire({                         
                                    icon: 'error',
                                    title: (data.msg),  
                                    timerProgressBar: true,
                                timer: 1500
                                })    
                              }else{
                                  $('#user-bookings-table').DataTable().ajax.reload(null, false);
                                  $('.editBooking').modal('hide');
                                  $('.editBooking').find('form')[0].reset();
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
               

                //DELETE BOOKING RECORD
                $(document).on('click','#deleteBookingBtn', function(){
                    var booking_id = $(this).data('id');
                    var url = '<?= route("user.delete.booking") ?>';
                    swal.fire({
                         title:'Are you sure?',
                         html:'ลบการจองนี้หรือไม่',
                         icon: 'warning',
                         showCancelButton:true,
                         showCloseButton:true,
                         cancelButtonText:'ยกเลิก',
                         confirmButtonText:'ใช่, ลบเลย',
                         cancelButtonColor:'#d33',
                         confirmButtonColor:'#556ee6',
                         allowOutsideClick:false
                    }).then(function(result){
                          if(result.value){
                              $.post(url,{booking_id:booking_id}, function(data){
                                   if(data.code == 1){
                                       $('#user-bookings-table').DataTable().ajax.reload(null, false);
                                       Swal.fire({
                                    position: 'top-end',
                                    icon: 'success',
                                    title: (data.msg),
                                    showConfirmButton: false,
                                    timerProgressBar: true,
                                    timer: 1500
                                        })
                                   }else{
                                       toastr.error(data.msg);
                                   }
                              },'json');
                          }
                    });
                });  

                $(document).on('click','#infoBookingBtn', function(){
                var booking_id = $(this).data('id');
                $.post('<?= route("user.get.booking.details") ?>',{booking_id:booking_id}, function(data){
                  
              $('.infoBooking').find('input[name="bkid"]').val(data.details.BookingID);
              $('.infoBooking').find('.DepartmentName').text(data.details.DepartmentName);
              $('.infoBooking').find('.BookingTitle').text(data.details.BookingTitle); 
              $('.infoBooking').find('.BookingAmount').text(data.details.BookingAmount);
              $('.infoBooking').find('.RoomName').text(data.details.RoomName); 
              $('.infoBooking').find('.BookingDate').text(data.details.BookingDate);
              $('.infoBooking').find('.Booking_start').text(data.details.Booking_start);
              $('.infoBooking').find('.Booking_end').text(data.details.Booking_end); 
              $('.infoBooking').find('.BookingDetail').text(data.details.BookingDetail); 
              $("#Image_Room").html(`<img src="img/Image_Room/${data.details.Image_room}" width="300" class="img-fluid img-center">`);       
              $('.infoBooking').modal('show');    
             // console.log(data.details.BookingID)        
              },'json'); 
            });



});

 </script>  
                @if (Session::has('success'))
                        <script>
                       Swal.fire({
                                    icon: 'success',
                                    title: '{!! Session::get('success') !!}',
                                })                  
                        </script>
                    @endif
                        @if (Session::has('fail'))
                        <script>
                       Swal.fire({
                                    icon: 'warning',
                                    title: '{!! Session::get('fail') !!}',                                  
                                })                  
                        </script>
                    @endif
                    
              
@endsection