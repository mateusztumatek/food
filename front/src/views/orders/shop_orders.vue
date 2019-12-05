<template>
    <div class="w-100">
        <v-data-table
                v-model="selected"
                :headers="headers"
                :items="orders.data"
                :expanded.sync="expanded"
                :options.sync="pagination"
                :server-items-length="orders.total"
                :footer-props="{'items-per-page-options': [10,20,50]}"
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
            <div v-for="e in errors" class="col-md-12">
                <v-alert type="error" v-for="error in e" class="col-md-12">{{error}}</v-alert>
            </div>
            <div clas="col-md-12">
                <v-btn @click="updateOrders()" width="100%" color="primary">Zapisz</v-btn>
            </div>
        </div>
    </div>
</template>
<script>
    import {getCustomersOrders, updateOrder} from "../../api/order";
    import TableHeader from '../../components/table-header';
    export default {
        components:{TableHeader},
        data(){
            return{
                timeout: false,
                pagination: {},
                loading: false,
                params:{},
                selected: [],
                orders: {},
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
        watch:{
          pagination:{
              deep: true,
              handler: function(){
                  if(this.timeout){
                      this.getOrders();
                  }
              }
          }
        },
        mounted(){
            this.getOrders();
            setTimeout(() => {
                this.timeout = true;
            },700)
        },
        methods:{
            getOrders(){
                this.loading = true;
                var params = {...this.params, page: this.pagination.page, perPage: this.pagination.itemsPerPage};
                getCustomersOrders(params).then(response => {this.orders = response; this.loading = false}).catch(e => {
                    this.loading = false;
                    this.$store.commit('app/ADD_ERROR', {text: e.response.data.message});
                })
            },
            updateOrders(){
                this.selected.forEach((item) => {
                    item.status = this.update.status;
                    if(this.update.paid){
                        item.paid = true;
                    }else item.paid = false;
                    updateOrder(item.id, item).then(response => {

                    }).catch(e => {
                        this.$store.commit('app/ADD_ERROR', {text: 'Nie udało się zaktualizować zamówień'});
                        this.errors = e.response.data.errors;
                        this.resetErrors();
                    })
                })

            },
            updateParams(params){
                this.params = params;
                this.pagination.page = 1;
                this.getOrders();
            }
        }
    }
</script>