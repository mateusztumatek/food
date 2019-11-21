<template>
    <div class="w-100" v-if="product">
        <v-dialog v-model="dialog" class="m-0" fullscreen hide-overlay transition="dialog-bottom-transition">
            <v-card>
                <v-toolbar dark color="primary">
                    <v-btn icon dark @click="dialog = false">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                    <v-toolbar-title>{{product.name}}</v-toolbar-title>
                </v-toolbar>
                <div class="py-3">
                    <v-list three-line subheader>
                        <v-list-item class="text-center justify-center">
                            <v-avatar size="300">
                                <img
                                        style="object-fit: cover"
                                        :src="getSrc(product.image)"
                                        :alt="product.name+'image'"
                                >
                            </v-avatar>
                        </v-list-item>
                        <v-list-item>
                            <v-list-item-content>
                                <v-list-item-title class="display-1">{{product.price | currency()}} zł</v-list-item-title>
                                <v-list-item-title>{{product.name}}</v-list-item-title>
                                <v-list-item-subtitle>{{product.description}}</v-list-item-subtitle>
                            </v-list-item-content>
                        </v-list-item>
                        <v-list-item>
                            <v-container class="align-center" style="display: flex">
                                <v-btn icon @click="quantity = quantity - 1"><v-icon>mdi-minus</v-icon></v-btn>
                                <v-text-field height="50" hide-details
                                              v-model="quantity"
                                               single-line
                                class="input-center display-1">
                                </v-text-field>
                                <v-btn icon @click="quantity=quantity+1"><v-icon>mdi-plus</v-icon></v-btn>
                            </v-container>
                        </v-list-item>
                        <v-list-item>

                            <v-btn class="w-100" color="primary" @click="addToCart()">Dodaj do koszyka</v-btn>
                        </v-list-item>
                    </v-list>
                </div>
            </v-card>
        </v-dialog>
    </div>
</template>
<script>
    import {getProduct} from "../../api/products";

    export default {
        data(){
            return{
                quantity: 1,
                product: null,
                dialog: false
            }
        },
        watch:{
          quantity: function(){
              if(this.quantity < 1){
                  this.quantity = 1;
              }
          }
        },
        mounted() {
            this.$eventBus.$on('openProduct', (data) => {
                this.openProduct(data.product, data.sale);
            })
        },
        methods:{
            addToCart(){
                this.$store.dispatch('cart/addToCart', {item_id: this.product.id, quantity: this.quantity});
                this.dialog = false;
            },
          openProduct(product, sale){
              this.startLoading();
            getProduct(product.id).then(response => {
                this.product = response;
                this.dialog = true;
                this.stopLoading();
            }).catch(e => {this.stopLoading();})
          }
        }
    }
</script>