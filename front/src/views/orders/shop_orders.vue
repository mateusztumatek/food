<template>
    <div class="w-100">
        <v-data-table
                v-if="orders && orders.data"
                v-model="selected"
                :headers="headers"
                :items="orders.data"
                :expanded.sync="expanded"
                show-expand
                item-key="id"
                show-select
                class="elevation-1"
        >
            <template v-slot:top>
                <table-header @change="(event) => {this.params = event}"></table-header>
            </template>
            <template v-slot:item.amount="{item}">
                <span>{{item.amount | currency()}} PLN</span>
            </template>
            <template v-slot:item.items_count="{item}">
                <span>{{item.order_items.length}}</span>
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
    </div>
</template>
<script>
    import {getCustomersOrders} from "../../api/order";
    import TableHeader from '../../components/table-header';
    export default {
        components:{TableHeader},
        data(){
            return{
                params:{},
                selected: [],
                orders: null,
                expanded: [],
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
                getCustomersOrders().then(response => {this.orders = response}).catch(e => {
                    this.$store.commit('app/ADD_ERROR', {text: e.response.data.message});
                })
            }
        }
    }
</script>