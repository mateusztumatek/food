<template>
    <div>
        <v-form ref="form_3" >
            <v-row>
                <v-col cols="12">
                    <v-text-field label="Cena" @input="updateProduct($event, 'price')" step="0.01" type="number" :rules="priceRules" :value="product.price"></v-text-field>
                </v-col>
                <v-col cols="12">
                    <v-switch
                            v-model="in_moment"
                            :label="'Przygotowany na miejscu:'"
                            @input="validate()"
                    ></v-switch>
                </v-col>
                <v-col v-if="!in_moment" cols="12">
                    <v-text-field label="Czas przygotowania (w przybliżeniu) w minutach" @input="updateProduct($event, 'prepere_time')" step="0.5" type="number" :rules="productTime" :value="product.prepere_time"></v-text-field>
                </v-col>
            </v-row>
        </v-form>
    </div>
</template>
<script>
    export default {
        name: 'Third_step',
        computed:{
            product(){
                return this.$store.getters.products.new_product;
            }
        },
        data(){
            return{
                productTime:[
                    v => !!v || 'Pole cena jest wymagane',
                    v => (v > 0)? true: 'Pole musi być większe od 0',

                ],
                in_moment: true,
                valid: false,
                priceRules: [
                    v => !!v || 'Pole cena jest wymagane',
                    v => (v < 0)? 'Pole cena musi być większe od 0' : true
                ]
            }
        },
        methods:{
            validate(){
                if(this.$refs.form_3.validate()){
                    this.valid = true;
                    this.$emit('done', 3, true);
                } else{
                    this.valid = false;
                    this.$emit('done', 3, false);
                }
            },
            updateProduct(e, prop){
                this.$store.commit('products/SET_NEW_PRODUCT_PROPERTY', {value: e, prop: prop});
                this.validate();
            },
        }
    }
</script>