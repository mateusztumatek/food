<template>
    <div v-if="order">
        <v-card>
            <v-card-title>
                Zamówienie nr: {{order.local_id}} w sklepie <span v-if="order.sale">{{order.sale.place.name}}</span>
            </v-card-title>
            <v-card-text class="text-center" v-if="order.timeleft">
                <v-progress-circular style="margin: auto" v-if="order.timeleft" color="primary" :size="300" width="10" v-model="order.timeleft.percentage"><span class="display-1">{{order.timeleft.percentage}}%</span></v-progress-circular>
            </v-card-text>
            <v-list>
                <v-list-item><span class="mr-3">Nr twojego zamówienia to: </span><span class="display-1 font-weight-bold">#{{order.local_id}}</span></v-list-item>
                <v-list-item><span class="mr-3">Status: </span><v-chip color="success">{{order.status}}</v-chip></v-list-item>
                <v-list-item><span class="mr-3">Zapłacone: </span><v-chip v-if="order.paid" color="success">Tak</v-chip><v-chip v-else color="red">Nie</v-chip></v-list-item>
                <v-list-item><span class="mr-3">Adres email: </span> {{order.email}}</v-list-item>
                <v-list-item><span class="mr-3">Cena całkowita: </span> <span class="green--text">{{order.amount | currency()}} PLN</span></v-list-item>
                <v-list-item><span class="mr-3">Uwagi: {{order.comment}}</span></v-list-item>
                <v-divider></v-divider>
                <v-list-item>
                    <v-list-item-title>Zakupione przedmioty:
                    </v-list-item-title>
                </v-list-item>
                <v-list-item v-for="item in order.order_items" v-if="item.item">
                    <v-list-item-avatar :size="100" tile>
                        <v-img :src="getSrc(item.item.image)" :alt="item.item.name"></v-img>
                    </v-list-item-avatar>
                    <v-list-item-content>
                        <div>
                            <v-list-item-title>Nazwa: {{item.item.name}}</v-list-item-title>
                            <v-list-item-subtitle>cena: {{item.single_price | currency()}} zł</v-list-item-subtitle>
                            <v-list-item-subtitle>ilość: {{item.quantity}}</v-list-item-subtitle>
                        </div>
                    </v-list-item-content>
                </v-list-item>
            </v-list>

        </v-card>
    </div>
</template>
<script>
    export default {
        computed:{
            order(){return this.$store.getters.activeOrder}
        },
        mounted() {
            this.$store.dispatch('order/getOrder', this.$route.params.hash);
        },

        methods:{
            getOrder(){

            }
        }
    }
</script>