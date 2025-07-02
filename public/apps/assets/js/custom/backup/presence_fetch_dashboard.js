"use strict";
var calendar;

! function (NioApp, $) {
  "use strict";

  (function ($) {
    "use strict";

    function lineOverview(selector, set_data) {
      var $selector = (selector) ? $(selector) : $('.overview-chart');
      $selector.each(function () {
        var $self = $(this),
          _self_id = $self.attr('id'),
          _get_data = (typeof set_data === 'undefined') ? eval(_self_id) : set_data;

        var selectCanvas = document.getElementById(_self_id).getContext("2d");
        var chart_data = [];

        for (var i = 0; i < _get_data.datasets.length; i++) {
          chart_data.push({
            label: _get_data.datasets[i].label,
            tension: _get_data.lineTension,
            backgroundColor: _get_data.datasets[i].background,
            borderWidth: 4,
            borderColor: _get_data.datasets[i].color,
            pointBorderColor: "transparent",
            pointBackgroundColor: "transparent",
            pointHoverBackgroundColor: "#fff",
            pointHoverBorderColor: _get_data.datasets[i].color,
            pointBorderWidth: 4,
            pointHoverRadius: 6,
            pointHoverBorderWidth: 4,
            pointRadius: 6,
            pointHitRadius: 6,
            data: _get_data.datasets[i].data,
          });
        }

        var chart = new Chart(selectCanvas, {
          type: 'line',
          data: {
            labels: _get_data.labels,
            datasets: chart_data,
          },
          options: {
            legend: {
              display: (_get_data.legend) ? _get_data.legend : false,
              rtl: NioApp.State.isRTL,
              labels: {
                boxWidth: 30,
                padding: 20,
                fontColor: '#6783b8',
              }
            },
            maintainAspectRatio: false,
            tooltips: {
              enabled: true,
              rtl: NioApp.State.isRTL,
              callbacks: {
                title: function (tooltipItem, data) {
                  return data['labels'][tooltipItem[0]['index']];
                },
                label: function (tooltipItem, data) {
                  return data.datasets[tooltipItem.datasetIndex]['data'][tooltipItem['index']] + ' ' + _get_data.dataUnit;
                }
              },
              backgroundColor: '#1c2b46',
              titleFontSize: 13,
              titleFontColor: '#fff',
              titleMarginBottom: 4,
              bodyFontColor: '#fff',
              bodyFontSize: 12,
              bodySpacing: 10,
              yPadding: 12,
              xPadding: 12,
              footerMarginTop: 0,
              displayColors: false
            },
            scales: {
              yAxes: [{
                display: true,
                stacked: (_get_data.stacked) ? _get_data.stacked : false,
                position: NioApp.State.isRTL ? "right" : "left",
                ticks: {
                  beginAtZero: true,
                  fontSize: 11,
                  fontColor: '#9eaecf',
                  padding: 10,
                  callback: function (value, index, values) {
                    return value;
                  },
                  min: 5,
                  max: 12,
                  stepSize: 0.5
                },
                gridLines: {
                  color: NioApp.hexRGB("#526484", .2),
                  tickMarkLength: 0,
                  zeroLineColor: NioApp.hexRGB("#526484", .2)
                },
              }],
              xAxes: [{
                display: true,
                stacked: (_get_data.stacked) ? _get_data.stacked : false,
                ticks: {
                  fontSize: 9,
                  fontColor: '#9eaecf',
                  source: 'auto',
                  padding: 10,
                  reverse: NioApp.State.isRTL
                },
                gridLines: {
                  color: "transparent",
                  tickMarkLength: 0,
                  zeroLineColor: 'transparent',
                },
              }]
            }
          }
        });
        $self.data('chart-instance', chart);
      });
    }
    window.lineOverview = lineOverview;
  })(jQuery);

  NioApp.coms.docReady.push(function () {
    lineOverview();
  });

  NioApp.Calendar = function () {
    var calendarEl = document.getElementById('calendar');
    var mobileView = NioApp.Win.width < NioApp.Break.md ? true : false;
    calendar = new FullCalendar.Calendar(calendarEl, {
      timeZone: 'UTC',
      initialView: mobileView ? 'listWeek' : 'dayGridMonth',
      themeSystem: 'bootstrap',
      headerToolbar: {
        left: 'title',
        center: null,
        right: 'prev,next'
      },
      height: 600,
      contentHeight: 600,
      aspectRatio: 2,
      editable: false,
      droppable: false,
      views: {
        dayGridMonth: {
          dayMaxEventRows: 2
        }
      },
      eventClick: function (info) {
        Swal.fire({
          title: info.event.title,
          html: info.event.extendedProps.description,
        });
      },
      eventMouseEnter: function (info) {
        $(info.el).popover({
          template: '<div class="popover"><div class="arrow"></div><h3 class="popover-header"></h3><div class="popover-body"></div></div>',
          title: info.event._def.title,
          content: info.event._def.extendedProps.description,
          placement: 'top',
          html: true,
          sanitize: false
        });

        if (info.event._def.extendedProps.description) {
          $(info.el).popover('show');
        } else {
          $(info.el).popover('hide');
        }
      },
      eventMouseLeave: function (info) {
        $(info.el).popover('hide');
      },
      events: []
    });
    calendar.render();
  };

  NioApp.coms.docReady.push(NioApp.Calendar);

}(NioApp, jQuery);

var Overview = {
  dataUnit: '',
  lineTension: 0.3,
  datasets: [{
    label: "Hours of days",
    color: "#006266",
    background: NioApp.hexRGB('#006266', .10),
  }]
};

$(document).ready(function () {
  setTimeout(function () {
    $.ajax({
      url: '/fetchDashboard',
      type: 'GET',
      dataType: 'json',
      success: function (response) {
        $('.d').html(response.resume.total_presensi + " <span class='text-soft'>Day</span>");
        $('.h').html(response.resume.total_hours + " <span class='text-soft'>Hr</span>");
        $('.t1').html(response.resume.avg_waktu_masuk);
        $('.t2').html(response.resume.avg_waktu_pulang);

        if (response.chart) {
          Overview.labels = response.chart.map(item => item.ofday);
          Overview.datasets[0].data = response.chart.map(item => item.work_time_hour);
          lineOverview('.overview-chart', Overview);
        }

        if (response.eventL) {
          var events = response.eventL.map(function (colevent) {
            return {
              id: colevent.id,
              title: colevent.flag_mesg,
              start: colevent.presence_date,
              className: colevent.flag_color,
              description: colevent.desc_attend,
            };
          });

          if (calendar) {
            calendar.removeAllEvents();
            calendar.addEventSource(events);
          } else {
            console.error("Calendar is not initialized yet!");
          }
        }
      },
      error: function (xhr, status, error) {
        console.error("Error fetching calendar data: " + error);
      }
    });
  }, 500);
});