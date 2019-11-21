<template>
    <div class="w-100" v-if="cart.sale">
        <div class="heading">
            <span class="headline">Zamów ze sklepu: {{cart.sale.place.name}}</span>
            <div class="w-100 body-1">Sprzedaż: {{cart.sale.name}}</div>
        </div>

        <v-dialog @click:outside="editItemComment = null" :value="(editItemComment && editItemComment.id)? true : false" persistent max-width="600px">
            <v-card v-if="editItemComment">
                <v-card-title>Wpisz komentarz do produktu {{editItemComment.name}}</v-card-title>
                <v-card-text>
                    <v-textarea v-model="editItemComment.comment"></v-textarea>
                </v-card-text>
                <v-card-actions>
                    <v-btn @click="updateItem(editItemComment)">Zapisz</v-btn>
                    <v-btn @click="editItemComment = null">Odrzuć</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-list style="background-color: transparent" flat three-line subheader>
            <v-list-item v-for="item, index in cart.items">
                <v-list-item-avatar :size="100" tile>
                    <v-img :src="getSrc(item.image)" :alt="item.name"></v-img>
                </v-list-item-avatar>
                <v-list-item-content>
                    <div v-if="item.edit == true">
                        <v-text-field :value="item.quantity" @input="$store.commit('cart/SET_ITEM_QUANTITY', {id: index, quantity: $event})" type="number" label="Number" append-outer-icon="add" @click:append-outer="increment(item)" prepend-icon="remove" @click:prepend="decrement(item)"></v-text-field>
                    </div>
                    <div v-else>
                        <v-row>
                            <v-col lg="6">
                                <v-list-item-title>Nazwa: {{item.name}}</v-list-item-title>
                                <v-list-item-subtitle>cena: {{item.price | currency()}} zł</v-list-item-subtitle>
                                <v-list-item-subtitle>ilość: {{item.quantity}}</v-list-item-subtitle>
                                <v-btn small class="mt-2" @click="editItemComment = item; editItemComment.index = index">Wpisz komentarz do tego produktu</v-btn>
                                <p v-if="item.comment" class="mt-2">Komentarz: {{item.comment}}</p>
                            </v-col>
                        </v-row>
                    </div>
                </v-list-item-content>
            </v-list-item>
            <v-divider></v-divider>
        </v-list>
        <v-list style="background-color: transparent" flat>
            <v-list-item>
                <v-list-item-content>
                    <v-textarea
                            label="Wpisz komentarz do zamówienia"
                            v-model="order.comment"
                            hint="Tutaj możesz wpisać ogólne uwagi do zamówienia, uwagi do produktu osobno wpisz w polu komentarz do poroduktu"
                    ></v-textarea>
                </v-list-item-content>
            </v-list-item>
            <v-list-item>
                <v-list-item-content>
                    <v-text-field
                            :error="(errors.email)? true : false"
                            :error-messages="errors.email"
                            label="Twój email"
                            v-model="order.email"
                    ></v-text-field>
                </v-list-item-content>
            </v-list-item>
            <v-list-item>
                <v-list-item-content>
                    <div>
                        <p v-for="item in cart.items">{{item.name}} <span class="grey--text" v-if="item.quantity > 1">x{{item.quantity}}</span> {{item.price * item.quantity | currency()}} PLN</p>
                        <h3 class="display-1"><v-icon class="grey--text mr-2">mdi-cash</v-icon>{{cart.price | currency()}} PLN</h3>
                    </div>
                </v-list-item-content>
            </v-list-item>
        </v-list>
        <div class="grey--text text-center" style="font-size: 1.3rem"><span v-if="cart.sale.payment_type == 'afterpaid'"> W tej sprzedaży możesz zapłacić od razu, lub po zrealizowaniu zamówienia</span><span v-else>Ta sprzedaż wymaga opłacenia twojego zamówienia przed realizacją</span></div>
        <div class="w-100 d-flex my-5">
            <v-col cols="6">
                <v-btn :loading="loading" class="mx-1 w-100" height="70" @click="pay('payu')">Zapłać online</v-btn>
            </v-col>
            <v-col cols="6">
                <v-btn :loading="loading" class="mx-1 w-100" height="70" @click="pay('local')">Zapłać przy kasie</v-btn>
            </v-col>
        </div>
    </div>
</template>
<script>
    import {updateItem} from "../../api/cart";

    export default {
        data(){
            return{
                editItemComment: null,
                loading: false,
                order:{
                    comment: '',
                    'email': '',
                }
            }
        },
        mounted(){
            if(this.user.id){
                this.order.email = this.user.email;
            }
        },
        computed:{
            user(){return this.$store.getters.user},
            cart(){return this.$store.getters.cart}
        },
        methods:{
            updateItem(item, index){
                updateItem(this.cart.sale_id,{id: item.index, quantity: item.quantity, comment: item.comment}).then(response => {
                    this.$store.commit('cart/SET_CART', response);
                    this.$store.commit('app/ADD_MESSAGE', 'Zaktualizowano poprawnie');
                    this.editItemComment = null;
                })
            },
            pay(type){
                this.errors = [];
                this.loading = true;
                this.$store.dispatch('order/makeOrder', {...this.order, sale_id: this.cart.sale_id, payment_type: type, items: this.cart.items, amount: this.cart.price}).then(response => {
                    if(response.redirect){
                        window.location.href = response.redirect;
                    }
                    this.loading = false;
                    this.$store.dispatch('order/getUserOrders');
                    this.$router.push('/orders/'+response.hash);
                }).catch(e => {
                    if(e.response.data.errors){
                        this.errors = e.response.data.errors;
                    }
                    this.loading = false;
                })
            }
        }
    }
</script>