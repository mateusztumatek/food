<template>
    <div>
        <v-list three-line>
            <v-list-item :to="'/sellout/'+sale.id+'/category/'+category.id" v-for="category in sale.categories">
                <v-list-item-avatar tile size="100">
                    <v-img  :src="getSrc(category.image_url)"></v-img>
                </v-list-item-avatar>
                <v-list-item-content>
                    <h3 class="display-1" >{{category.name}} </h3>
                </v-list-item-content>
            </v-list-item>
        </v-list>
    </div>
</template>
<script>
    import {getSellout} from "../../api/sellout";

    export default {

        data(){
            return{
                sale: {},
                loading: false
            }
        },
        mounted() {
            this.loading = true;
            this.getSale();
        },
        methods:{
            getSale(){
                this.startLoading();
                getSellout(this.$route.params.id).then(response => {
                    this.sale = response
                    this.$store.dispatch('stats/cacheStat', {type: 'place_view', place_id: this.sale.place.id});
                    this.stopLoading();
                    this.loading = false;
                    this.$store.commit('navigation/SET_HEADER', {title: 'Sprzedaż'+this.sale.name});
                }).catch(e => {
                    this.stopLoading();
                    this.loading = false;
                    this.$store.commit('app/ADD_ERROR', {text: e.response.data.message});
                })
            }
        }
    }
</script>