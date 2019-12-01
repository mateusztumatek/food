<template>
    <div class="w-100">
        <v-data-table
                v-if="orders && orders.data"
                v-model="selected"
                :headers="headers"
                :items="orders.data"
                :expanded.sync="expanded"
                show-expand
                :loading="loading"
                item-key="id"
                show-select
                class="elevation-1"
        >
            <template v-slot:top>
                <table-header @change="updateParams"></table-header>
            </template>
            <template v-slot:item.amount="{item}">
                <span>{{item.amount | currency()}} PLN</span>
            </template>
            <template v-slot:item.items_count="{item}">
                <span>{{item.order_items.length}}</span>
            </template>
            <template v-slot:item.paid="{item}">
                <v-chip :color="(item.paid)? 'green' : 'red'"><span v-if="item.paid">Tak</span><span v-else>Nie</span></v-chip>
            </template>
            <template v-slot:item.status="{item}">
                <span><v-chip :color="getStatusColor(item.status)">{{item.status}}</v-chip></span>
            </template>
            <template v-slot:expanded-item="{ item }">
                <td :colspan="7">
                    <v-list>
                        <v-list-item :href="'/products?product_id='+product.item.id" class="my-2" v-for="product in item.order_items">
                            <v-list-item-avatar tile>
                                <img
                                        :src="$root.getSrc(product.item.image)"
                                        alt="John"
                                >
                            </v-list-item-avatar>
                            <v-list-item-content>
                                 <span class="ml-2">
                            {{product.item.name}}
                                <p class="text-muted">{{product.comment}}</p>
                            </span>
                            </v-list-item-content>

                            <v-list-item-action>
                                <div style="border: 2px solid white; padding: 12px">x{{product.quantity}}</div>
                            </v-list-item-action>
                        </v-list-item>
                        <v-list-item v-if="item.comment">Komentarz do zamówienia: {{item.comment}}</v-list-item>
                    </v-list>
                </td>
            </template>
        </v-data-table>
        <div class="row mx-0" v-if="selected && selected.length > 0">
            <div class="col-md-12">
                <v-select v-model="update.status" :items="['new', 'canceled', 'complete']" label="Status zamówień"></v-select>
            </div>
            <div class="col-md-12">
                <v-switch v-model="update.paid" label="Opłacone"></v-switch>
            </div>
            <div clas="col-md-12">
                <v-btn @click="updateOrders()" width="100%" color="primary">Zapisz</v-btn>
            </div>
        </div>
    </div>
</template>
<script>
    import {getCustomersOrders} from "../../api/order";
    import TableHeader from '../../components/table-header';
    export default {
        components:{TableHeader},
        data(){
            return{
                loading: false,
                params:{},
                selected: [],
                orders: null,
                expanded: [],
                update:{},
                headers: [
                    {
                        text: 'ID',
                        align: 'left',
                        sortable: false,
                        value: 'local_id',
                    },
                    { text: 'Miejsce', value: 'place.name' },
                    { text: 'Sprzedaż', value: 'sale.name' },
                    { text: 'Koszt zamówienia', value: 'amount' },
                    { text: 'Pozycji w zamówieniu', value: 'items_count'},
                    { text: 'Opłacone', value: 'paid'},
                    { text: 'Status', value: 'status'},
                    { text: '', value: 'data-table-expand' },

                    /*{ text: 'Iron (%)', value: 'iron' },*/
                ],
            }
        },
        mounted(){
            this.getOrders();
        },
        methods:{
            getOrders(){
                this.loading = true;
                getCustomersOrders(this.params).then(response => {this.orders = response; this.loading = false}).catch(e => {
                    this.loading = false;
                    this.$store.commit('app/ADD_ERROR', {text: e.response.data.message});
                })
            },
            updateOrders(){
                
            },
            updateParams(params){
                this.params = params;
                this.getOrders();
            }
        }
    }
</script>