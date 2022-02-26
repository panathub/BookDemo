
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Noble-MeetingsRoom</title>
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

<body>
  <header style="background: rgba(255, 255, 255, 0.3);">
    <div class="container-fluid p-3">
  test
      <navmon class="navbar navbar-expand-lg">
      
          <img src="img/Noble.png"  alt="logo" style="max-width:15%;margin-left: 25px;">
  
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
          aria-label="Toggle navigation">
          <i class="fas fa-align-right text-black"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <div class="mr-auto"></div>
          <ul class="navbar-nav text-center">
          <li class="nav-item">
           <a class="nav-link text-gradient" href="{{ route('get.shabu') }}">ShabuShabu</a>
            </li>
            <li class="nav-item">
           <a class="nav-link text-gradient1" href="{{ route('get.sukiyaki') }}">Sukiyaki</a>
            </li>
           <li class="nav-item">
           <a class="nav-link text-gradient2" href="{{ route('get.karamiso') }}">Karamiso</a>
            </li>
            <li class="nav-item">
           <a class="nav-link text-gradient3" href="{{ route('get.tonkotsu') }}">Tonkotsu</a>
            </li>                      
            <li class="nav-item">
           <a class="nav-link text-gradient4"href="{{ route('get.kinoko') }}">Kinoko</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-gradient5" href="{{ route('login') }}">Login</a>
            </li>
            <li>
              
            </li>
          </ul>
        </div>
      </navmon>
    </div>
    <div class="container-fluid p-0">
   <div class="row">
        <div class="col-sm-7">
     <img src="img/00002.png" alt="Book">
        </div>
        <div class="col">
      <h1><div class="welcome text-gradient5">Meeting Room</div></h1>
        </div>
      </div>
</div>
  </header>

  <footer style="background:rgb(255,255,255)">
    <div class="contaibner-fluid p-4">
      <div class="row justify-content-center">
        <div class="col-md-6">
          <h4 class="text-center">Copyright © 2021</h4>
          <p class="text-muted text-center">Development By PanatHub</p>
          <div class="column text-light text-center">
          <a href="https://www.facebook.com/mzero.bangsangoon/" target="_blank"><i class="fab fa-facebook-f"></i></a>
          <a href="https://www.instagram.com/zal_mon/" target="_blank"><i class="fab fa-instagram"></i></a>
          <a href="https://github.com/panathub" target="_blank"><i class="fab fa-github"></i></a>
          </div>
        </div>
      </div>
    </div>
  </footer>

@include('managemodal') 

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
    crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
    crossorigin="anonymous"></script>
  <script src="sweetalert2/sweetalert2.min.js"></script>


<script>
  $(document).ready(function() {
    
              var m_id = $(this).data('id');
              //* -----------------------------------------------------------------------------
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
                   
            
  })
</script>





                    

</body>

</html>
