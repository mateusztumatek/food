<template>
    <div class="give-me-space py-12">
        <h1 class="display-1">Dodaj kod</h1>
        <div class="row">
            <div class="col-md-8">
                <p>Wybierz do czego ma być przypisany kod</p>
                <v-btn class="mr-3" tile :outlined="to != 'place'" color="primary" @click="changeType('place')">Miejsce</v-btn><v-btn tile :outlined="to != 'sale'" color="primary" @click="changeType('sale')">Sprzedaż</v-btn>
                <v-select
                        :loading="loading"
                        class="mt-5"
                        :items="sales"
                        v-model="code.sellout_id"
                        item-value="id"
                        label="Wybierz sprzedaż"
                        item-text="name"
                        :error="(errors.sellout_id)? true : false"
                        :error-messages="errors.sellout_id"
                        v-if="to == 'sale'"
                ></v-select>
                <v-select
                        :loading="loading"
                        class="mt-5"
                        :items="places"
                        v-model="code.place_id"
                        item-value="id"
                        label="Wybierz miejsce"
                        item-text="name"
                        :error="(errors.place_id)? true : false"
                        :error-messages="errors.place_id"
                        v-if="to == 'place'"
                ></v-select>
                <v-text-field :loading="loading" label="Przypisz wartość procentową" :error="(errors.percentage)? true : false" :error-messages="errors.percentage" type="number" @input="code.value = null" v-model="code.percentage" min="0" max="100"></v-text-field>
                <v-text-field :loading="loading" label="Przypisz wartość liczbową"  :error="(errors.value)? true : false" :error-messages="errors.value" type="number" @input="code.percentage = null" v-model="code.value" min="0" max="100"></v-text-field>
                <v-text-field v-if="!code.auto" :loading="loading" :error="(errors.code)? true : false" :error-messages="errors.code" label="Wpisz kod" v-model="code.code"></v-text-field>
                <p class="mt-5 body-2 grey--text">Poniżej wpisz liczbę, ile razy pojedyńczy kod może zostać użyty.</p>
                <v-text-field :loading="loading" :error="(errors.max_uses)? true : false" :error-messages="errors.max_uses" label="Maksymalna ilość użycia kodu" v-model="code.max_uses"></v-text-field>
                <v-checkbox v-model="code.auto" label="Wygeneruj automatycznie"></v-checkbox>
                <v-text-field v-if="code.auto" :loading="loading" :error="(errors.quantity)? true : false" :error-messages="errors.quantity" label="Wpisz ilość kodów do wygenerowania" v-model="code.quantity"></v-text-field>
                <v-btn :loading="loading" color="primary" tile @click="save()">Zapisz</v-btn>
            </div>
            <!--<div class="col-md-4">
                <v-card>
                    <v-card-title>Generuj kody</v-card-title>
                </v-card>
            </div>-->
        </div>
    </div>
</template>
<script>
    import {storeCode, updateCode, getCodes} from "../../api/codes";

    export default {
        data(){
            return{
                loading: false,
                to: 'place',
                code: {},
            }
        },
        computed:{
            sales(){return this.$store.getters.sellouts},
            places(){return this.$store.getters.places},
        },
        mounted(){
          if(this.$route.params.id){
              this.getCode();
          }
        },
        methods:{
            getCode(){
                getCodes({id: this.$route.params.id}).then(response => {
                    this.code = response.data[0];
                    if(this.code.sellout_id) this.to = 'sale';
                    if(this.code.place_id) this.to = 'place';
                })
            },
            save(){
              this.loading = true;
              if(this.code.id && !this.code.auto){
                  var func = updateCode(this.code, this.code.id);
              }else{
                  var func = storeCode(this.code);
              }
              func.then(response => {
                this.loading = false;
                this.$router.push('/codes');
              }).catch(e => {
                  if(e.response.data.errors){
                      this.errors = e.response.data.errors;
                  }
                  this.loading = false;
                  this.resetErrors();
              })
            },
            changeType(type){
                (type == 'place')? this.code.sellout_id = null : this.code.place_id = null;
                this.to = type;
            }
        }

    }
</script>