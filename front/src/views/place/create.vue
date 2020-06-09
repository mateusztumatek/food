<template>
    <div class="w-100">
        <v-form
                ref="form"
                v-model="valid"
                lazy-validation>
            <v-card>
                <v-card-title>{{$t('Tworzenie nowego miejsca')}}</v-card-title>
                <v-card-text>
                    <v-row justify="center">
                        <v-col cols="12" class="text-center">
                            <v-avatar class="change-avatar" color="indigo" size="200" v-if="place.image">
                                <img
                                        :src="getSrc(place.image)"
                                >
                                <div class="change"><v-btn @click="$refs.file_input.click()" small color="primary">{{$t('Zmień')}}</v-btn></div>
                            </v-avatar>
                            <v-btn v-else @click="$refs.file_input.click()">{{$t('Dodaj zdjęcie')}}</v-btn>
                            <input type="file" id="file_input" @change="uploadLogo()" style="display: none" ref="file_input"></input>
                        </v-col>
                        <v-col cols="12">
                            <v-text-field :error="(errors.name)? true : false"
                                          :error-messages="errors.name" v-model="place.name" :label="$t('Nazwa miejsca')"></v-text-field>
                        </v-col>
                        <v-col cols="12">
                            <trumbowyg v-model="place.description" :config="config" class="form-control" name="content"></trumbowyg>
                        </v-col>

                        <v-col cols="12">
                            <v-combobox
                                    v-model="place.tags"
                                    chips
                                    clearable
                                    :label="$t('Tagi')"
                                    multiple
                            >
                                <template v-slot:selection="{ attrs, item, select, selected }">
                                    <v-chip
                                            v-bind="attrs"
                                            :input-value="selected"
                                            close
                                            color="blue"
                                            @click="place.tags.splice(place.tags.findIndex(e => e == item), 1)"
                                            @click:close="place.tags.splice(place.tags.findIndex(e => e == item), 1)"
                                    >
                                        <strong>{{ item }}</strong>
                                    </v-chip>
                                </template>
                            </v-combobox>
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
                                        :position="{lat: parseFloat(place.lat), lng: parseFloat(place.lng)}"
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

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="primary" @click="save">
                        <v-icon class="mr-2">mdi-content-save</v-icon>
                        {{$t('Zapisz')}}
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-form>
    </div>
</template>
<script>
    import {upload} from "../../api/upload";
    import {getPlace} from "../../api/place";
    // Import this component
    import Trumbowyg from 'vue-trumbowyg';

    // Import editor css
    import 'trumbowyg/dist/ui/trumbowyg.css';
    export default {
        components: {
            Trumbowyg
        },
        data(){
            return{
                text: 'SIEMANKO',
                valid: true,
               place:{},
                config: {
                    // Get options from
                    // https://alex-d.github.io/Trumbowyg/documentation
                }
            }
        },
        computed:{
          center(){
              if(this.place.lat){
                  return {lat: parseFloat(this.place.lat), lng: parseFloat(this.place.lng)}
              }else return {lat: 51.714776 , lng: 19.495305}
          }
        },

        mounted(){
          if(this.$route.params.id){
              getPlace(this.$route.params.id).then(response => {
                  this.place = response;
                  this.place.tags = this.place.tags.map((item) => {
                      return item.tag;
                  })
              })
          }
        },
        methods:{
            save(){
                this.startLoading();
                if(this.place.id){
                    this.$store.dispatch('places/updatePlace', this.place).then(response => {
                        this.stopLoading();
                        this.$router.push('/place');
                    }).catch(e => {
                        this.errors = e.response.data.errors;
                        this.stopLoading();
                    })
                }else{
                    this.$store.dispatch('places/storePlace', this.place).then(response => {
                        this.stopLoading();
                        this.$router.push('/place');
                    }).catch(e => {
                        this.errors = e.response.data.errors;
                        this.stopLoading();
                    })
                }
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
                for(var i in temp){
                    this.$set(this.place, i, temp[i]);
                }
                this.$set(this.place, 'lat', place.geometry.location.lat());
                this.$set(this.place, 'lng', place.geometry.location.lng());
            },
            uploadLogo(){
                var formData = new FormData();
                formData.append('files[]', this.$refs.file_input.files[0]);
                this.startLoading();
                upload(formData, 'places').then(response => {
                    this.stopLoading();
                    this.$set(this.place, 'image', response.data[0]);
                }).catch(e => {
                    this.stopLoading();
                })
            }
        }
    }
</script>