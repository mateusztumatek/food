<template>
    <div class="w-100">
        <stats-component></stats-component>
        <div class="row">
            <div class="col-md-8">
                <orders-chart></orders-chart>
            </div>
            <div class="col-md-4">
                <product-chart></product-chart>
            </div>
            <div class="col-md-6">
                <v-card>
                    <v-card-title>Ostatnie zamówienia</v-card-title>
                    <v-card-text>
                        <div class="empty-state" style="min-height: 200px">Do zrobienia</div>
                    </v-card-text>
                </v-card>
            </div>
            <div class="col-md-6">
                <last-clients></last-clients>
            </div>
        </div>
    </div>
</template>
<script>
    import ProductChart from './product-chart';
    import OrdersChart from './orders-chart';
    import StatsComponent from './stats-component'
    import LastClients from './last-clients'
    export default {
        components:{StatsComponent, ProductChart, OrdersChart, LastClients},
        data() {
            return{

            }
        },
    }
</script>
<style>
    .v-sheet--offset {
        top: -24px;
        position: relative;
    }
</style>