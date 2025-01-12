<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<!-- optional -->
<script src="https://code.highcharts.com/modules/offline-exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>

<script type="text/javascript">
    var site = '<?php echo site_url(); ?>';
    var year = new Date().getFullYear();
    var month, currDate, momentDate, weekNumber;

    $(document).ready(function() {
        // DEFINE NEW DATE
        var d = new Date();
        console.log(d)
        month = d.getMonth() + 1;

        var day = d.getDate();


        currDate = d.getFullYear() + '-' +
            (month < 10 ? '0' : '') + month + '-' +
            (day < 10 ? '0' : '') + day;


        moment.locale('en', {
            week: {
                dow: 1
            } // Monday is the first day of the week
        });

        // momentDate = moment(currDate, "YYYY-MM-DD").day(1).format("YYYY/MM/DD");
        renderChart(year);
    });




    $('#btnDetail').datepicker({
        format: "yyyy",
        viewMode: "years",
        minViewMode: "years"
    }).on('changeDate', function(ev) {
        currDate = ev.format();
        $('#btnDetail').html(currDate);
        data = currDate;

        console.log(data);

        var myarr = data.split("-");
        var month = myarr[0];
        var year = myarr[1];

        // console.log(month, year)

        renderChart(data);
    });


    function renderChart(data) {

        $('#container').empty();

        $.ajax({
            url: site + 'api/pangan/data/' + data,
            type: "GET",
            success: function(data) {
                var container = document.getElementById('container');
                if (data.length === 0) {
                    $('#container').append(`<div class="col-xs-12 col-sm-12"> 
                                <div class="panel">
                                        <p>No Data Available</p>
                                </div> 
                            </div>`);
                } else {

                    $('#container').append(`<div class="col-xs-12 col-sm-12"> 
                                <div class="panel">
                                    <div class="panel-body collapse in" >
                                        <div id="chart"> </div> 
                                    </div> 
                                </div> 
                            </div>`);
                    var dataseries = [];
                    $.each(data.dataseries, function(key, item) {
                        $.each(item, function(kakey, kaitem) {

                            $.each(kaitem, function(jekey, jeitem) {
                                dataseries.push({
                                    'data': jeitem,
                                    'name': key + ' ' + kakey + ' ' + jekey
                                });
                            });
                        });
                    });
                    const chart = Highcharts.chart('chart', {
                        chart: {
                            type: 'line',
                            height: (9 / 16 * 100) + '%' // 16:9 ratio
                        },
                        title: {
                            text: 'Perkembangan Harga Bahan Pangan'
                        },
                        xAxis: {
                            categories: data.categories
                        },
                        yAxis: {
                            title: {
                                text: 'Harga Bahan Pokok (Rupiah)'
                            }
                        },
                        exporting: {
                            enabled: true
                        },
                        series: dataseries,
                    });
                }
            },
        });
    }
</script>