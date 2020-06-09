<template>
    <div class="w-100">
        <div class="row">
            <div class="col-md-3" v-for="stat in stats">
                <v-card
                        :loading="stat.loading"
                        class="mt-4 mx-auto"
                        max-width="400"
                >
                    <v-sheet
                            class="v-sheet--offset mx-auto"
                            color="cyan"
                            elevation="12"
                            max-width="calc(100% - 32px)"
                    >
                        <div class="py-4 text-center">
                            <v-icon style="font-size: 40px">{{stat.icon}}</v-icon>
                            <span>{{stat.name}}</span>
                        </div>
                    </v-sheet>

                    <v-card-text class="pt-0">
                        <div class="display-2 font-weight-bold grey--text">{{stat.value}}</div>
                        <v-divider class="my-2"></v-divider>
                        <div class="d-flex" style="justify-content: space-between">
                            <div>
                                <v-icon
                                        class="mr-2"
                                        small
                                >
                                    mdi-clock
                                </v-icon>
                                <span class="caption grey--text font-weight-light">{{stat.date.from}} - {{stat.date.to}}</span>
                            </div>
                            <v-menu bottom left>
                                <template v-slot:activator="{ on }">
                                    <v-btn
                                            dark
                                            icon
                                            v-on="on"
                                    >
                                        <v-icon>mdi-dots-vertical</v-icon>
                                    </v-btn>
                                </template>
                                <v-date-picker v-model="stat.date.from" @input="getStat(stat.handler, stat)"></v-date-picker>
                                <v-date-picker v-model="stat.date.to" @input="getStat(stat.handler, stat)"></v-date-picker>
                            </v-menu>
                        </div>
                    </v-card-text>
                </v-card>
            </div>
        </div>
    </div>
</template>
<script>
    import {getStats} from "../../api/stats";
    import Vue from 'vue'

    export default {
        data(){
            return{
                stats:[
                    {
                        name: 'Zamówień',
                        loading: true,
                        icon: 'mdi-cart-outline',
                        value: '0',
                        handler: 'orders',
                        date: {
                            from: '2019-08-21',
                            to: '2019-09-21'
                        },
                    },
                    {
                        name: 'Zysk',
                        loading: true,
                        handler: 'profit',
                        icon: 'mdi-cash-usd-outline',
                        value: '0',
                        date: {
                            from: '2019-08-21',
                            to: '2019-09-21'
                        },
                    },
                    {
                        name: 'Produktów',
                        loading: true,
                        icon: 'mdi-package-variant-closed',
                        value: '0',
                        handler: 'products',
                        date: {
                            from: '2019-08-21',
                            to: '2019-09-21'
                        },
                    },
                    {
                        name: 'Wejść na sklep',
                        loading: true,
                        icon: 'mdi-chart-timeline-variant',
                        value: '0',
                        handler: 'shop_enter',
                        date: {
                            from: '2019-08-21',
                            to: '2019-09-21'
                        },
                    }
                ]
            }
        },
        mounted() {
            this.getAll();
        },
        methods:{
            getAll(){
                getStats().then(response => {
                    var orders = this.stats[this.stats.findIndex(x => x.name == 'Zamówień')];
                    orders.value = response.orders;
                    orders.loading = false;
                    var profit = this.stats[this.stats.findIndex(x => x.name == 'Zysk')];
                    profit.value = Vue.filter('currency')(response.profit) + ' PLN';
                    profit.loading = false;
                    var products = this.stats[this.stats.findIndex(x => x.name == 'Produktów')];
                    products.value = response.products;
                    products.loading = false;
                    var shop_enter = this.stats[this.stats.findIndex(x => x.name == 'Wejść na sklep')];
                    shop_enter.value = response.shop_enter;
                    shop_enter.loading = false;
                    this.stats.forEach(item => {
                        item.date.from = response.date_from;
                        item.date.to = response.date_to;
                    })
                })
            },
            getStat(type, item){
                item.loading = true;
                var params = {date_from: item.date.from, date_to: item.date.to, type: item.handler};
                switch (type) {
                    case 'orders':
                        getStats(params).then(response => {
                            item.value = response.orders;
                            item.loading = false;
                        })
                        break;
                    case 'profit':
                        getStats(params).then(response => {
                            item.value = Vue.filter('currency')(response.profit) + ' PLN';;
                            item.loading = false;
                        })
                        break;
                    case 'products':
                        getStats(params).then(response => {
                            item.value = response.products;
                            item.loading = false;
                        })
                        break;
                    case 'shop_enter':
                        getStats(params).then(response => {
                            item.value = response.shop_enter;
                            item.loading = false;
                        })
                        break;
                }
            }
        }
    }
</script>