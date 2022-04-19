
function drawTables() {
  const charts = document.querySelectorAll(".chart");


  try {
    sessionStorage.getItem("productInfoTableData");
    sessionStorage.getItem("productInfoMonthLabels");
  }
  catch (e) {
    console.log(e + "Error, using default data preset");
    sessionStorage.setItem("productInfoTableData", "[12,19,3,5,2,3,12,19,3,5,2,3]");
    sessionStorage.setItem("productInfoMonthLabels", '["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"]');
  }

  const monthSessionData = sessionStorage.getItem("productInfoMonthLabels").split(',');
  var monthArray = [];
  for (var i = 0; i < 12; i++) {
    monthArray.push(monthSessionData[i]);
  }

  charts.forEach(function (chart) {
    var ctx = chart.getContext("2d");
    var myChart = new Chart(ctx, {
      type: "bar",
      data: {
        labels: monthArray,
        datasets: [
          {
            label: "Number of Orders",
            data: JSON.parse(sessionStorage.getItem("productInfoTableData")),
            backgroundColor: [

              "rgba(247, 253, 34, 0.7)",
              "rgba(250, 187, 0, 0.7)",
              "rgba(250, 158, 0, 0.7)",
              "rgba(75, 192, 192, 0.7)",
              "rgba(153, 102, 255, 0.7)",
              "rgba(255, 159, 64, 0.7)",
              "rgba(255, 99, 132, 0.7)",
              "rgba(54, 162, 235, 0.7)",
              "rgba(124, 94, 163, 0.7)",
              "rgba(122, 244, 199, 0.7)",
              "rgba(145, 8, 83, 0.7)",
              "rgba(29, 71, 36, 0.7)",
            ],
            borderColor: [
              "rgba(28, 12, 8, 1)"
            ],
            borderWidth: 1,
          },
        ],
      },
      options: {
        scales: {
          y: {
            beginAtZero: true,
          },
        },
      },
    });
  });

  $(document).ready(function () {
    $(".data-table").each(function (_, table) {
      $(table).DataTable();
    });
  });
}

