$(document).ready(function(){
	//show_list();
		grafica();
    grafica2();
	//grafica3();
});
function grafica (){
	$.ajax({
    url: "grafica.php",
    method: "POST",
    success: function(data)
    {
        //var mes = [];
        var total = [];
        var nombre = [];
        var obj = jQuery.parseJSON(data);

        for(var i in obj)
        {
            //mes.push(obj[i].mes);
            total.push(obj[i].total);
            nombre.push(obj[i].nombre);
      }

        var chartdata =
        {
            labels: nombre,
            datasets : [
                {
                   label: 'Cantidad',
                   backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1.2,
                    data: total,
                }
            ]
        };

            var ctx = $("#myChart");

            var barGraph = new Chart(ctx, {
                type: 'bar',
                data: chartdata,
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero:true
                            }
                        }]
                    },
                    /*title: {
                        display: true,
                        text: 'Examenes mas Vendidos'
											},*/
                     responsive: true,
                    },
            });
        },
        error: function(data) {
            console.log(data);
        }
    });
}
function grafica3(){
    $.ajax({
    url: "grafica1.php",
    method: "POST",
    success: function(data)
    {
        var datoss = [];
        var obj = jQuery.parseJSON(data);

        for(var i in obj)
        {
            datoss.push(obj[i].Canceladas);
            datoss.push(obj[i].Finalizadas);
        }
        var chartdata =
        {
            labels: ["Citas","Consultas"],
            datasets : [
                {
                   backgroundColor: [
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(153, 102, 255, 0.2)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    //backgroundColor:'rgba(54, 162, 235, 0.2)',
                    //borderColor:'rgba(54, 162, 235, 1)',
                    borderWidth: 1.2,
                    data: datoss,
                }
            ]
        };

            var ctx = $("#myChar");

               var myDoughnutChart = new Chart(ctx, {
                    type: 'pie',
                    data: chartdata,
                    options: {
                     responsive: true,
                    }
                });

        },
        error: function(data) {
            console.log(data);
        }
    });
}
function grafica2(){
    var mes = [];
    var total = [];
    var total1 = [];
    $.ajax({
        url: "grafica1.php",
        method: "POST",
        success: function(data)
        {
            var obj = jQuery.parseJSON(data);

            for(var i in obj)
            {
                mes.push(obj[i].mes);
                total.push(obj[i].total);
            }
            var chartdata =
            {
                labels: mes,
                datasets : [
                    {
                       label: 'Numero de examenes',
                        backgroundColor:'rgba(75, 192, 192, 0.2)',
                        borderColor:'rgba(75, 192, 192, 1)',
                        borderWidth: 1.2,
                        data: total,
                    }
                    /*{
                        label: 'EGRESOS',
                        backgroundColor:'rgba(255, 99, 132, 0.2)',
                        borderColor:'rgba(255,99,132,1)',
                        //backgroundColor:'rgba(54, 162, 235, 0.2)',
                        //borderColor:'rgba(54, 162, 235, 1)',
                        borderWidth: 1.2,
                        data: total1,
                    }*/
                ]
            };
            var ctx = $("#myChart1");
            var barGraph = new Chart(ctx, {
                type: 'line',
                data: chartdata,
                options: {
                    //title: {
                        //display: true,
                        //text: ' POR MES'
                        //},
                     responsive: true,
                    }
            });
        },
        error: function(data) {
            console.log(data);
        }
    });
}
