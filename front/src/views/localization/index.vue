<template>
    <div>
        <v-card >
            <v-card-title>Znajdź miejsca w pobliżu</v-card-title>
            <v-card-text>
                <gmap-map
                        v-if="location != 'denied'"
                        :center="center"
                        @dragend="getSales"
                        @zoom_changed="getSales"
                        :zoom="10"
                        ref="map"
                        map-type-id="terrain"
                        @click="closeWindow()"
                        style="width:100%;  height: 700px;"
                >
                    <gmap-marker :icon="'/img/user.png'" :key="'user'" v-if="location" :position="{lng: parseFloat(location.lng), lat:parseFloat(location.lat)}">

                    </gmap-marker>
                    <gmap-marker
                            :icon="'/img/restaurant.png'"
                            :key="sale.id"
                            v-if="sale.lat"
                            :clickable="true"
                            v-for="sale in sellouts"
                            @click="selected = sale"
                            :position="{lat: parseFloat(sale.lat), lng: parseFloat(sale.lng)}"
                    ></gmap-marker>
                    <gmap-info-window
                            v-if="selected && selected.id"
                            :position="{lng: parseFloat(selected.lng), lat: parseFloat(selected.lat)}"
                            :opened="selected && selected.id != null"
                            @closeclick="selected = null"
                    >
                        <div class="google-map-window" v-if="selected && selected.id">
                            <map-item :data="selected"></map-item>
                        </div>
                    </gmap-info-window>
                </gmap-map>
                <div v-else>
                    <h2 class="display-3 text-center" style="padding: 50px 0px">Brak pozwolenia</h2>
                </div>
                <v-list three-line :loading="loading">
                    <span v-for="sale in sortedSellouts">
                        <v-list-item :to="'/sellout/'+sale.id">
                            <v-list-item-avatar>
                                <v-img :src="getSrc(sale.image)"></v-img>
                            </v-list-item-avatar>
                            <v-list-item-content>
                                <v-list-item-title>{{sale.name}} <span class="caption float-right">{{sale.distance}}Km</span></v-list-item-title>
                                <v-list-item-subtitle v-html="sale.place.description"></v-list-item-subtitle>
                                <v-list-item-subtitle class="my-2">
                                    <v-chip class="mr-1" v-for="tag in sale.place.tags">{{tag.tag}}</v-chip>
                                </v-list-item-subtitle>
                            </v-list-item-content>
                        </v-list-item>
                        <v-divider></v-divider>
                    </span>

                </v-list>
            </v-card-text>
        </v-card>
    </div>
</template>
<script>
    import {getSellouts} from "../../api/sellout";
    import mapItem from "./map-item";
    export default {
        components:{
          mapItem
        },
        data(){
            return{
                loading: false,
                selected: null,
                sellouts: []
            }
        },
        computed:{
            sortedSellouts(){
              return this.sellouts.sort((a, b) => {
                  return (a.distance < b.distance)? -1 : 0;
              })
            },
            location(){return this.$store.getters.location},
            center(){
                if(this.location && this.location.lng){
                    return {lat: parseFloat(this.location.lat), lng: parseFloat(this.location.lng)}
                }else{
                    return {lat: 51.714776 , lng: 19.495305}
                }
            }
        },
        mounted() {
          if(this.location == 'no_ask'){
              this.$confirm('Prośba o geolokalizację', 'Za chwilę zostaniesz poproszony o dostęp do twojej lokalizacji, jeśli odmówisz nie będziesz miał dostępu do niektórych części systemu')
                  .then(r => {this.$store.dispatch('user/getLocation').then(res => {this.getSales()})})
                  .catch(e => {this.$store.dispatch('user/getLocation')})
          }else{
              this.$store.dispatch('user/getLocation').then(e => {
                  setTimeout(() => {
                      this.getSales(true);
                  }, 300)
              });
          }
        },
        methods:{
            closeWindow(){
              if(this.selected != null){
                  this.selected = null;
              }
            },
          getSales(withDistance = true){
              this.loading = true;
              var bounds = this.$refs.map.$mapObject.getBounds();
              var ne = [bounds.getNorthEast().lng(), bounds.getNorthEast().lat()];
              var sw = [bounds.getSouthWest().lng(), bounds.getSouthWest().lat()];
              var obj = {ne: ne, sw: sw};
              if(withDistance){
                  obj = {...obj, withDistance: true, user_lat: this.location.lat, user_lng: this.location.lng};
              }
              getSellouts(obj).then(response => {
                    this.sellouts = response;
                  this.loading = false;
              }).catch(e => {
                  this.loading = false;
                  this.$store.commit('app/ADD_ERROR', {text: 'Nie udało się pobrać miejsc'});
              })
          }
        }

    }
</script>
<style lang="scss">
    .google-map-window{

        .header{
            color: red !important;
            font-size: 1.5rem !important;
            font-weight: bold;
        }
        color: red;
    }
    .gm-style-iw{
        padding: 0px !important;
        .gm-style-iw-d{
            position: relative !important;
            width: 400px;
            overflow: auto !important;

        }
    }
    @media screen and (max-width: 1200px){
        .gm-style-iw{
            max-width: 320px !important;
            width: 320px;
            .gm-style-iw-d{
                max-width: 320px !important;
                width: 320px !important;
            }
        }
    }
</style>