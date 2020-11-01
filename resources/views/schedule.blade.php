<!DOCTYPE html>
<html>
    <head>
        <title>UniBot</title>

        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.3.2/main.css' rel='stylesheet' />
        <script src='https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js' type="text/javascript"></script>
        <script src='https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js' type="text/javascript"></script>
        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.3.2/main.js' type="text/javascript"></script>
        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.3.2/locales/ru.js' type="text/javascript"></script>
        <script src='https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js' type="text/javascript"></script>
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap-grid.min.css" rel="stylesheet">
        <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
        <link href="{{ asset('css/argon-design-system.css?v=1.2.0') }}" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200;700;900&display=swap" rel="stylesheet">
        <link href="{{ asset('css/nucleo-icons.css') }}" rel="stylesheet" />
        <link href="{{ asset('css/nucleo-svg.css') }}" rel="stylesheet" />
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var calendarEl = document.getElementById('calendar');
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    themeSystem: 'bootstrap',
                    locale: 'ru',
                    headerToolbar: {
                        left: 'dayGridMonth,timeGridWeek,timeGridDay add',
                        center: 'title',
                        right: 'prev,next'
                    },
                    customButtons: {
                        add: {
                            text: 'Добавить',
                            click: function () {
                                $('#addModal').modal('show')
                            }
                        }
                    },
                    events: 'https://yabzik.online/unisystem/public/api/feed/{{ $speciality }}/{{ $course }}',
                    // eventContent: function (arg) {
                    //     console.log(arg)
                    //     let html = `<a class="fc-daygrid-event fc-daygrid-dot-event fc-event fc-event-start fc-event-end fc-event-past"><div class="fc-daygrid-event-dot"></div><div class="fc-event-time">${arg.timeText}</div><div class="fc-event-title">${arg.event.title}</div></a>`
                    //     return { html }
                    // }
                });
                calendar.render();

                flatpickr('.flatpickr', {});
                // flatpickr('.datetimepicker', {
                //     enableTime: true,
                //     dateFormat: "Y-m-d H:i",
                // });

                $('#add-event').on('click', function () {
                    $.ajax({
                        url: 'https://yabzik.online/unisystem/public/api/addEvent/{{ $speciality }}/{{ $course }}',
                        data: {
                            'title': $('#title-input').val(),
                            'subtitle': $('#description-input').val(),
                            'date': $('.flatpickr-input').val(),
                            'index': $('#index-input').val()
                        },
                        contentType: "application/json",
                        dataType: 'json',
                        success: function(result){
                            location.reload();
                        }
                    });
                });

                let searchParams = new URLSearchParams(window.location.search);

                if (searchParams.has('token') && searchParams.get('token').charAt(0) == 'u' && searchParams.get('token').charAt(4) == 'n' && searchParams.get('token').charAt(7) == 'i') {
                    consele.log('authed')
                }
                else {
                    $('.fc-add-button').hide();
                }

            });
        </script>
    </head>
    <body>
        <div id='calendar'></div>

        <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="addModalLabel">Добавить событие</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="title-input">Заголовок</label>
                        <input type="text" class="form-control" id="title-input">
                    </div>
                    <div class="form-group">
                        <label for="description-input">Описание</label>
                        <textarea class="form-control" id="description-input" rows="2"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="index-input">Номер пары</label>
                        <input type="number" class="form-control" id="index-input">
                    </div>
                    <div class="form-group">
                        <label for="date-input">Дата:</label>
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                          </div>
                          <input class="flatpickr flatpickr-input form-control" id="date-input" type="text" placeholder="Выберите дату..">
                        </div>
                      </div>
                    {{-- <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                            </div>
                            <input class="flatpickr datetimepicker form-control" type="text" placeholder="Datetimepicker">
                        </div>
                    </div> --}}
                    {{-- <div class="row form-group ml-2">
                        <span class="badge badge-secondary">По номеру пары</span>
                        <label class="custom-toggle">
                            <input type="checkbox">
                            <span class="custom-toggle-slider rounded-circle"></span>
                        </label>
                        <span class="badge badge-secondary">По времени</span>
                    </div> --}}
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                  <button type="button" class="btn btn-primary" id="add-event">Добавить</button>
                </div>
              </div>
            </div>
          </div>
    </body>
</html>
