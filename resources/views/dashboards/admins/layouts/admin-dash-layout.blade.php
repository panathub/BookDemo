<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Admin Dashboard</title>
  <base href="{{ \URL::to('/') }}">
 
  <!-- Google Font: Source Sans Pro -->
  <link rel="icon" href="img/Noble.png" type="image/png">
  <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400&display=swap" rel="stylesheet">
 
  <!-- Icons -->
  <link rel="stylesheet" href="assets/vendor/nucleo/css/nucleo.css" type="text/css">
  <link rel="stylesheet" href="assets/vendor/@fortawesome/fontawesome-free/css/all.min.css" type="text/css">
 
  <!-- Argon CSS -->
  <link rel="stylesheet" href="assets/css/argon.css" type="text/css">
  
  <!-- Datatable -->
  <link rel="stylesheet" href="DataTables/datatables.css" type="text/css">
  <link rel="stylesheet" href="DataTables/Responsive-2.2.9/css/responsive.dataTables.min.css" type="text/css">
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
          <img src="img/Noble.png" class="navbar-brand-img" alt="...">
        </a>
      </div>
      <div class="navbar-inner">
        <!-- Collapse -->
        <div class="collapse navbar-collapse" id="sidenav-collapse-main">
          <!-- Nav items -->
          <ul class="navbar-nav">
            <li class="nav-item">
              <a href="{{ route('admin.dashboard') }}" is class="nav-link {{ (request()->is('admin/dashboard*')) ? 'active' : ''}}">
                <i class="ni ni-tv-2 text-primary"></i>
                <span class="nav-link-text">หน้าหลัก</span>
              </a>
            </li>
            <li class="nav-item">
            <a href="{{ route('admin.booking') }}" is class="nav-link {{ (request()->is('admin/booking*')) ? 'active' : ''}}">
                <i class="ni ni-pin-3 text-yellow"></i>
                <span class="nav-link-text">จัดการการจอง</span>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.room') }}" is class="nav-link {{ (request()->is('admin/room*')) ? 'active' : ''}}">
                <i class="ni ni-planet text-orange"></i>
                <span class="nav-link-text">จัดการห้องประชุม</span>
              </a>
            </li>
            <li class="nav-item">
            <a href="{{ route('admin.manageuser') }}" is class="nav-link {{ (request()->is('admin/manageuser*')) ? 'active' : ''}}">
                <i class="ni ni-circle-08 text-dark"></i>
                <span class="nav-link-text">จัดการผู้ใช้งาน</span>
              </a>
            </li>
            <li class="nav-item">
            <?php $partsm = DB::connection('mysql')->select('select * from modal'); ?>
            @foreach($partsm as $partm)
            <a href="#editModal" data-toggle="modal" is class="nav-link open-editModal"  data-id="{{$partm->id}}">
                <i class="ni ni-folder-17 text-danger"></i>
                <span class="nav-link-text">จัดการแจ้งเตือน</span>
              </a>
              @endforeach
            </li>
    
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
      </div>
    </div>
</div>

  </nav>
  <!-- Main content -->
  <div class="main-content" id="panel">
    <!-- Topnav -->
    <nav class="navbar navbar-top navbar-expand navbar-dark bg-primary border-bottom">
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
                   <span class="mb-0 text-sm  font-weight-bold" style="color:white" >&nbsp;Welcome Admin : {{ Auth::user()->name }}</span> 
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
    <div class="header bg-primary pb-6">
    @yield('content')
    </div>
    <!-- Page content -->
    
  </div>

          <!--Modal Edit -->
          <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Notification Modal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <form action="<?= route('update.modal.details') ?>" method="POST" enctype="multipart/form-data" id="update-modal-form">
                    @csrf
                     <input type="hidden" name="mid">
                     <div class="img-holder-update"></div>
                     <div class="form-group">
                         <label for="">Image</label>
                         <input type="file" class="form-control" name="Image_modal_update">
                         <span class="text-danger error-text Image_modal_update_error"></span>
                      </div>
                      <div class="form-group">
                        <div class ="row">
                          <div class="col col-1 ">
                            <label>Text</label>
                          </div>
                          <div class="col col-5"> <h5 class="text-danger">*เว้นวรรคประโยคทุกครั้ง</h5></div>
                         </div>
                        
                        
                         <textarea class="form-control"  rows="3" name="text" placeholder="Enter Text"></textarea>
                         <span class="text-danger error-text text_error"></span>
                     </div>
                     <div class="form-group">
                         <button type="submit" class="btn btn-block btn-success">
                           <i class="loading-icon fa fa-spinner fa-spin text-hide"></i>
                                    <span class="btn-txt">Save Change</span>
                          </button>
                     </div>
                 </form>
            </div>
        </div>
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
  <script src="DataTables/Buttons-2.0.1/js/dataTables.buttons.min.js"></script>
  <script src="DataTables/JSZip-2.5.0/jszip.min.js"></script>
  <script src="DataTables/Buttons-2.0.1/js/buttons.html5.min.js"></script>
  <script src="DataTables/Responsive-2.2.9/js/responsive.dataTables.min.js"></script>
  <script src="sweetalert2/sweetalert2.min.js"></script>
    <!-- Optional JS -->
  <script src="assets/js/argon.js"></script>
  <!-- Calendar -->

  
  @yield('scripts')

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
  mytime=setTimeout('display_ct5()',refresh)
    }
display_c5()

</script>

<script>
  $(window).on("load",function(){
     $(".loader-wrapper").fadeOut("slow");
});
</script>
<script>

$.ajaxSetup({
             headers:{
                 'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
             }
         });
         $(function(){

        $(document).on('click','.open-editModal', function(){ 
          var m_id = $(this).data('id');
          $('#editModal').find('form')[0].reset();
          $('#editModal').find('span.error-text').text('');
         $.get('<?= route("get.modal.details") ?>',{m_id:m_id}, function(data){
              $('#editModal').find('input[name="mid"]').val(data.details.id);  
              $('#editModal').find('textarea[name="text"]').val(data.details.text);  
              $('#editModal').find('.img-holder-update').html('<img src="/img/Image_Room/'+data.details.
              image+'" class="img-fluid" style="width:50%;margin-bottom:10px;">');
              $('#editModal').find('input[type="file"]').attr('data-value','<img src="/img/Image_Room/'+data.details.
              image+'" class="img-fluid" style="width:50%;margin-bottom:10px;">');
              $('#editModal').find('input[type="file"]').val('');  
              //console.log(data.details.text);    
              $('#editModal').modal('show');
              },'json');
            });

            $('input[type="file"][name="Image_modal_update"]').on('change', function(){
            var img_path = $(this)[0].value;
            var img_holder = $('.img-holder-update');
            var currentImagePath = $(this).data('value');
            var extension = img_path.substring(img_path.lastIndexOf('.')+1).toLowerCase();
            if(extension == 'jpg' || extension == 'jpeg' || extension == 'png'){
                if(typeof(FileReader) != 'undefined'){
                    img_holder.empty();
                    var reader = new FileReader();
                    reader.onload = function(e){
                        $('<img/>',{'src':e.target.result,'class':'img-fluid','style':'max-width:300;margin-bottom:10px;'}).appendTo(img_holder);
                    }
                    img_holder.show();
                    reader.readAsDataURL($(this)[0].files[0]);
                }else{
                    $(img_holder).html('this browser not support FileReader');
                }
            }else{
                $(img_holder).html(currentImagePath);
            }

        });               

                //UPDATE Modal DETAILS
                $('#update-modal-form').on('submit', function(e){
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
                        beforeSend: function(){
                             $(form).find('span.error-text').text('');
                        },
                        success: function(data){
                              if(data.code == 0){
                                  $.each(data.error, function(prefix, val){
                                      $(form).find('span.'+prefix+'_error').text(val[0]);
                                  });
                              }else{
                                  $(".loading-icon").addClass("text-hide");
                                  $(".button").attr("disabled", false);
                                  $(".btn-txt").text("Save Change");
                                  $('#editModal').modal('hide');
                                  $('#editModal').find('form')[0].reset();
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
          
});            
</script>

</body>
</html>