// document.addEventListener('DOMContentLoaded', function() {
//     var chartElement = document.getElementById("kt_project_list_chart");

//     if (chartElement) {
//         var ctx = chartElement.getContext("2d");

//         new Chart(ctx, {
//             type: "doughnut",
//             data: {
//                 datasets: [{
//                     data: [30, 45, 25],
//                     backgroundColor: ["#00A3FF", "#50CD89", "#FF5757"]
//                 }],
//                 labels: ["Active", "Completed", "Not Active"]
//             },
//             options: {
//                 chart: {
//                     fontFamily: "inherit"
//                 },
//                 borderWidth: 0,
//                 cutout: "75%",
//                 cutoutPercentage: 65,
//                 responsive: true,
//                 maintainAspectRatio: false,
//                 title: {
//                     display: false
//                 },
//                 animation: {
//                     animateScale: true,
//                     animateRotate: true
//                 },
//                 stroke: {
//                     width: 0
//                 },
//                 tooltips: {
//                     enabled: true,
//                     intersect: false,
//                     mode: "nearest",
//                     bodySpacing: 5,
//                     yPadding: 10,
//                     xPadding: 10,
//                     caretPadding: 0,
//                     displayColors: false,
//                     backgroundColor: "#20D489",
//                     titleFontColor: "#ffffff",
//                     cornerRadius: 4,
//                     footerSpacing: 0,
//                     titleSpacing: 0
//                 },
//                 plugins: {
//                     legend: {
//                         display: false
//                     }
//                 }
//             }
//         });
//     } else {
//         console.error("Could not find element with id 'kt_project_list_chart'");
//     }
// });
