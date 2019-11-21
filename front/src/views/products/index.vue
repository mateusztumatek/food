<template>
    <div class="w-100">
        <v-row>
            <v-col cols="12">
                <v-text-field
                        v-model="term"
                        @input="search()"
                        :loading="loading"
                        label="Szukaj produktu"
                ></v-text-field>
            </v-col>
            <v-fade-transition v-for="product in products.data">
                <v-col cols="12" md="6" lg="3">
                    <v-card hover ripple>
                        <v-img
                                class="white--text align-end"
                                :src="getSrc(product.image)"
                                height="200px"
                        >
                            <div class="align-items-center justify-content-end" style="padding: 10px">
                                <v-chip
                                        style="font-size: 0.7rem"
                                        class="mr-1"
                                        v-for="tag in product.tags"
                                        color="blue"
                                >
                                    <strong>{{ tag.tag }}</strong>
                                </v-chip>
                            </div>

                            <v-card-title>{{product.name}}
                                <p class="text-muted w-100" style="font-size: 0.7rem">{{product.created_at}}</p>
                            </v-card-title>
                        </v-img>
                        <v-card-text>
                            <p class="w-100 mb-3">{{product.description | truncate(80, '...')}}</p>
                            <p class="w-100 mb-3"><v-icon class="mr-2">mdi-apps</v-icon>
                                <v-chip color="blue" v-for="category in product.categories" class="mr-1">
                                    {{category.name}}
                                </v-chip>
                            </p>
                            <p class="w-100 mb-0"><v-icon class="mr-2">mdi-cash</v-icon><span style="font-weight: 200; font-size: 1.2rem">{{product.price | currency()}}PLN</span></p>
                        </v-card-text>
                        <v-card-actions>
                            <v-btn @click="editItem(product)">Edytuj</v-btn>
                            <v-btn @click="del(product)"><v-icon>mdi-trash-can-outline</v-icon>Usuń</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-col>
            </v-fade-transition>
            <v-col cols="12" md="6" lg="4">
                <v-card height="100%" class="align-center d-flex">
                    <v-card-text class="text-center">
                        <v-btn @click="create_new()">Dodaj nowy produkt</v-btn>
                    </v-card-text>
                </v-card>
            </v-col>
            <div class="w-100">
                <v-btn v-if="showMore == false" @click="showMore = true" class="w-100">Pokaż więcej</v-btn>
                <infinite-loading  @infinite="infiniteHandler" v-if="products.last_page > products.current_page && showMore"></infinite-loading>
            </div>
        </v-row>
        <createor-component v-on:close="creator = false" :visible="creator" @update="updateProduct"></createor-component>
    </div>
</template>
<script>
    import CreateorComponent from './creator';
    import {getUserProducts} from "../../api/products";

    export default {
        components:{
            CreateorComponent
        },
        data(){
            return{
                term: '',
                loading: false,
                creator: false,
                products: {current_page:0, data:[]},
                showMore: false,
            }
        },
        computed:{
            user() {return this.$store.getters.user},
            /*products(){return this.$store.getters.products.products;}*/
        },
        mounted() {
            /*this.$store.dispatch('products/getProducts');*/
            this.getProducts(true);
            if(this.$route.query.product_id){
                getUserProducts(this.user.id, {id: this.$route.query.product_id}).then(response => {
                    this.editItem(response[0]);
                })
            }
        },
        methods:{
            updateProduct(product){
                var index = this.products.data.findIndex(x => x.id == product.id);
                if(index == -1){
                    this.products.data.unshift(product);
                }else{
                    this.products.data[index] = product;
                }
                this.creator = false;
            },
            search(){
              this.products.current_page = 0;
              this.products.last_page = 0;
              this.getProducts();
            },
            infiniteHandler($state){
                getUserProducts(this.user.id, {paginate: true, page: this.products.current_page + 1, term: this.term}).then(res => {
                    this.products.current_page = res.current_page;
                    this.products.last_page = res.last_page;
                    res.data.forEach((item) => {
                        this.products.data.push(item);
                    })
                    this.showMore = false;
                    if(res.current_page == res.last_page) $state.complete();
                    else $state.loaded();
                }).catch(e => {
                    $state.complete();
                })
            },
            getProducts(set = false){
                this.loading = true;

                getUserProducts(this.user.id, {paginate: true, page: this.products.current_page+1, term: this.term}).then(response => {
                    this.products = response;
                    this.loading = false;
                }).catch(e => {this.loading = false;})

            },
            del(product){
                this.$store.commit('products/DELETE_PRODUCT', product);
            /*  this.startLoading();
              this.$store.dispatch('products/deleteProduct', product).then(response => {
                  this.stopLoading();

              })*/
            },
            create_new(){
                this.creator = true;
            },
            editItem(product){
                this.creator = true;
                this.$store.commit('products/SET_NEW_PRODUCT_PROPERTY', {full: product});
            }
        }
    }
</script>
<style lang="scss">
    .white--text{
        .v-card__title{
            background: rgb(0,0,0);
            background: linear-gradient(0deg, rgba(0,0,0,0.865983893557423) 0%, rgba(0,0,0,0.6615021008403361) 40%, rgba(0,0,0,0) 100%);
        }
    }
</style>