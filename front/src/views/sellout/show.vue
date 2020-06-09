<template>
    <div style="min-height: 1200px" class="sellout-show">
            <v-app-bar
                    v-if="sale.background_image"
                    shrink-on-scroll
                    prominent
                    height="250"
                    :src="getSrc(sale.background_image)"
                    fade-img-on-scroll
                    scroll-target="#scrolling-techniques-3"
            >
                <template v-slot:img="{ props }">
                    <v-img
                            v-bind="props"
                    ></v-img>
                </template>
                <v-spacer></v-spacer>

                <v-btn icon>
                    <v-icon>mdi-magnify</v-icon>
                </v-btn>

                <v-btn icon>
                    <v-icon>mdi-heart</v-icon>
                </v-btn>

                <v-btn icon>
                    <v-icon>mdi-dots-vertical</v-icon>
                </v-btn>

                <template v-slot:extension>
                    <v-tabs
                            v-model="tab"
                            align-with-title
                            background-color="transparent"
                            color="primary"
                    >
                        <v-tab key="kategorie">Kategorie</v-tab>
                        <v-tab key="informacje">Informacje</v-tab>
                    </v-tabs>
                </template>
            </v-app-bar>
        <v-tabs-items v-model="tab">
            <v-tab-item
                    key="kategorie"
            >
                    <v-list color="transparent" three-line>
                        <v-list-item :to="'/sellout/'+sale.id+'/category/'+category.id" v-for="category in sale.categories">
                            <v-list-item-avatar tile size="100">
                                <v-img  :src="getSrc(category.image_url)"></v-img>
                            </v-list-item-avatar>
                            <v-list-item-content>
                                <h3 >{{category.name}} </h3>
                            </v-list-item-content>
                        </v-list-item>
                    </v-list>
            </v-tab-item>
            <v-tab-item
                    key="informacje"
            >
                <v-container>
                    <div v-if="sale && sale.place" v-html="sale.place.description"></div>
                </v-container>
            </v-tab-item>
        </v-tabs-items>
    </div>
</template>
<script>
    import {getSellout} from "../../api/sellout";

    export default {

        data(){
            return{
                tab: null,
                sale: {},
                loading: false,
            }
        },
        mounted() {
            this.loading = true;
            this.getSale();
            $(document).ready(() => {
              /*  this.fixed.remember_top = $('#sale_tollbar').offset().top;
                console.log($('#sale_tollbar').offset().top)
                $(document).on('scroll', () => {
                    if($(window).scrollTop() >= this.fixed.remember_top){
                        this.fixed.is_fixed = true;
                    }else this.fixed.is_fixed = false;
                })*/
            })

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
<style lang="scss">
    .sellout-show{
        .v-tabs-items{
            background-color: transparent !important;
        }
        .v-tabs{
            background: rgb(0,0,0);
            background: linear-gradient(180deg, rgba(0,0,0,0.3981967787114846) 0%, rgba(0,0,0,0.6334908963585435) 49%, rgba(0,0,0,0.7231267507002801) 100%);
        }
    }
</style>