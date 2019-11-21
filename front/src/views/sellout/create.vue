<template>
    <div class="w-100">
        <v-form ref="sellout_form">
        <v-row>
            <v-col md="8" cols="12">
                <v-card>
                    <v-card-title>Wypełnij dane sprzedaży</v-card-title>
                    <v-card-text>
                        <v-select
                                v-model="sellout.place_id"
                                :items="places"
                                item-text="name"
                                item-value="id"
                                @input="getCategories()"
                                :rules="rules.place_id"
                                label="Wybierz miejsce do którego chcesz przyporządkować sprzedaż"
                                hide-details
                                prepend-icon="mdi-shopping"
                                single-line
                        ></v-select>
                        <v-text-field label="Nazwij sprzedaż" v-model="sellout.name" :rules="rules.name"></v-text-field>
                        <v-radio-group :rules="rules.payment_type" label="Wybierz rodzaj płatności zamówienia." v-model="sellout.payment_type" :mandatory="false">
                            <v-radio label="Przedpłata" value="prepaid"></v-radio>
                            <v-radio label="Płatność po wykonaniu" value="afterpaid"></v-radio>
                        </v-radio-group>
                        <label>Automatycznie ustaw zamówienie jako zrealizowane po zmianie statusu na wykonane</label>
                        <v-radio-group row v-model="sellout.archivize" :mandatory="false">
                            <v-radio label="Nie ustawiaj" :value="null"></v-radio>
                            <v-radio label="Po 5 minutach" value="5"></v-radio>
                            <v-radio label="Po 10 minutach" value="10"></v-radio>
                            <v-radio label="Po 15 minutach" value="15"></v-radio>
                        </v-radio-group>
                        <label>Wybierz godziny sprzedaży</label>
                        <v-row>
                            <v-col md="5" cols="12" class="d-flex flex-wrap">
                                <v-time-picker :rules="rules.hours_from" style="margin: auto" format="24hr" v-model="sellout.hour_from"></v-time-picker>
                                <v-text-field :error-messages="(errors.hour_from)? errors.hour_from : null" type="hidden" class="text-center w-100 notInput" :rules="rules.hours_from" v-model="sellout.hour_from"></v-text-field>
                            </v-col>
                            <v-col md="2" cols="12" class="d-flex" style="align-items: center; justify-content: center"><span class="font-weight-bold display-3">-</span></v-col>
                            <v-col md="5" cols="12" class="d-flex flex-wrap">
                                <v-time-picker style="margin: auto" format="24hr" v-model="sellout.hour_to"></v-time-picker>
                                <v-text-field :error-messages="(errors.hour_to)? errors.hour_to: null" type="hidden" class="text-center w-100 notInput" :rules="rules.hours_from" v-model="sellout.hour_to"></v-text-field>
                            </v-col>
                        </v-row>
                        <div v-if="this.sellout.place_id">
                            <label>Wyierz które kategorie i produkty mają być dostępne w tej sprzedaży</label>
                            <div class="w-100 my-2">
                                <v-btn @click="toggle()">Zaznacz wszystkie</v-btn>
                            </div>
                            <v-treeview return-object v-model="selected" hoverable selectable item-key="id" item-children="items" :items="categories"></v-treeview>
                        </div>
                        <v-text-field type="hidden" class="mb-2 notInput w-100" :rules="rules.sel" v-model="selected.length"></v-text-field>
                        <v-btn @click="save()" color="primary" class="w-100"><v-icon>mdi-save</v-icon>Zapisz</v-btn>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col md="4" cols="12">
                <v-card class="mb-5" v-if="sellout.id">
                    <v-card-title>Niebezpieczna strefa</v-card-title>
                    <v-card-text @click="deleteSellout()">
                        <v-btn min-height="60" class="w-100" color="red">Usuń sprzedaż</v-btn>
                    </v-card-text>
                </v-card>
                <v-card style="position: sticky; top:50px">
                    <v-card-title>Wybierz miejsce</v-card-title>
                    <v-card-text>
                        <v-list >
                            <v-list-item-group v-model="item" color="primary">
                                <v-list-item
                                        v-for="(place, i) in navs"
                                        :key="i"
                                        @click="selectPlace(place)"
                                >
                                    <v-list-item-icon>
                                        <v-icon>mdi-home</v-icon>
                                    </v-list-item-icon>
                                    <v-list-item-content>
                                        <v-list-item-title>{{place.name}}
                                            <p class="mb-0 grey--text overline">Miasto: {{place.city}}</p>
                                            <p class="mb-0 grey--text overline">Ulica: {{place.street}}</p>
                                            <p class="mb-0 grey--text overline">Kod pocztowy: {{place.postal_code}}</p>
                                        </v-list-item-title>
                                    </v-list-item-content>
                                </v-list-item>
                            </v-list-item-group>
                        </v-list>
                        <div class="mb-3">
                            <label style="width: 100%" >
                                <gmap-autocomplete  class="el-input__inner" style="width: 100%; min-height: 60px"
                                                    :options="{types: ['address']}"
                                                    @place_changed="setPlace">
                                </gmap-autocomplete>
                            </label>
                        </div>
                        <gmap-map
                                :center="center"
                                :zoom="10"
                                style="width:100%;  height: 400px;"
                        >
                            <gmap-marker
                                    :key="0"
                                    v-if="selectedPlace"
                                    :position="{lat: parseFloat(selectedPlace.lat), lng: parseFloat(selectedPlace.lng)}"
                            ></gmap-marker>
                        </gmap-map>
                        <div v-for="e in errors">
                            <v-alert
                                    v-for="item in e"
                                    border="left"
                                    color="indigo"
                                    dark
                            >
                                {{item}}
                            </v-alert>
                        </div>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
        </v-form>
    </div>
</template>
<script>
    import {getCategories} from "../../api/categories";
    import {storeSellout, manageSellout, updateSellout, deleteSellout} from "../../api/sellout";

    export default {
        data(){
            return{
                rules:{
                  sel: [v => (v < 1)? 'Musiz wybrać conajmniej 3 produkty' : true],
                    name:[
                      v => !!v || 'Nazwa jest wymagana',
                      v => (v && v.length <= 3)? 'Nazwa musi być większa niż 3 litery' : true
                  ],
                  payment_type:[
                      v => !!v || 'Typ sprzedaży jest wymagany'
                  ],
                  hours_from: [
                      v => !!v || 'Godzina jest wymagana',
                  ],
                  place:[
                      v => !!v || 'Musisz wybrać miejsce',
                      v => (v && v.lng)? 'Szerokość musi być podana' : true,
                      v => (v && v.lat)? 'Wysokość musi być podana' : true
                  ]
                },
                sellout:{},
                item:{},
                selectedPlace:null,
                customPlaces:[],
                categories:[],
                selected:[],
            }
        },
        watch:{
          item: function(val){
              if(typeof this.item == 'undefined')this.selectedPlace = null
          }
        },
        computed:{
            user() {return this.$store.getters.user},
            navs(){
              return [...this.places, ...this.customPlaces];
            },
            places(){return this.$store.getters.places},
            center(){
                if(this.selectedPlace){
                    return {lat: parseFloat(this.selectedPlace.lat), lng: parseFloat(this.selectedPlace.lng)}
                }else return {lat: 51.714776 , lng: 19.495305}
            }
        },
        mounted(){
            if(this.$route.params && this.$route.params.id){
                this.getSellout();
            }
        },
        methods:{
            deleteSellout(){
                this.$confirm('Czy na pewno chcesz usunąć tą sprzedaż').then(res => {
                    deleteSellout(this.sellout.id).then(response =>{
                        this.$store.commit('app/ADD_MESSAGE', 'Udało się usunąć sprzedaż');
                        this.$store.commit('sellout/DELETE_SELLOUT', this.sellout);
                        this.$router.push('/');
                    }).catch(e => {
                        if(e.response.data.message){
                            this.$store.commit('app/ADD_ERROR', {text: e.response.data.message});
                        }
                    })
                })
            },
            getSellout(){
                this.loading = true;
                manageSellout(this.$route.params.id, this.params).then(response => {
                    this.sellout = response;
                    this.selected = response.items;
                    this.getCategories();
                    this.$store.commit('sellout/SET_ACTIVE_SELLOUT', response);
                    this.loading = false;
                }).catch(e => {
                    this.$store.commit('app/ADD_ERROR', {text: e.response.data.message}, {root: true});
                    this.$router.push('/');
                    this.loading = false;
                });

            },
            save(){
                if(this.$refs.sellout_form.validate()){
                    if(!this.selectedPlace || !this.selectedPlace.lat){
                        this.errors.push(['Brak wybranego miejsca']);
                        return null;
                    }
                    var place = {lat: this.selectedPlace.lat, lng: this.selectedPlace.lng, city: this.selectedPlace.city, street: this.selectedPlace.street, postal_code: this.selectedPlace.postal_code};
                    var obj = {...this.sellout, ...{products: this.selected}, ...place};
                    this.startLoading();
                    if(!this.sellout.id){
                        var func = storeSellout(obj);
                    }else{
                        var func = updateSellout(this.sellout.id, obj);
                    }
                    func.then(response => {
                        this.stopLoading();
                        this.$store.dispatch('sellout/getSellouts');
                        this.$router.push('/sellout/'+response.id+'/manage');
                    }).catch(e => {
                        this.stopLoading();
                        this.errors = e.response.data.errors;
                        this.resetErrors();
                        this.$store.commit('app/ADD_ERROR', {text: e.response.data.message});
                    })
                }
            },
            getCategories(){
                var params = {user_id: this.user.id, place_id: this.sellout.place_id, withProducts: true};
                getCategories(params).then(response => {
                  /*  var iterator = 0;
                    for (var i in response){
                        iterator = iterator + 1;
                        response[i].temp_id = iterator;
                        response[i].items.forEach((item) => {
                            iterator = iterator + 1;
                            item.temp_id = iterator;
                        })
                    }*/
                    var temp = this.selected;
                    this.selected = [];
                    this.categories = response;
                    setTimeout(() => {
                        this.selected = temp;
                    },200)

                }).catch(e => {
                    this.$store.commit('app/ADD_ERROR', {text: e.response.data.message});
                })
            },
            selectPlace(place){
                this.selectedPlace = place;
            },
            setPlace(place){
                var temp = {
                    street: '',
                };
                var check = 0;
                place.address_components.forEach(element => {
                    if(element.types.includes('locality')){
                        temp.city = element.short_name;
                        check = check +1;
                    }
                    if(element.types.includes('postal_code')){
                        temp.postal_code = element.short_name;
                        check = check +1;
                    }
                    if(element.types.includes('route')){
                        temp.street = temp.street + ' '+element.short_name;
                        check = check +1;
                    }
                    if(element.types.includes('street_number')){
                        temp.street = temp.street + ' ' +element.short_name;
                        check = check +1;
                    }
                })
                if(check != 4){
                    this.errors.push({place: 'Musisz podać dokładniejsze dane miejsca'});
                    this.resetErrors();
                    return null;
                }
                temp['lat'] = place.geometry.location.lat();
                temp['lng'] = place.geometry.location.lng();
                this.customPlaces.push(temp);
                this.selectedPlace = temp;
            },
            toggle(){
                this.selected = [];
                for (var i in this.categories){
                    this.categories[i].items.forEach((item) => {
                        this.selected.push(item);
                    })
                }
            }
        }
    }
</script>