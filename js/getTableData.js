function getTableData(url) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 & this.status == 200) {
            const arrayData = this.responseText.split('#');
            sessionStorage.setItem("productInfoTableData",arrayData[0]);

            var monthArray =[];
            for(var i=1;i<13;i++){
                monthArray.push(arrayData[i]);
            }
            sessionStorage.setItem("productInfoMonthLabels",monthArray);
            document.getElementById("uniqueChartSpinner").className="d-none";
            drawTables();
        }
    };
    xmlhttp.open("GET", url, true);
    xmlhttp.send();
}
