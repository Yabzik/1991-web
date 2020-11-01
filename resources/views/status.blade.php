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

<body class="status-page" style="height: 100vh; width: 100vw; overflow-x: hidden;">
  <section class="section section-hero section-shaped" style="overflow: visible;">
    <div class="shape shape-style-3 shape-default" style="height: 100%;">
        <span class="span-150"></span>
        <span class="span-50"></span>
        <span class="span-50"></span>
        <span class="span-75"></span>
        <span class="span-100"></span>
        <span class="span-75"></span>
        <span class="span-50"></span>
        <span class="span-100"></span>
        <span class="span-50"></span>
        <span class="span-100"></span>
    </div>
    <div class="container pt-lg-7">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card bg-secondary shadow border-0">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>Источник</th>
                                    <th>Факультет</th>
                                    <th>Специальность</th>
                                    <th class="text-right">Последняя проверка</th>
                                    <th class="text-right">Последнее обновление</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (App\Models\DataSource::all() as $source)
                                <tr>
                                    <td class="text-center">{{ $source->id }}</td>
                                    <td><a href="{{ $source->url }}">{{ $source->url }}</a></td>
                                    <td>ЭПФ</td>
                                    <td>{{ $source->title }}</td>
                                    <td class="text-right">{{ $source->last_checked }}</td>
                                    <td class="text-right">{{ $source->last_updated }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
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
        $('#schedule').show(250)
    });
  </script>
</body>

</html>
