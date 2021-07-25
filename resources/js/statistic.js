import Chart from '../../node_modules/chart.js/auto';
import axios from 'axios';
var chart1HTML = document.getElementById('chart1');
var chart2HTML = document.getElementById('chart2');
var chart3HTML = document.getElementById('chart3');
var chart1Text = document.getElementById('chart1Text');
var chart2Text = document.getElementById('chart2Text');
var chart3Text = document.getElementById('chart3Text');
let token = document.querySelector('meta[name="api-token"]').content;
let updateBtn = document.querySelector('#time-period-button');
updateBtn.addEventListener('click', () => {
    let timePeriodSelect = document.querySelector('#time-period-select'); 
    let currentSelectedTimePeriod  = timePeriodSelect.value;
    viewAllCharts(currentSelectedTimePeriod);
});

/**
 * 1. Log Masuk Mengikut Peranan
 * - Admin
 * - Student
 * - Lecturer
 * 2. Log Masuk Mengikut Pelayar Web
 * - Mozilla Firefox
 * - Google Chrome
 * - Safari
 * 3. Log Masuk Mengikut Sistem Pengoperasian
 * -ChromeOS
 * -macOS
 * -iOS
 * -Windows
 * -Android
 */

const chart1Data = {
    datasets: [{
        data: [0, 0, 0],
        backgroundColor: [
            'rgb(0, 0, 0)',
            'rgb(33, 37, 41)',
            'rgb(52, 58, 64)',
        ]
    }],

    // These labels appear in the legend and in the tooltips when hovering different arcs
    labels: [
        'Pelajar',
        'Pensyarah',
        'Admin'
    ]
}

const chart1Config = {
    type: 'doughnut',
    data: chart1Data,
    options: {
        responsive: true,
        plugins: {
          legend: {
            position: 'top',
          },
          title: {
            display: true,
            text: 'Log Masuk Mengikut Peranan'
          }
        }
      }
}


// Update the chart
function updateChartData(chart, data) {
    chart.data.datasets[0].data = data;
    chart.update();
}

var chart1Chart = new Chart(chart1HTML, chart1Config);
viewAllCharts('today');
function viewAllCharts(timePeriod){
    
    async function getTotal(period, role){
        try {
          const response = await axios({
            method: 'get',
            url: '/api/statistic/login/' + period,
            responseType: 'json',
            headers: {'Authorization': 'Bearer ' + token},
          });
          return response;
        } catch (error) {
          console.error(error);
        }
    }
    switch(timePeriod) {
        case "today":
            getTotal('day').then(function (response) {
                let data = response.data;
                let total = data.total;
                let admin = data.admin;
                let lecturer = data.lecturer;
                let student = data.student;
                updateChartData(chart1Chart, [student, lecturer, admin]);
                chart1Total.innerText = 'Jumlah: ' + total;
            });

            // chart2Text.innerText = 'Log Masuk Mengikut Pelayar Web';
            // chart2Total.innerText = 'Jumlah: ';
            // var chart2Chart = new Chart(chart2HTML, {
            //     type: 'doughnut',
            //     data: {
            //         datasets: [{
            //             label: 'Jumlah Log Masuk',
            //             data: [10, 20, 30],
            //             backgroundColor: [
            //                 'rgb(0, 0, 0)',
            //                 'rgb(33, 37, 41)',
            //                 'rgb(52, 58, 64)',
            //             ]
            //         }],
                
            //         // These labels appear in the legend and in the tooltips when hovering different arcs
            //         labels: [
            //             'Mozilla Firefox',
            //             'Google Chrome',
            //             'Safari'
            //         ]
            //     },
            //     options: {
            //     }
            // });
            
            // chart3Text.innerText = 'Log Masuk Mengikut Sistem Pengoperasian';
            // chart3Total.innerText = 'Jumlah: ';
            // var chart3Chart = new Chart(chart3HTML, {
            //     type: 'doughnut',
            //     data: {
            //         datasets: [{
            //             label: 'Jumlah Log Masuk',
            //             data: [10, 20, 30, 40, 50],
            //             backgroundColor: [
            //                 'rgb(0, 0, 0)',
            //                 'rgb(33, 37, 41)',
            //                 'rgb(52, 58, 64)',
            //                 'rgb(73, 80, 87)',
            //                 'rgb(108, 117, 125)'
            //             ]
            //         }],
                
            //         // These labels appear in the legend and in the tooltips when hovering different arcs
            //         labels: [
            //             'ChromeOS',
            //             'macOS',
            //             'iOS',
            //             'Windows',
            //             'Android'
            //         ]
            //     },
            //     options: {
            //     }
            // });
            break;
        case "week":
            // getTotal('week', 'student').then(function (response) {
            //     let totalStudent = response.data.count;
            //     getTotal('week', 'lecturer').then(function (response) {
            //         let totalLecturer = response.data.count;
            //         getTotal('week', 'admin').then(function (response) {
            //             let totalAdmin = response.data.count;
            //             let totalRole = totalStudent + totalLecturer + totalAdmin;
            //             chart1Total.innerText = 'Jumlah: ' + totalRole;
            //             console.log('Jumlah Hari Ini:' + totalRole);
            //             let data = [totalStudent, totalLecturer, totalAdmin];
            //             console.log(data);
            //             updateChartData(chart1Chart, data);
            //         });
            //     });
            // });
            break;
        case "month":
            getTotal('month').then(function (response) {
                let data = response.data;
                let total = data.total;
                let admin = data.admin;
                let lecturer = data.lecturer;
                let student = data.student;
                updateChartData(chart1Chart, [student, lecturer, admin]);
                chart1Total.innerText = 'Jumlah: ' + total;
            });
            break;
        case "year":
            getTotal('year').then(function (response) {
                let data = response.data;
                let total = data.total;
                let admin = data.admin;
                let lecturer = data.lecturer;
                let student = data.student;
                updateChartData(chart1Chart, [student, lecturer, admin]);
                chart1Total.innerText = 'Jumlah: ' + total;
            });
            break;
        default:
            break;
    }
}