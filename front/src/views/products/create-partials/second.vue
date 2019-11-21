<template>
    <div>
        <v-form ref="form_2" lazy-validation>
            <v-row>
                <v-col cols="12">
                    <v-combobox
                            :value="product.categories"
                            :items="categories"
                            :item-text="'name'"
                            @input="updateProduct($event, 'categories')"
                            label="Wybierz kategorie produktu"
                            :rules="categoriesRules"
                            multiple
                            clearable
                            chips
                    ></v-combobox>
                </v-col>
                <v-col cols="12">
                    <v-btn color="primary" :disabled="!valid" @click="$emit('next_step', 3)">Przejdź dalej</v-btn>
                </v-col>
            </v-row>
        </v-form>
    </div>
</template>
<script>
    import {getCategories} from "../../../api/categories";

    export default {
        name: 'Second_step',
        computed:{
            product(){
                return this.$store.getters.products.new_product;
            }
        },
        data(){
            return{
                valid: false,
                categoriesRules:[
                    v => !!v || 'Kategorie są wymagane',
                    v => (!!v && v.length == 0)? 'Kategorie są wymagane' : true
                ],
                categories: [],
            }
        },
        mounted(){
            this.getCategories();
        },
        methods:{
            getCategories(){
                getCategories().then(response => {
                    this.categories = response;
                })
            },
            validate(){
                if(this.$refs.form_2.validate()){
                    this.valid = true;
                    this.$emit('done', 2, true);
                } else{
                    this.valid = false;
                    this.$emit('done', 2, false);
                }
            },
            updateProduct(e, prop){
                this.$store.commit('products/SET_NEW_PRODUCT_PROPERTY', {value: e, prop: prop});
                this.validate();
            },
        }
    }
</script>