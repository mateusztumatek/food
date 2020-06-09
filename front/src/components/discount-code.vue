<template>
    <div>
        <v-btn v-if="!coupon_active" @click="coupon_active = true">Wprowadź kod</v-btn>
        <div v-if="coupon_active">
            <v-text-field
                    :error="(errors.coupon || errors.code)? true : false"
                    :error-messages="errors.coupon"
                    label="Wprowadź swój kupon"
                    outlined
                    :loading = "loading"
                    v-model="coupon"
            >
                <template v-slot:append-outer="">
                    <v-btn v-if="cart.code == null" :loading = "loading" @click="applyCode">Aplikuj kod</v-btn>
                    <v-btn v-else color="red" :loading = "loading" @click="dezActivate">Dezaktywuj kod</v-btn>
                </template>
            </v-text-field>
            <div v-if="cart.code">
                <p class="grey--text" v-if="cart.code.percentage">Zniżka procentowa: {{cart.code.percentage}}%</p>
                <p class="grey--text" v-if="cart.code.value">Zniżka cenowa: {{cart.code.value}}zł</p>
            </div>
        </div>
    </div>
</template>
<script>
    import {applyCode, removeCartCode} from "../api/codes";

    export default {
        data(){
            return{
                coupon_active: false,
                coupon:null,
                loading: false,
            }
        },
        computed:{
            cart(){return this.$store.getters.cart}
        },
        mounted(){
          if(this.cart.code){
              this.coupon_active = true;
              this.coupon = this.cart.code.code;
          }
        },
        methods:{
            applyCode(){
                this.errors = [];
                this.loading = true;
                applyCode({sale_id: this.cart.sale_id, code: this.coupon})
                    .then(response => {
                        this.loading = false;
                        this.$store.commit('cart/SET_CART', response);
                    }).catch(e => {
                        if(e.response.data.errors) this.errors = e.response.data.errors;
                        this.loading = false;
                    })
            },
            dezActivate(){
                this.errors = [];
                this.loading = true;
                removeCartCode({sale_id: this.cart.sale_id})
                    .then(response => {
                        this.loading = false;
                        this.coupon = null;
                        this.$store.commit('cart/SET_CART', response);
                    }).catch(e => {
                    if(e.response.data.errors) this.errors = e.response.data.errors;
                    this.loading = false;
                })
            }
        }
    }
</script>