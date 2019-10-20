<template>
    <div class="w-100">
        <v-card>
            <v-card-title>Tworzenie nowego miejsca</v-card-title>
            <v-card-text>
                <v-row justify="center">
                    <v-col cols="12" class="text-center">
                        <v-avatar class="change-avatar" color="indigo" size="200">
                            <img
                                    :src="place.image"
                            >
                            <div class="change"><v-btn @click="$refs.file_input.click()" small color="primary">Zmień</v-btn></div>
                            <input type="file" id="file_input" @change="uploadImage()" style="display: none" ref="file_input"></input>
                        </v-avatar>
                    </v-col>
                    <v-col cols="12">
                        <v-text-field v-model="place.name" label="Nazwa miejsca"></v-text-field>
                    </v-col>
                    <v-col cols="12">
                        <v-text-field v-model="place.description" label="Opis miejsca"></v-text-field>
                    </v-col>

                    <v-col cols="12">
                        <v-textarea
                                outlined
                                v-model="place.description"
                                label="Opis miejsca"
                        ></v-textarea>
                    </v-col>
                    <v-col cols="12">
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
                                    v-if="place.lat"
                                    :position="{lat: place.lat, lng: place.lng}"
                            ></gmap-marker>
                        </gmap-map>
                    </v-col>
                </v-row>
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
    </div>
</template>
<script>
    export default {
        data(){
            return{

               place:{},
            }
        },
        computed:{
          center(){
              if(this.place.lat){
                  return {lat: this.place.lat, lng: this.place.lng}
              }else return {lat: 51.714776 , lng: 19.495305}
          }
        },
        methods:{
            setPlace(place){
                var temp = {
                    maps_street: '',
                };
                console.log('PLACE', place);
                var check = 0;
                place.address_components.forEach(element => {
                    if(element.types.includes('locality')){
                        temp.maps_city = element.short_name;
                        check = check +1;
                    }
                    if(element.types.includes('postal_code')){
                        temp.maps_code = element.short_name;
                        check = check +1;
                    }
                    if(element.types.includes('route')){
                        temp.maps_street = temp.maps_street + ' ' +element.short_name;
                        check = check +1;
                    }
                    if(element.types.includes('street_number')){
                        temp.maps_street = temp.maps_street + ' ' +element.short_name;
                        check = check +1;
                    }
                })
                if(check != 4){
                    this.errors.push({place: 'Musisz podać dokładniejsze dane miejsca'});
                    this.resetErrors();
                    return null;
                }
                for(var i in temp){
                    this.$set(this.place, i, temp[i]);
                }
                this.$set(this.place, 'lat', place.geometry.location.lat());
                this.$set(this.place, 'lng', place.geometry.location.lng());
            }
        }
    }
</script>