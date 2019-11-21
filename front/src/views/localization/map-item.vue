<template>
    <v-card
            v-if="item.place"
            light
            :loading="loading"
    >
        <v-img height="250" :src="getSrc(item.place.image)"></v-img>


        <v-card-title>{{item.name}}</v-card-title>

        <v-card-text>
            <!--<v-row align="center" class="mx-0">
                <v-rating
                        :value="4.5"
                        color="amber"
                        dense
                        half-increments
                        readonly
                        size="14"
                ></v-rating>
                <div class="grey&#45;&#45;text ml-4">4.5 (413)</div>
            </v-row>-->
            <div><v-icon>mdi-home</v-icon><span class="ml-3">{{item.city}}, {{item.street}}</span></div>
            <v-btn v-if="!showItems" @click="showItems = true" class="my-2" small color="primary" block>Zobacz przedmioty</v-btn>
            <v-list three-line v-if="item.items.data && item.items.data.length > 0 && showItems">
                <template v-for="(item, index) in item.items.data">
                    <v-list-item
                            :key="item.title"
                            @click=""
                    >
                        <v-list-item-avatar tile size="70">
                            <v-img :src="getSrc(item.image)"></v-img>
                        </v-list-item-avatar>

                        <v-list-item-content>
                            <v-list-item-title>{{item.name}} <span class="float-right font-weight-black">{{item.price | currency}}zł</span></v-list-item-title>
                            <v-list-item-subtitle v-html="item.description"></v-list-item-subtitle>
                        </v-list-item-content>
                    </v-list-item>
                </template>
            </v-list>
            <infinite-loading @infinite="infiniteHandler" v-if="item.items.last_page > item.items.current_page"></infinite-loading>
        </v-card-text>
        <v-divider class="mx-4"></v-divider>
        <span v-if="item.place.tags && item.place.tags.length > 0">
             <v-card-title>Tonight's availability</v-card-title>
             <v-card-text>
                <v-chip-group
                        active-class="deep-purple accent-4 white--text"
                        column>
                    <v-chip v-for="tag in item.place.tags">{{tag.tag}}</v-chip>
                </v-chip-group>
             </v-card-text>
        </span>
        <v-card-actions>
            <v-btn
                    :to="'/sellout/'+item.id"
                    color="primary accent-4"
                    text>
                Zobacz więcej
            </v-btn>
        </v-card-actions>
    </v-card>
</template>
<script>
    import {getSellout} from "../../api/sellout";

    export default {
        props: ['data'],
        data(){
            return{
                showItems: false,
                loading:false,
                item: {}
            }
        },
        mounted() {
            this.getSale();
        },
        methods:{
            infiniteHandler($state) {
                this.loading = true;
                getSellout(this.data.id, {page: this.item.items.current_page + 1})
                    .then(response => {
                        this.item.items.data = [...this.item.items.data, ...response.items.data];
                        this.item.items.current_page = response.items.current_page;
                        this.item.items.last_page = response.items.last_page;
                        if(this.item.items.last_page == this.item.items.current_page){
                            this.loading = false;
                            $state.complete();
                        }else{
                            this.loading = false;
                            $state.loaded();
                        }
                }).catch(e => {
                    this.loading = false;
                    this.$store.commit('app/ADD_ERROR', {text: e.response.data.message});
                    $state.complete();
                });
            },
            getSale(){
                getSellout(this.data.id).then(response => {
                    this.item = response;
                    this.load = false;
                })
            }
        }
    }
</script>