<template>
    <v-scroll-x-transition v-if="cart.sale_id">
        <div>
            <v-dialog @click:outside="$store.commit('cart/SET_VISIBLE', false)" v-model="visible" class="m-0" fullscreen hide-overlay transition="dialog-bottom-transition">
                <template v-slot:activator="{ on }">
                    <v-bottom-navigation
                            grow
                            app>
                        <v-btn @click="$store.commit('cart/SET_VISIBLE', true)" class="pt-1 cart-button">
                            <v-badge
                                    overlap
                                    color="cyan"
                                    right
                            >
                                <template v-slot:badge>
                                    <span>{{cart.items.length}}</span>
                                </template>
                                <v-icon
                                        large
                                        color="grey lighten-1"
                                >shopping_cart</v-icon>
                            </v-badge>
                            {{cart.price | currency()}} zł
                        </v-btn>
                    </v-bottom-navigation>
                </template>
                <v-card>
                    <v-toolbar dark color="primary">
                        <v-btn icon dark @click.prevent="$store.commit('cart/SET_VISIBLE', false)">
                            <v-icon>mdi-close</v-icon>
                        </v-btn>
                        <v-toolbar-title>Twoje zakupy w {{cart.sale.place.name}}</v-toolbar-title>
                    </v-toolbar>
                    <div class="py-3">
                       <v-list three-line subheader>
                            <v-list-item v-for="item, index in cart.items">
                                <v-list-item-avatar :size="100" tile>
                                    <v-img :src="getSrc(item.image)" :alt="item.name"></v-img>
                                </v-list-item-avatar>
                                <v-list-item-content>
                                    <div v-if="item.edit == true">
                                        <v-text-field :value="item.quantity" @input="$store.commit('cart/SET_ITEM_QUANTITY', {id: index, quantity: $event})" type="number" label="Number" append-outer-icon="add" @click:append-outer="increment(item)" prepend-icon="remove" @click:prepend="decrement(item)"></v-text-field>
                                    </div>
                                    <div v-else>
                                        <v-list-item-title>Nazwa: {{item.name}}</v-list-item-title>
                                        <v-list-item-subtitle>cena: {{item.price | currency()}} zł</v-list-item-subtitle>
                                        <v-list-item-subtitle>ilość: {{item.quantity}}</v-list-item-subtitle>
                                    </div>

                                    <v-row>
                                        <v-col cols="6">
                                            <v-btn class="w-100" @click="(!item.edit)? $set(item, 'edit', true) : updateItem(item, index)"><span v-if="!item.edit">Edytuj</span><span v-else>Zapisz</span></v-btn>
                                        </v-col>
                                        <v-col cols="6">
                                            <v-btn color="red" class="w-100" @click="deleteItem(index)">Usuń</v-btn>
                                        </v-col>
                                    </v-row>
                                </v-list-item-content>
                            </v-list-item>
                           <v-divider></v-divider>
                           <v-list-item>
                               <v-list-item-content>
                                   <v-list-item-title>Cena twoich zakupów: {{cart.price | currency()}} PLN</v-list-item-title>
                               </v-list-item-content>
                           </v-list-item>
                           <v-list-item>
                               <v-list-item-content>
                                    <v-btn color="primary" @click="process()">Zrealizuj zamówienie</v-btn>
                               </v-list-item-content>
                           </v-list-item>
                        </v-list>
                    </div>
                </v-card>
            </v-dialog>
        </div>
    </v-scroll-x-transition>
</template>
<script>
    import {deleteItem, updateItem} from "../../api/cart";

    export default {
        data(){
            return{
            }
        },
        computed:{
            cart(){return this.$store.getters.cart},
            visible(){return this.$store.getters.cart_visible}
        },
        mounted() {
        },
        methods:{
            deleteItem(index){
                deleteItem(this.cart.sale_id, {id: index}).then(response => {
                    this.$store.commit('cart/SET_CART', response);
                    this.$store.commit('app/ADD_MESSAGE', 'Usunięto przedmiot z koszyka');
                }).catch(e => {this.$store.dispatch('app/ADD_ERROR', {text: 'Nie udało sie usunąć elementu z karty'})})
            },
            updateItem(item, index){
                updateItem(this.cart.sale_id,{id: index, quantity: item.quantity}).then(response => {
                    this.$store.commit('cart/SET_CART', response);
                    this.$store.commit('app/ADD_MESSAGE', 'Zaktualizowano poprawnie');
                })
            },
            increment (item) {
                item.quantity = parseInt(item.quantity,10) + 1
            },
            decrement (item) {
                if(item.quantity > 1){
                    item.quantity = parseInt(item.quantity,10) - 1
                }
            },
            process(){
                this.$store.commit('cart/SET_VISIBLE', false);
                this.$router.push('/sellout/'+this.cart.sale_id+'/process');
            }
        }
    }
</script>
<style lang="scss">
    .cart-button{
        .v-btn__content{
            display: -webkit-box !important;
        }
    }
</style>