<template>
    <div>
        <v-data-table
                v-model="selected"
                :headers="headers"
                :items="codes.data"
                :options.sync="pagination"
                :server-items-length="codes.total"
                :footer-props="{'items-per-page-options': [10,20,50]}"
                :loading="loading"
                item-key="id"
                show-select
                class="elevation-1"
        >
        </v-data-table>
        <div class="mt-4">
            <v-btn :to="'/codes/create'" color="primary">Dodaj nowy kod</v-btn>
        </div>
    </div>
</template>
<script>
    import {getCodes} from "../../api/codes";

    export default {
        data(){
            return{
                loading:false,
                codes:{},
                selected:[],
                headers: [
                    {
                        text: 'ID',
                        align: 'left',
                        sortable: false,
                        value: 'id',
                    },
                    { text: 'Kod', value: 'code' },
                    { text: 'Sprzedaż', value: 'sale.name' },
                    { text: 'Miejsce', value: 'place.name' },
                    { text: 'Zniżka procentowa', value: 'percentage'},
                    { text: 'Zniżka liczbowa', value: 'value'},
                    { text: 'Maksymalna ilośc wykorzystań', value: 'max_uses'},
                    { text: 'Wykorzystane', value: 'uses' },

                    /*{ text: 'Iron (%)', value: 'iron' },*/
                ],
                pagination:{}
            }
        },
        mounted(){
          this.getCodes();
        },
        methods:{
            getCodes(){
                getCodes(this.pagination).then(response => {
                    this.codes = response;
                })
            }
        }
    }
</script>