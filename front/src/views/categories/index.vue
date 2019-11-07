<template>
    <div class="w-100">
        <v-card>
            <v-card-title>
                <v-row>
                    <v-col cols="12" md="5" class="d-flex" style="align-items: center">
                        Kategorie
                    </v-col>
                    <v-col cols="12" md="5" class="d-flex" style="align-items: center">
                        <v-text-field
                                @input="search"
                                v-model="term"
                                append-icon="search"
                                label="Szukaj"
                                single-line
                                lazy
                                hide-details
                        ></v-text-field>
                    </v-col>
                    <v-col cols="12" md="2" class="d-flex" style="align-items: center">
                        <v-btn @click="edit={}">Dodaj nową</v-btn>
                    </v-col>
                </v-row>
            </v-card-title>
            <v-data-table
                    show-select
                    @click:row="editItem"
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
                <template v-slot:item.active="{item}">
                    <v-chip v-if="item.active" color="primary">tak</v-chip>
                    <v-chip v-else color="red">Nie</v-chip>
                </template>
                <template v-slot:item.image="{item}">
                    <v-avatar size="60px">
                        <img
                                v-if="item.image"
                                alt="Avatar"
                                :src="getSrc(item.image)">
                    </v-avatar>
                </template>
            </v-data-table>
            <v-card-actions v-if="selected.length > 0">
                <v-btn @click="deleteSelected" color="red" >Usuń zaznaczone</v-btn>
            </v-card-actions>
        </v-card>
        <edit-add v-on:closed="edit = null" v-on:updated="updatedItem" v-on:stored="storedItem" :dialog="edit" ></edit-add>
    </div>
</template>
<script>
    import {getCategories, deleteMassive} from "../../api/categories";
    import editAdd from './edit-add';
    export default {
        components: {editAdd},
        data () {
            return {
                edit: null,
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
            updatedItem(item){
                const index = _.findIndex(this.categories, ['id', item.id]);
                this.categories[index] = item;
            },
            storedItem(item){
              this.categories.push(item);
            },
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
            editItem(item){
                this.edit = item;
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