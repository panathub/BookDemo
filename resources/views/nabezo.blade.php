<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta http-equiv="refresh" content="30">
  <title>Noble-MeetingsRoom</title>
  <base href="{{ \URL::to('/') }}">
  <link rel="icon" href="img/Noble.png" type="image/png">
  <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
    crossorigin="anonymous" />
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr"
    crossorigin="anonymous">
  <link rel="stylesheet" href="css_welcome/Style.css" />
  <link rel="stylesheet" href="css_welcome/mobile-style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
  <link rel="stylesheet" href="sweetalert2/sweetalert2.min.css" type="text/css">
</head>

<body onload=display_ct5();>
  <header style="background-image: linear-gradient(to right top, #051937, #004d7a, #008793, #00bf72, #a8eb12);">
    <div class="container-fluid p-3">
      <nav class="navbar navbar-expand-lg">
        <a class="navbar-brand" href="#">
          <i class="fas fa-book-reader fa-2x mx-3"></i>NobleMeetingRoom</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
          aria-label="Toggle navigation">
          <i class="fas fa-align-right text-light"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <div class="mr-auto"></div>
          <ul class="navbar-nav text-center">
            <li class="nav-item active">
              <a class="nav-link" href="/">หน้าหลัก
                <span class="sr-only">(current)</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('login') }}">เข้าสู่ระบบ</a>
            </li>

           <li class="nav-item">
            </li>
          </ul>
        </div>
      </nav>
    </div>
    <div class="container text-center">
             <div class="col-md-8 col-sm-12  text-white">
             <h5><span id='ct5' ></span></h5>

             <form action="<?= route('verify.meeting') ?>" method="POST" enctype="multipart/form-data" id="verify-booking-form">
             @csrf 
                   
             <div class="row justify-content-center">
           
              
               <input type="hidden" name="bkid"> 
               <input type="hidden" name="rid"> 
             <h1>ห้องประชุม</h1>&nbsp;<h1>Nabezo</h1>
             </div>
              <h6>สถานะ</h6>        
              <h3 class="BookingStatus"></h3>

        </div>
    </div>
    <div class="container-fluid p-4">

    </div>
  </header>
  <main>
    <section class="section-1">
      <div class="container text-center text-white">
          <div class="col-md-12 col-14">
            <div class="panel text-left">

              <h3 class="text-left" style="background-color: #0093E9;
                                            background-image: linear-gradient(160deg, #0093E9 0%, #80D0C7 100%);">Upcoming Meeting</h3>
              <div>
                <h1 class="Booking_start">-</h1>
                <h1 class="Booking_end">-</h1>
              </div>

              <div>
                <h3>By</h3>
               
                <div class="row">   
                <div class="col-md-1 col-1">
                  <h3 class="name">-</h3>
                  </div>     
               
                <h3 class="DepartmentName"><span style="display:none">d</span></h3>
                
 
               
               
                </div>

              </div>
      <div class="container-fluid p-4">

    </div>
      <div class="container text-center">
      <button type="submit"class="btn btn-light px-5 py-2 primary-btn" id="verifyHomeBtn">
            Booking
          </button>
    </form>
</div>
    </section>

  </main>
  <footer>
  <div class="container-fluid p-4">

    </div>
  </footer>

  @include('managemodal') 

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
    crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
    crossorigin="anonymous"></script>
  <script src="sweetalert2/sweetalert2.min.js"></script>
  <script src="assets/vendor/moment/min/moment-with-locales.min.js"></script>

  <script>
  function display_ct5() {
  var x = new Date();
  var x1 = " - " +  x.getHours()+ ":" +  ('0'+x.getMinutes()).slice(-2) + ":" +  ('0'+x.getSeconds()).slice(-2) ;  
  var result = x.toLocaleDateString('th-TH', {
  year: 'numeric',
  month: 'long',
  day: 'numeric',
  weekday: 'long',
})
  document.getElementById('ct5').innerHTML = result + x1;
 
  display_c5();
    }
 function display_c5(){
  var refresh=1000; // Refresh rate in milli seconds
  mytime=setTimeout('display_ct5()',refresh);
    }
display_c5();
</script>

<script>
 $.ajaxSetup({
             headers:{
                 'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
             }
         });
      $(function() {
      $(document).ready(function(){
        var booking_id = $(this).data('id');
        var today = new Date();
        var presentTime = today.getFullYear() + "-" + ('0' + (today.getMonth()+1)).slice(-2) + "-" + ('0' + today.getDate()).slice(-2) + " " + ('0' + today.getHours()).slice(-2) + ":" + ('0'+today.getMinutes()).slice(-2);
        times = new Date(today.setMinutes(today.getMinutes() + 30));
        var timeAfter30Mins = moment(times).locale('th').format('LT');
        //console.log(time);
        //console.log(display_c5());
     
             //* -----------------------------------------------------------------------------
             $.get('<?= route("get.booking.nabezo") ?>',{booking_id:booking_id}, function(data){
              
              var startAfter30Mins = moment(data.details.Booking_start).add(30,'minutes').locale('th').format('YYYY-MM-DD HH:mm');
              var end = moment(data.details.Booking_end).locale('th').format('HH:mm');
              var end3 = data.details.Booking_end;
              var status = data.details.BookingStatus;
              //alert(data.details[1].BookingID);
             // for (var i=0;i<data.details.Booking>)
              if(presentTime <= startAfter30Mins && status == 0){              
                                             
                displayData();
                displayModal();
                console.log('if1');

              }else if(presentTime >= startAfter30Mins && status == 0){

                deleteData();
                console.log('if2');

              }else if(presentTime <= end3 && status == 1){

                displayData();
                console.log('success');

              }else if(presentTime > end3 && status == 1){

                deleteData();
                console.log('if3');
                
                }else{
                  displayData2TEST()
                console.log('error');
                }
              
            });
            
        
                    //Verify ฺBooking DETAILS
                    $('#verify-booking-form').on('submit', function(e){
                    e.preventDefault();
                    var form = this;
                    $.ajax({
                        url:$(form).attr('action'),
                        method:'POST',
                        data:new FormData(form),
                        processData:false,
                        dataType:'json',
                        contentType:false,
                        success: function(data){     
                                  Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: (data.msg),
                                    showConfirmButton: false,
                                    timerProgressBar: true,
                                timer: 1500
                                });
                                window.setTimeout(function(){
                                   window.location.reload();     
                                }, 2000); 
                              
                        }
                    });
                }); 
          });
          

          function displayData() {
          var booking_id = $(this).data('id');
          var room_id = $(this).data('RoomID');
          var today = new Date();
          var time = today.getHours() + ":" + today.getMinutes()
             //* -----------------------------------------------------------------------------
             $.get('<?= route("get.booking.nabezo") ?>',{booking_id:booking_id},(function(data){
              
                //alert(room_id);  
               
              $('input[name="bkid"]').val(data.details.BookingID);
              $('input[name="rid"]').val(data.details.RoomID);
              
              $('.name').text(data.details.name);  
              $('.DepartmentName').text(data.details.DepartmentName);
              $('.Booking_start').text(moment(data.details.Booking_start).locale('th').format('DD-MM-YYYY เวลา LT'));
              $('.Booking_end').text(moment(data.details.Booking_end).locale('th').format('DD-MM-YYYY เวลา LT'));
              if(data.details.BookingStatus == 0){
                    $('.BookingStatus').html('<span class="text-white">'+'<i class="fas fa-check text-white"></i>'+' รอยืนยันการใช้ห้องประชุม'+'</span>');
                  }else{
                    $('.BookingStatus').html('<span class="text-white">'+'<i class="fas fa-clock text-white"></i>'+' กำลังดำเนินการประชุม'+'</span>');
                  }
              }),'json');
          }

          function displayData2TEST() {
          var booking_id = $(this).data('id');
          var room_id = $(this).data('RoomID');
          var today = new Date();
          var time = today.getHours() + ":" + today.getMinutes()
             //* -----------------------------------------------------------------------------
             $.get('<?= route("get.booking.nabezo.test") ?>',{booking_id:booking_id}, function(data){
                //alert(room_id);  
              $('input[name="bkid"]').val(data.details.BookingID);
              $('input[name="rid"]').val(data.details.RoomID);
              $('.name').text(data.details.name);  
              $('.DepartmentName').text(data.details.DepartmentName);
              $('.Booking_start').text(moment(data.details.Booking_start).locale('th').format('DD-MM-YYYY เวลา LT'));
              $('.Booking_end').text(moment(data.details.Booking_end).locale('th').format('DD-MM-YYYY เวลา LT'));
              if(data.details.BookingStatus == 0){
                    $('.BookingStatus').html('<span class="text-white">'+'<i class="fas fa-check text-white"></i>'+' รอยืนยันการใช้ห้องประชุม'+'</span>');
                  }else{
                    $('.BookingStatus').html('<span class="text-white">'+'<i class="fas fa-clock text-white"></i>'+' กำลังดำเนินการประชุม'+'</span>');
                  }
              },'json');
          }

          function deleteData() {
          var booking_id = $(this).data('id');
          var room_id = $(this).data('RoomID');        
             //* -----------------------------------------------------------------------------
             $.post('<?= route("delete.booking.nabezo") ?>',{booking_id}, function(data){             
          },'json');
        }

        function displayModal() {
              var m_id = $(this).data('id');
              //* -----------------------------------------------------------------------------
              $.get('<?= route("karamiso.noti.modal") ?>',{m_id:m_id}, function(data){
                //alert(room_id);   
             $('.NotiModal').find('input[name="mid"]').val(data.details.id);
             $('.NotiModal').find('.text').text(data.details.text);
             $("#image").html(`<img src="img/Image_Room/${data.details.image}" width="55%" height="55%" class="img-center">`); 
             $('.NotiModal').modal('show');      
              },'json');
              setTimeout(function(){
                $('.NotiModal').modal('hide')
                    }, 5000);
                   
            };

       

          });
          
</script> 




</body>

</html>