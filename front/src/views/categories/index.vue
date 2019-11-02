<template>
    <div class="w-100">
        <v-card>
            <v-card-title>
                Kategorie
                <v-spacer></v-spacer>
                <v-text-field
                        @input="search"
                        v-model="term"
                        append-icon="search"
                        label="Szukaj"
                        single-line
                        lazy
                        hide-details
                ></v-text-field>
            </v-card-title>
            <v-data-table
                    show-select
                    v-model="selected"
                    :options.sync="params"
                    :headers="headers"
                    :items="categories"
                    :server-items-length="categries_length"
                    :loading="loading"
                    class="elevation-5 w-100"
            >
                <template v-slot:item.place="{ item }">
                    <span v-if="item.place">{{item.place.name}}</span>
                </template>
            </v-data-table>
            <v-card-actions v-if="selected.length > 0">
                <v-btn @click="deleteSelected" color="red" >Usuń zaznaczone</v-btn>
            </v-card-actions>
        </v-card>
    </div>
</template>
<script>
    import {getCategories, deleteMassive} from "../../api/categories";
    import editAdd from './edit-add';
    export default {
        component:{editAdd},
        data () {
            return {
                check: false,
                temp_per_page: 10,
                term: '',
                params: {},
                selected:[],
                loading: true,
                headers: [
                    {
                        text: 'Miejsce',
                        align: 'left',
                        sortable: false,
                        value: 'place',
                    },
                    {
                        text: 'Nazwa',
                        value: 'name'
                    },
                    {
                        text: 'Aktywna',
                        value: 'active',
                    },
                    {
                        text: 'Obrazek',
                        value: 'image'
                    },
                    {

                    }
                ],
                categories: [],
                categries_length: 0
            }
        },
        computed:{
            user(){return this.$store.getters.user}
        },
        watch:{
            params: {
                handler () {
                    this.getCategories()
                },
                deep: true,
            },
        },
        mounted(){
            this.getCategories();
        },
        methods:{
            deleteSelected(){
                this.startLoading();
                var ids = this.selected.map((item) => {
                    return item.id;
                });
                deleteMassive({ids: ids}).then(response => {
                    this.getCategories();
                    this.stopLoading();
                }).catch(e => {
                    this.$store.commit('app/ADD_ERROR', {text: e.response.data.message});
                    this.stopLoading();
                })
            },
            getCategories(){
                this.loading = true;
                var obj = {...{user_id: this.user.id, paginate: true}, ...this.params, ...{term: this.term}};
                getCategories(obj).then(response => {
                    this.categories = response.data;
                    this.categries_length = response.total;
                    this.loading = false;
                }).catch(e => {
                    this.$store.commit('app/ADD_ERROR', {text: e.response.data.message});
                    this.loading = false;
                })
            },
            search(){
                this.getCategories();
            }
        }
    }
</script>