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
            <template v-slot:item.actions="{item}">
                <v-btn small tile color="primary" @click="$router.push('/codes/'+item.id+'/edit')"><v-icon x-small class="mr-1">mdi-pencil</v-icon>Edytuj</v-btn>
            </template>
        </v-data-table>
        <div class="mt-4">
            <v-btn class="mr-2" tile @click="deleteSelected()" v-if="selected.length > 0" color="red">Usuń zaznaczone</v-btn>
            <v-btn tile :to="'/codes/create'" color="primary">Dodaj nowy kod</v-btn>
        </div>
    </div>
</template>
<script>
    import {getCodes, deleteCodes} from "../../api/codes";

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
                    { text: 'Akcje', value: 'actions' },

                    /*{ text: 'Iron (%)', value: 'iron' },*/
                ],
                pagination:{}
            }
        },
        watch:{
          pagination:{
              deep: true,
              handler:function () {
                  this.getCodes();
              }
          }
        },
        mounted(){
          this.getCodes();
        },
        methods:{
            deleteSelected(){
                this.$confirm('Usuwanie kodów', 'Czy na pewno chcesz usunąć te elementy?').then(resposne => {
                    this.loading = true;
                    deleteCodes(this.selected).then(response => {
                        this.pagination.page = 1;
                        this.loading = false;
                        this.getCodes();
                    })
                })
            },
            getCodes(){
                getCodes(this.pagination).then(response => {
                    this.codes = response;
                })
            }
        }
    }
</script>