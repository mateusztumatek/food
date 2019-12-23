<template>
    <div style="width: 100%" v-bind:class="{'full-width': isSearch}">
        <v-autocomplete
                class="autocomplete"
                v-model="select"
                v-bind:class="{'full-width': isSearch}"
                :loading="isLoading"
                :search-input.sync="term"
                :items="items"
                :placeholder="$t('Szukaj')"
                @focus="setSearch(true)"
                @blur="setSearch(false)">
            <template v-slot:item="data">
                <v-list-item-avatar>
                    <img :src="$root.getSrc(data.item.value.avatar)">
                </v-list-item-avatar>
                <v-list-item-content>
                    <v-list-item-title>{{data.item.value.name}}</v-list-item-title>
                    <v-list-item-subtitle>{{data.item.value.city}}</v-list-item-subtitle>
                </v-list-item-content>
            </template>
        </v-autocomplete>
        <p>{{search.items}}</p>
    </div>
</template>
<script>
    import {mapGetters} from 'vuex';
    import {search} from "../../api/search";

    export default {
        data: () => ({
            descriptionLimit: 60,
            entries: [],
            select: null,
            isLoading: false,
            model: null,
            term: null,
        }),

        computed: {
            isSearch(){
              return this.$store.getters.isSearch;
            },
            items(){
                return this.entries.map((item) => {
                    return {
                        text: item.name,
                        value: item
                    }
                })
            },
            ...mapGetters(['search'])
        },
        methods:{
          setSearch(value){
              this.$store.commit('search/setSearch', value);
          }
        },
        watch:{
            term(val){
                this.isLoading = true;
                search(this.term).then(response => {
                    this.entries = response.data;
                    this.isLoading = false
                }).catch(e => {

                })
            }
        }
    }
</script>
<style lang="scss">

    .autocomplete{
        transition: all 300ms;
        .v-text-field__details{
            display: none !important;
        }
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center !important;
        width: 100%;
    }
</style>