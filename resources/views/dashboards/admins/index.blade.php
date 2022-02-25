@extends('dashboards.admins.layouts.admin-dash-layout')
@section('title','Dashboard')

@section('content')

<div class="header bg-primary pb-4">
      <div class="container-fluid">
        <div class="header-body">
          <div class="row align-items-center py-4">
            <div class="col-lg-6 col-7">
              <h6 class="h2 text-white d-inline-block mb-0">ปฏิทินห้องประชุม</h6>
              <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">หน้าหลัก</a></li>
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
            <h5 class="h3 mb-0">รายการจองใช้ห้องประจำเดือน</h5>
        </div>
            <div class="card-body">
                
            <table class="table table-hover table-condensed" id="bookings-table">
                                <thead>
                                <tr>
                                    <!--th style="color: transparent;">Image</!--th-->
                                    <th>ห้อง</th>
                                    <th>ผู้จอง</th>
                                    <th>แผนก</th>
                                    <th>เริ่มเวลา</th>
                                    <th>สิ้นสุดเวลา</th>
                                    <th>หัวข้อการประชุม</th>
                                    <th>จำนวนผู้เข้าร่วมประชุม</th>
                                    <th>สถานะ</th>
                                    <th>รายละเอียด</th>
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

            <!-------------------------------Modal Info ------------------------------------------>
            <div class="modal fade infoBooking" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header pb-1">
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
            </div>
        </div>
    </div>
</div>

@include('managemodal')

@endsection

@section('scripts')

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>
<script src="assets/vendor/moment/min/moment-with-locales.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/fullcalendar@5.10.1/locales-all.js"></script>

 
    @if (Session::has('success'))
    <script>
        var m_id = $(this).data('id');
        $.get('<?= route("karamiso.noti.modal") ?>',{m_id:m_id}, function(data){
                //alert(room_id);   
          $('.NotiModal').find('input[name="mid"]').val(data.details.id);
          $('.NotiModal').find('.text').text(data.details.text);
          $("#image").html(`<img src="img/Image_Room/${data.details.image}" width="25%" height="25%" class="img-center">`); 
          $('.NotiModal').modal('show');      
        },'json');
            setTimeout(function(){
              $('.NotiModal').modal('hide')
        }, 5000);            
    </script>
     @endif
 
<script>
$(document).ready(function() {
  
        var calendarEl = document.getElementById('calendar');
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
             headers:{
                 'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
             }
         }); 

         $(function() {
                      //GET ALL BOOKIng
          var table =  $('#bookings-table').DataTable({
           responsive: true,
           processing:true,
           info:true,
           ajax:"{{ route('get.booking.index') }}",
           "pageLength":5,
           "aLengthMenu":[[5,10,25,50,-1],[5,10,25,50,"All"]],
 
           
           columns:[
            /*{data:'Image_room', render: function(data){       
              return '<img src="img/Image_Room/'+data+'"style="width:150px;height:105px">'
                    }},
            {data:'RoomName',
            render:function(data,type,row,meta){
                return '<b>ห้อง</b> '+row.RoomName +'<p></p><b>ผู้จอง</b> '+row.name +
                        '<p></p><b>สำหรับแผนก</b> '+row.DepartmentName 
            }},*/
            {data:'RoomName', name:'RoomName'},
            {data:'name', name:'name'},
            {data:'DepartmentName', name:'DepartmentName'},
            {data:"Booking_start",
                    render: function(data,type,row,meta){
                       return moment(data).locale('th').format('Do MMM YY เวลา LT')
                    }},
                {data:'Booking_end',
                render: function(data){
                    return moment(data).locale('th').format('Do MMM YY เวลา LT')
                }}, 
            {data:'BookingTitle', name:'BookingTitle'},
            {data:'BookingAmount', name:'BookingAmount'},
            {data:'VerifyStatus', 
              render: function(data,type,row,meta){
                if(data == 1){
                   return ' อนุมัติแล้ว';
                  }else if(data == 2){
                   return' ไม่อนุมัติ';
                  }else
                   return ' รอยืนยัน';
              }},
            {data:'BookingDetail', name:'BookingDetail'},
            {data:'actions', name:'actions'},
            
           ],
           columnDefs: [
            {
                "targets": [ 5 ],
                "visible": false,
                "searchable": false
            },
            {
              "targets": [ 6 ],
              "visible": false,
              "searchable": false
            },
            {
              "targets": [ 7 ],
              "visible": false,
              "searchable": false
            },
            {
              "targets": [ 8 ],
              "visible": false,
              "searchable": false
            }
        ],
           
      });

      $(document).on('click','#infoBookingBtn', function(){
                var booking_id = $(this).data('id');
                $.get('<?= route("get.booking.index.details") ?>',{booking_id:booking_id}, function(data){
                  
              $('.infoBooking').find('input[name="bkid"]').val(data.details.BookingID);
              $('.infoBooking').find('.DepartmentName').text(data.details.DepartmentName);
              $('.infoBooking').find('.BookingTitle').text(data.details.BookingTitle); 
              $('.infoBooking').find('.BookingAmount').text(data.details.BookingAmount);
              $('.infoBooking').find('.RoomName').text(data.details.RoomName); 
              $('.infoBooking').find('.Booking_start').text(data.details.Booking_start);
              $('.infoBooking').find('.Booking_end').text(data.details.Booking_end); 
              $('.infoBooking').find('.BookingDetail').text(data.details.BookingDetail);       
                  if(data.details.VerifyStatus == 1){
                    $('.infoBooking').find('.VerifyStatus').html('<span class="text-success">'+'<i class="fas fa-check text-success"></i>'+' อนุมัติแล้ว'+'</span>');
                  }else if(data.details.VerifyStatus == 2){
                    $('.infoBooking').find('.VerifyStatus').html('<span class="text-danger">'+'<i class="fas fa-times text-danger"></i>'+' ไม่อนุมัติ'+'</span>');
                  }else
                    $('.infoBooking').find('.VerifyStatus').html('<span class="text-warning">'+'<i class="fas fa-clock text-warning"></i>'+' รอยืนยัน'+'</span>');
              $('.infoBooking').modal('show');    
              //console.log(data.details.DepartmentName);   
              },'json'); 
            });

        });

</script>

@endsection