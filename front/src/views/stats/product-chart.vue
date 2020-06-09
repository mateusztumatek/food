<template>
    <div>
        <v-card style="overflow: hidden; height: 100%" :loading="loading">
            <v-card-title>Popularoność produktów</v-card-title>
            <v-card-text>
                <p class="text-muted" style="font-size: 0.8rem"><span v-if="params.date && params.date.from">od {{params.date.from}}</span><span v-if="params.date && params.date.to"> do {{params.date.to}}</span></p>
                <!--<v-btn :outlined="tab != 'sales'" solo tile color="primary" @click="setTab('sales')">Sprzedaż</v-btn>
                <v-btn :outlined="tab != 'views'" solo tile color="primary" @click="setTab('views')" class="ml-2">Wyświetleń</v-btn>-->
                <div class="w-100 d-flex" style="justify-content: center">
                    <div id="chart" class="mt-4">
                        <apexchart v-if="chartOptions.labels.length > 0 && series.length > 0" type=pie width=400 :options="chartOptions" :series="series" />
                    </div>
                </div>
            </v-card-text>
            <v-divider></v-divider>
            <v-card-actions>
                <v-menu bottom left v-if="params.date">
                    <template v-slot:activator="{ on }">
                        <v-btn
                                dark
                                icon
                                v-on="on"
                        >
                            <v-icon>mdi-dots-vertical</v-icon>
                        </v-btn>
                    </template>
                    <v-date-picker v-model="params.date.from" @input="getChart()"></v-date-picker>
                    <v-date-picker v-model="params.date.to" @input="getChart()"></v-date-picker>
                </v-menu>
            </v-card-actions>
        </v-card>
    </div>
</template>
<script>
    import {getProductsChart} from "../../api/stats";

    export default {
        data(){
            return{
                loading: false,
                series: [],
                tab: 'sales',
                chartOptions: {
                    labels: [],
                    dataLabels: {
                        enabled: false
                    },
                    theme: {
                        mode: 'dark',
                        palette: 'palette1',
                    },
                    legend:{
                        position: 'bottom'
                    },
                    responsive: [{
                        breakpoint: 480,
                        options: {
                            chart: {
                                width: 400
                            },
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }]
                },
                params: {}
            }
        },
        mounted(){
           /* var date = {}
            date.from = new Date(new Date().setMonth(new Date().getMonth() - 1)).getTime();
            date.to = new Date().getTime();
            this.$set(this.params, 'date', date);*/
           this.params.date = {};
          this.getChart();
        },
        methods:{
            setTab(tab){
                this.tab = tab;
                this.getChart();
            },
            getChart(){
                this.series = [];
                this.chartOptions.labels = [];
                this.loading = true;
                var params = {tab: this.tab};
                if(this.params.date && this.params.date.from ){
                    params.from = this.params.date.from;
                }
                if(this.params.date && this.params.date.to){
                    params.to = this.params.date.to;
                }
                getProductsChart(params).then(response => {
                    this.$set(this.chartOptions, 'labels', response.labels);
                    this.series = response.products;
                    this.loading = false;
                }).catch(e => {this.loading = false;})
            }
        }
    }
</script>