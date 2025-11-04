<!DOCTYPE html><html lang="en"><head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="pixelstrap">
    <link rel="icon" href="{{ asset(getSettingValue('background_image', 'assets/images/logdo.png')) }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset(getSettingValue('background_image', 'assets/images/logdo.png')) }}" type="image/x-icon">
    <title>Print Studio | @yield('title')</title>

    <!-- Google font-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/font-awesome.css') }}">
    <!-- ico-font-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/icofont.css') }}">
    <!-- Themify icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/themify.css') }}">
    <!-- Flag icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/flag-icon.css') }}">
    <!-- Feather icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/feather-icon.css') }}">
    <!-- Plugins css start-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/slick.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/slick-theme.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/scrollbar.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
    <!-- Plugins css Ends-->
    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.css') }}">
    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/custom.css') }}">
    <link id="color" rel="stylesheet" href="{{ asset('assets/css/color-1.css') }}" media="screen">
    <!-- Responsive css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/responsive.css') }}">
    <!-- Toastr CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<!-- Select2 CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

    
    <!-- latest jquery-->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <style>
    .logo-wrapper img {
    width: 79% !important;
    height: 51px !important;
}
    .select2-container .select2-selection--single {
    border-radius: 0.25rem !important;
    height: 37px !important;
    padding: 0px !important;
}
    /* सभी toastr message का text color white कर दो */
    #toast-container > .toast {
        color: white !important;
    }

    /* Optional: Background को थोड़ा dark करो ताकि text दिखे */
    #toast-container > .toast-success {
        background-color: #28a745 !important; /* ग्रीन */
    }

    #toast-container > .toast-error {
        background-color: #dc3545 !important; /* रेड */
    }

    #toast-container > .toast-warning {
        background-color: #ffc107 !important; /* येलो */
        color: black !important; /* इस case में white नहीं दिखेगा */
    }

    #toast-container > .toast-info {
        background-color: #17a2b8 !important; /* ब्लू */
    }
</style>

  </head>
  <body> 
    <div class="loader-wrapper"> 
      <div class="loader loader-1">
        <div class="loader-outter"></div>
        <div class="loader-inner"></div>
        <div class="loader-inner-1"></div>
      </div>
    </div>
    <!-- loader ends-->
    <!-- tap on top starts-->
    <div class="tap-top"><i data-feather="chevrons-up"></i></div>
    <!-- tap on tap ends-->
    <!-- page-wrapper StartMy Currencies-->
    <div class="page-wrapper compact-wrapper" id="pageWrapper">
        @include('layout.header')
        <div class="page-body-wrapper">
            @include('layout.sidebar')
              <div class="page-body">
                <!-- Container-fluid starts-->
                <div class="container-fluid">
                  @yield('admins')
                </div>
                <!-- Container-fluid Ends-->
              </div>
            @include('layout.footer')
        </div>
    </div>
    <!-- jQuery (already included), अब Select2 JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <!-- Bootstrap js-->
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <!-- feather icon js-->
    <script src="{{ asset('assets/js/feather.min.js') }}"></script>
    <script src="{{ asset('assets/js/feather-icon.js') }}"></script>
    <!-- scrollbar js-->
    <script src="{{ asset('assets/js/simplebar.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>
    <!-- Sidebar jquery-->
    <script src="{{ asset('assets/js/config.js') }}"></script>
    <!-- Plugins JS start-->
    <script src="{{ asset('assets/js/sidebar-menu.js') }}"></script>
    <script src="{{ asset('assets/js/sidebar-pin.js') }}"></script>
    <script src="{{ asset('assets/js/slick.min.js') }}"></script>
    <script src="{{ asset('assets/js/slick.js') }}"></script>
    <script src="{{ asset('assets/js/header-slick.js') }}"></script>
    <script src="{{ asset('assets/js/apex-chart.js') }}"></script>
    <script src="{{ asset('assets/js/stock-prices.js') }}"></script>
    <script src="{{ asset('assets/js/moment.min.js') }}"></script>
    <!-- calendar js-->
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable.custom.js') }}"></script>
    <script src="{{ asset('assets/js/datatable.custom1.js') }}"></script>
    <script src="{{ asset('assets/js/dashboard_4.js') }}"></script>
    
    <!-- Plugins JS Ends-->
    <!-- Theme js-->
    <script src="{{ asset('assets/js/script.js') }}"></script>
    <script src="{{ asset('assets/js/script1.js') }}"></script>
    <script src="{{ asset('assets/js/customizer.js') }}"></script>
    <!-- Plugin used-->
    <!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "timeOut": "5000",  // 5 सेकंड तक दिखेगा
        "positionClass": "toast-top-right"  // ऊपर दाईं तरफ पॉप-अप
    };

    @if(Session::has('success'))
        toastr.success("{{ Session::get('success') }}");
    @endif

    @if(Session::has('error'))
        toastr.error("{{ Session::get('error') }}");
    @endif

    @if(Session::has('info'))
        toastr.info("{{ Session::get('info') }}");
    @endif

    @if(Session::has('warning'))
        toastr.warning("{{ Session::get('warning') }}");
    @endif
</script>


  <script>
  // Select all <input type="date">
  document.querySelectorAll('input[type="date"],input[type="time"]').forEach(input => {
    input.addEventListener('click', () => {
      if (typeof input.showPicker === 'function') {
        input.showPicker(); // opens native calendar (modern browsers)
      } else {
        input.focus(); // fallback
      }
    });

    // Optional: also open on keyboard focus (Tab navigation)
    input.addEventListener('focus', () => {
      if (typeof input.showPicker === 'function') {
        input.showPicker();
      }
    });
  });
</script>
<script>
$(document).ready(function() {
    $('#hostelTable').DataTable({
        pageLength: 10,                        // Default 10 entries
        lengthMenu: [ [10, 25, 50, 100], [10, 25, 50, 100] ], // User select options
        searching: true,                        // Search box enable
        responsive: true
    });
});
</script>
<script>
    $(document).ready(function() {
        $('#student_details').select2({
            placeholder: "--select--",
            allowClear: true,
            width: '100%'  // ensures proper width
        });
    });
</script>
@yield('demo')


</body></html>