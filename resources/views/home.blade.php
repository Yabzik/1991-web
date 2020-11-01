<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('img/apple-icon.png') }}">
  <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}">
  <title>
    UniBot
  </title>
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
  <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
  <!-- Nucleo Icons -->
  <link href="{{ asset('css/nucleo-icons.css') }}" rel="stylesheet" />
  <link href="{{ asset('css/nucleo-svg.css') }}" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <link href="{{ asset('css/font-awesome.css') }}" rel="stylesheet" />
  <link href="{{ asset('css/nucleo-svg.css') }}" rel="stylesheet" />
  <!-- CSS Files -->
  <link href="{{ asset('css/argon-design-system.css?v=1.2.0') }}" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200;700;900&display=swap" rel="stylesheet">
</head>

<body class="main-page" style="height: 100vh;">
  <section class="section section-shaped section-lg" style="overflow: visible; height: 100%;">
    <div class="shape shape-style-1 bg-gradient-default" style="height: 100%;">
      <span></span>
      <span></span>
      <span></span>
      <span></span>
      <span></span>
      <span></span>
      <span></span>
      <span></span>
    </div>
    <img src='{{ asset('img/logo.png') }}' width="96" height="96" style="display: block; margin: 0 auto;" >
    <h1 style="font-family: 'Montserrat', sans-serif; font-weight: 900; color: white; text-align: center;">UniBot</h1>
    <div class="container">
      <div class="row justify-content-center p-4">
        {{-- <div class="col-lg-5">
          <div class="card bg-secondary shadow border-0">
            <div class="card-header bg-white pb-5">
              <div class="text-muted text-center mb-3"><small>Sign in with</small></div>
              <div class="btn-wrapper text-center">
                <a href="#" class="btn btn-neutral btn-icon">
                  <span class="btn-inner--icon"><img src="../assets/img/icons/common/github.svg"></span>
                  <span class="btn-inner--text">Github</span>
                </a>
                <a href="#" class="btn btn-neutral btn-icon">
                  <span class="btn-inner--icon"><img src="../assets/img/icons/common/google.svg"></span>
                  <span class="btn-inner--text">Google</span>
                </a>
              </div>
            </div>
            <div class="card-body px-lg-5 py-lg-5">
              <div class="text-center text-muted mb-4">
                <small>Or sign in with credentials</small>
              </div>
              <form role="form">
                <div class="custom-control custom-control-alternative custom-checkbox">
                  <input class="custom-control-input" id=" customCheckLogin" type="checkbox">
                  <label class="custom-control-label" for=" customCheckLogin"><span>Remember me</span></label>
                </div>
                <div class="text-center">
                  <button type="button" class="btn btn-primary my-4">Sign in</button>
                </div>
              </form>
            </div>
          </div>
          <div class="row mt-3">
            <div class="col-6">
              <a href="#" class="text-light"><small>Forgot password?</small></a>
            </div>
            <div class="col-6 text-right">
              <a href="#" class="text-light"><small>Create new account</small></a>
            </div>
          </div>
        </div> --}}
        <div class="btn-wrapper text-center">
            <a href="https://t.me/Uniiiiiiiiiiiiiii_bot" class="btn btn-neutral btn-icon">
                <span class="btn-inner--icon"><i class="fab fa-telegram-plane"></i></span>
                <span class="btn-inner--text">Бот</span>
            </a>
        </div>
      </div>
      <h3 style="font-family: 'Montserrat', sans-serif; font-weight: 700; color: white; text-align: center;" class="m-0">Онлайн-расписание:</h3>
      <a href="updateStatus" style="font-family: 'Montserrat', sans-serif; font-weight: 200; color: white; text-align: center; display: block; margin: 0 auto; text-decoration: underline;" class="mb-2"><small>Статус обновлений</small></a>
      <div class="row justify-content-center">
        <ul class="m-0 p-0" style="flex-direction: row;">
            <li class="dropdown">
                <a href="#" class="btn btn-neutral btn-icon mr-0" class="nav-link" data-toggle="dropdown" href="#" role="button">
                    <span class="btn-inner--icon"><i class="fas fa-university"></i></span>
                    <span class="btn-inner--text" id="faculty-text">Факультет</span>
                </a>
                <div class="dropdown-menu">
                    <a href="#" class="dropdown-item faculty" id="epf">ЭПФ</a>
                </div>
            </li>
            <li class="dropdown" id="dropdown-speciality" style="display:none;">
                <a href="#" class="btn btn-neutral btn-icon" class="nav-link" data-toggle="dropdown" href="#" role="button">
                    <span class="btn-inner--icon"><i class="fas fa-graduation-cap"></i></span>
                    <span class="btn-inner--text" id="speciality-text">Специальность</span>
                </a>
                <div class="dropdown-menu">
                    @foreach (\App\Models\DataSource::where('faculty', 'epf')->get() as $source)
                    <a href="#" class="dropdown-item speciality" id="{{ $source->speciality }}">{{ $source->title }}</a>
                    @endforeach
                </div>
            </li>
            <li class="dropdown" id="dropdown-course" style="display:none;">
                <a href="#" class="btn btn-neutral btn-icon" class="nav-link" data-toggle="dropdown" href="#" role="button">
                    <span class="btn-inner--icon"><i class="far fa-smile"></i></span>
                    <span class="btn-inner--text" id="course-text">Курс</span>
                </a>
                <div class="dropdown-menu">
                    @foreach ([1, 2, 3, 4] as $course)
                    <a href="#" class="dropdown-item course" id="{{ $course }}">{{ $course }}</a>
                    @endforeach
                </div>
            </li>
        </ul>
      </div>
      <div class="row justify-content-center p-5" id="schedule" style="display: none;">
        <a href="#" class="btn btn-neutral btn-icon" id="schedule-link">
            <span class="btn-inner--icon"><i class="far fa-calendar-alt"></i></span>
            <span class="btn-inner--text">Онлайн-расписание</span>
        </a>
        <a href="#" class="btn btn-neutral btn-icon" id="sync-link">
            <span class="btn-inner--icon"><i class="fas fa-sync-alt"></i></span>
            <span class="btn-inner--text">Синхронизировать расписание</span>
        </a>
      </div>
    </div>
  </section>
  <!--   Core JS Files   -->
  <script src="{{ asset('js/core/jquery.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('js/core/popper.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('js/core/bootstrap.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('js/plugins/perfect-scrollbar.jquery.min.js') }}"></script>
  <script src="{{ asset('js/argon-design-system.min.js?v=1.2.0') }}" type="text/javascript"></script>

  <script>
    $( ".faculty" ).on( "click", function() {
        $("#faculty-text").text($( this ).text()).attr('data-selected', $( this ).attr('id'))
        $('#dropdown-speciality').show(250)
    });
    $( ".speciality" ).on( "click", function() {
        $("#speciality-text").text($( this ).text()).attr('data-selected', $( this ).attr('id'))
        $('#dropdown-course').show(250)
    });
    $( ".course" ).on( "click", function() {
        // $("#course-text").text($( this ).text()).attr('data-selected', $( this ).attr('id'))
        $('#schedule').show(250)

        let speciality = $("#speciality-text").attr('data-selected')
        let course = $( this ).attr('id')

        $('#schedule-link').attr('href', `schedule/${speciality}/${course}`)
        $('#sync-link').attr('href', `syncInfo/${speciality}/${course}`)
        $('#schedule').show(250)
    });
  </script>
</body>

</html>
