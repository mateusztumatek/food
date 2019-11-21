<template>
    <div>
        <v-list color="transparent" three-line>
            <template v-for="item in items">
                <v-list-item @click="openProduct(item)">
                    <v-list-item-avatar tile size="100">
                        <v-img  :src="getSrc(item.image)"></v-img>
                    </v-list-item-avatar>
                    <v-list-item-content>
                        <h4 class="">{{item.name}}</h4>
                        <p class="grey--text">{{item.price | currency()}}</p>
                    </v-list-item-content>
                    <v-list-item-action v-if="category.place && category.place.user_id == user.id" @click.prevent>
                        <v-btn :to="'/products?product_id='+item.id">Edytuj produkt</v-btn>
                    </v-list-item-action>
                </v-list-item>
                <v-divider></v-divider>
            </template>

        </v-list>
    </div>
</template>
<script>
    import {selloutCategoryItems} from "../../api/sellout";

    export default {

        data(){
            return{
                loading: false,
                items:{},
                category:{},
            }
        },
        computed:{
          user(){return this.$store.getters.user}
        },
        mounted() {
            this.loading = true;
            this.getItems();
        },
        methods:{
            openProduct(product){
              this.$eventBus.$emit('openProduct', {product: product, sale: this.$route.params.sale});
            },
             getItems(){
                selloutCategoryItems(this.$route.params.sale, this.$route.params.category).then(response => {
                    this.category = response.category;
                    this.items = response.items;
                    this.$store.commit('navigation/SET_HEADER', {title: this.category.name});
                    this.loading = false;
                }).catch(e => {this.$store.commit('app/ADD_ERROR', {text: e.response.data.message})});
            }
        }
    }
</script>