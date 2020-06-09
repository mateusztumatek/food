<template>
    <v-card style="overflow: hidden; height: 100%" :loading="loading">
        <v-card-title>Ilość zamówień</v-card-title>
        <div id="chart">
            <apexchart type=area height=350 :options="chartOptions" :series="series" />
        </div>
    </v-card>
</template>
<script>
    import {getStatsCharts} from "../../api/stats";

    export default {
        data(){
            return{
                series: [],
                chartOptions: {
                    tooltip: {
                        enabled: true,
                        enabledOnSeries: undefined,
                        shared: true,
                        followCursor: false,
                        intersect: false,
                        inverseOrder: false,
                        custom: undefined,
                        fillSeriesColor: true,
                        theme: 'dark',
                        style: {
                            fontSize: '12px',
                            fontFamily: undefined
                        },
                        onDatasetHover: {
                            highlightDataSeries: false,
                        },
                        x: {
                            show: true,
                            format: 'dd MMM',
                            formatter: undefined,
                        },
                        y: {
                            formatter: undefined,
                        },
                        z: {
                            formatter: undefined,
                            title: 'Size: '
                        },
                        marker: {
                            show: true,
                        },
                        items: {
                            display: 'flex',
                        },
                        fixed: {
                            enabled: false,
                            position: 'topRight',
                            offsetX: 0,
                            offsetY: 0,
                        },
                    },

                    chart: {
                        foreColor: '#F6F8F9',
                        toolbar: {
                            show: true,
                            tools: {
                                download: true,
                                selection: true,
                                zoom: true,
                                zoomin: true,
                                zoomout: true,
                                pan: false,
                                reset: false | '<img src="/static/icons/reset.png" width="20">',
                                customIcons: []
                            },
                            autoSelected: 'zoom'
                        },
                        background: '#424242',

                        zoom: {
                            enabled: true
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        curve: 'smooth'
                    },
                    xaxis: {
                        type: 'datetime',
                        categories: [],
                    },
                },
                loading: false,
                params:{}
            }
        },
        mounted() {
            this.getChart();
        },
        methods:{
            getChart(){
                this.loading = true;
                getStatsCharts(this.params).then(response => {
                    this.series = [];
                    this.series.push({
                        name: 'Zamówienia zrealizowane',
                        data: response.order_completed
                    });
                    this.series.push({
                        name: 'Zamówienia anulowane',
                        data: response.order_canceled
                    });
                    this.chartOptions.xaxis.categories = response.categories;
                    this.loading = false;
                })
            }
        }
    }
</script>