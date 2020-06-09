<template>
    <div class="w-100" v-if="sellout.id">
        <h1 class="display-1 mb-5">Zamówienia</h1>
        <div class="empty-state" v-if="orders == 0">
            <img src="/img/28518.png">
            <p class="display-1">W tym momencie nie ma żadnych zamówień</p>
        </div>
        <v-text-field label="szukaj zamówienia" prepend-icon="mdi-magnify"></v-text-field>
        <v-checkbox v-model="params.new" @change="getOrders()" label="Pokaż tylko nowe zamówienia"></v-checkbox>
        <v-expansion-panels>
            <transition-group name="list"  class="w-100">
                <v-expansion-panel
                        v-for="(order,i) in sorted"
                        :key="i"
                        v-bind:class="{'paid': order.paid}"
                >
                    <v-expansion-panel-header ><span class="display-1">#{{order.local_id}}</span>
                        <v-progress-linear
                                class="ml-2"
                                v-if="order.status == 'new' && order.timeleft && order.timeleft.percentage"
                                v-model="order.timeleft.percentage"
                                height="15"
                                color="red"
                        ></v-progress-linear>
                    </v-expansion-panel-header>
                    <v-expansion-panel-content>
                        <div style="padding: 20px 0px">
                            Zakupionych przedmiotów: 12
                            <div class="col-md-12">
                                <v-chip class="mr-2" color="primary">{{order.amount | currency}} PLN</v-chip> <v-chip color="primary">status {{order.status}}</v-chip>
                            </div>
                            <v-list>
                                <v-list-item style="border-bottom: 1px solid rgba(255,255,255,0.3)" v-for="item in order.order_items">
                                    <v-list-item-avatar tile size="100">
                                        <v-img  :src="getSrc(item.item.image)"></v-img>
                                    </v-list-item-avatar>
                                    <v-list-item-content>
                                        <v-list-item-title>{{item.item.name}}</v-list-item-title>
                                    </v-list-item-content>
                                    <v-list-item-action>
                                        <div style="border: 2px solid white; padding: 24px">x{{item.quantity}}</div>
                                    </v-list-item-action>
                                </v-list-item>
                                <v-divider></v-divider>
                                <v-list-item class="mt-4" style="flex-wrap: wrap">
                                    <v-btn :loading="loading" color="primary" class="ma-1" @click="editItem = order; editItem.editType = 'status'">Zmień status zamówienia</v-btn>
                                    <v-btn :loading="loading" :color="(order.paid)? 'red': 'primary'" class="ma-1" @click="markPaid(order)"><span v-if="order.paid">Oznacz jako nieopłacone</span><span v-else>Oznacz jako opłacone</span></v-btn>
                                    <v-btn color="primary" :loading="loading" class="ma-1" @click="editItem = order; editItem.editType = 'time'">Wydłuż czas realizacji</v-btn>
                                </v-list-item>
                            </v-list>
                        </div>
                    </v-expansion-panel-content>
                </v-expansion-panel>
            </transition-group>
        </v-expansion-panels>
        <infinite-loading  @infinite="infiniteHandler" v-if="orders && orders.current_page < orders.last_page"></infinite-loading>

        <v-dialog  @click:outside="editItem = null" :value="(editItem && editItem.id)? true : false" :width="($root.isMobile())? '90%' : '50%'">
            <v-card v-if="editItem && editItem.id && editItem.editType == 'time'">
                <v-card-title>Wydłuż czas realizacji zamówienia</v-card-title>
                <v-card-text style="height: 300px;">
                    <v-btn @click="setTime(editItem, time)" v-for="time in getOrderTimeStamps(editItem)" color="primary" class="w-100 mb-2" style="font-size: 2rem" outlined solo tile min-height="60">{{time}}</v-btn>
                </v-card-text>
            </v-card>
            <v-card v-if="editItem && editItem.id && editItem.editType == 'status'">
                <v-card-title>Wydłuż czas realizacji zamówienia</v-card-title>
                <v-card-text style="height: 300px;">
                    <v-btn @click="editStatus(editItem, status.value)" v-for="status in statuses" color="primary" class="w-100 mb-2" style="font-size: 2rem" outlined solo tile min-height="60">{{status.name}}</v-btn>
                </v-card-text>
            </v-card>
        </v-dialog>

        <v-btn class="w-100 mt-5" :to="'/sellout/'+sellout.id">Przejdź do sprzedaży</v-btn>
    </div>
</template>
<script>
    import {manageSellout} from "../../../api/sellout";
    import {getSelloutOrders, getOrder} from "../../../api/order";
    import {processPercentageTimeleft} from "../../../utilis/helper";
    import moment from 'moment-timezone';
    export default {
        beforeRouteLeave (to, from, next) {
           this.$store.commit('sellout/SET_ACTIVE_SELLOUT', null);
           next();
        },
        data(){
            return{
                editItem:null,
                sellout:{},
                orders: [],
                params:{new: true},
                loading: false,
                statuses: [{name: 'Nowy', value:'new'}, {name: 'Zrealizowany', value: 'completed'}, {name: 'Anulowane', value: 'canceled'}],
            }
        },
        computed:{
            sorted(){
                if(this.orders && this.orders.data){
                    return this.orders.data.filter((x) => {
                        if(this.params.new){
                            return x.status == 'new';
                        }else{
                            return true
                        }
                    })
                }else{return []}
            }
        },
        mounted() {
            this.getSellout();
            this.getOrders();
            Echo.channel('SaleChannel.'+this.$route.params.id)
                .listen('.order_created', (e) => {
                    getOrder(e.order.hash).then(response => {
                        this.orders.unshift(response);
                        this.getTime(response);
                    })
                });
            this.$store.dispatch('sellout/attempt', this.$route.params.id);
        },
        methods:{
            infiniteHandler($state){
                if(this.orders.current_page < this.orders.last_page){
                    this.params.page = this.orders.current_page + 1;
                    this.getOrders($state);
                }else{
                    $state.complete();
                }
            },
            setTime(order, time){
                order.time = time;
                this.updateOrder(order);
            },
            editStatus(order, status){
                this.loading = true;
                order.status = status;
                this.updateOrder(order);
            },
            updateOrder(order){
                this.$store.dispatch('order/updateOrder', order).then(response => {
                    var index = this.orders.data.findIndex(x => x.id == response.id);
                    if(index != -1){
                        this.$set(this.orders.data, index, response);
                        this.getTime(this.orders.data[index]);
                        this.loading = false;
                    }
                    this.editItem = null;
                    this.$store.commit('app/ADD_MESSAGE', 'Zaktualizowano poprawnie');
                });
            },
            markPaid(order){
                this.loading = true;
                var data = order;
                data.paid = !data.paid;
                if(data.paid){
                    moment.tz('Europe/Warsaw');
                    data.payment_date = moment().format('YYYY-MM-DD HH:mm:ss');
                }else{
                    data.payment_date = null;
                }
                this.updateOrder(data);
            },

            getSellout(){
                this.loading = true;
                manageSellout(this.$route.params.id, this.params).then(response => {
                    this.sellout = response;
                    this.$store.commit('sellout/UPDATE_SELLOUT', response);
                    this.$store.commit('sellout/SET_ACTIVE_SELLOUT', response);
                    this.loading = false;
                }).catch(e => {
                    this.$store.commit('app/ADD_ERROR', {text: e.response.data.message}, {root: true});
                    this.$router.push('/');
                    this.loading = false;
                });

            },
            getOrders($state = null){
                this.startLoading();
                if(this.params.new){
                    this.params.status = 'new';
                }else{
                    this.params.status = null;
                }
                getSelloutOrders(this.$route.params.id, this.params).then(response => {
                    if(response.current_page == 1){
                        this.orders = response;
                        this.orders.data.forEach((order) => {
                            this.getTime(order);
                        })
                    }else{
                        this.orders.current_page = response.current_page;
                        this.orders.last_page = response.last_page;
                        response.data.forEach((order) => {
                            this.getTime(order);
                            this.orders.data.push(order);
                        })
                    }
                    if($state){
                        if(response.page != response.last_page){
                            $state.loaded();
                        }else{
                            $state.complete();
                        }
                    }

                    this.stopLoading();
                })
            },
            listen(){
                Echo.channel('SaleChannel.'+response.id)
                    .listen('.order_created', (e) => {
                        if(state.active && e.order.sale.id == state.active.id){
                            state.active.orders.unshift(e.order);
                        }
                    });
            },
            getTime(order){
                if(order.status == 'new'){
                    var data = processPercentageTimeleft(order.payment_date, order.time);
                    this.$set(order, 'timeleft', data)
                    setInterval(() => {
                        data = processPercentageTimeleft(order.payment_date, order.time);
                        this.$set(order, 'timeleft', data);
                    }, 30000);
                }
            },
            getOrderTimeStamps(order){
                var temp = [];
                for (var i = 1; i < 5; i++) {
                    temp.push(order.time + (5 * i));
                }
                return temp;
            }
        }
    }
</script>
<style lang="scss">
    .paid{
        .v-expansion-panel-header{
            background-color: rgba(117, 195, 116, 0.67);
        }
    }
    .list-enter-active, .list-leave-active {
        transition: all 0.4s;
    }
    .list-enter, .list-leave-to /* .list-leave-active below version 2.1.8 */ {
        opacity: 0;
        transform: translateY(60px);
    }
</style>