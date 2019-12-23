<template>
    <div class="w-100">
        <v-row>
            <v-col cols="12" md="6" lg="4" v-for="place in places">
                <v-card hover ripple>
                    <v-img
                            class="white--text align-end"
                            :src="getSrc(place.image)"
                            height="200px"
                    >
                        <div class="align-items-center justify-content-end" style="padding: 10px">
                            <v-chip
                                    style="font-size: 0.7rem"
                                    class="mr-1"
                                    v-for="tag in place.tags"
                                    color="blue"
                            >
                                <strong>{{ tag.tag }}</strong>
                            </v-chip>
                        </div>

                        <v-card-title>{{place.name}}
                            <p class="text-muted w-100" style="font-size: 0.7rem">{{place.created_at}}</p>
                        </v-card-title>
                    </v-img>
                    <v-card-text>
                        <p class="w-100 mb-0"><v-icon class="mr-2">mdi-sign-direction</v-icon>{{place.street}}</p>
                        <p class="w-100 mb-0"><v-icon class="mr-2">mdi-city</v-icon>{{place.city}} {{place.postal_code}}</p>

                    </v-card-text>
                    <v-card-actions>
                        <v-btn :to="'place/edit/'+place.id">Edytuj</v-btn>
                        <v-btn @click="del(place)"><v-icon>mdi-trash-can-outline</v-icon>Usuń</v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
            <v-col cols="12" md="6" lg="4">
                <v-card height="100%" class="align-center d-flex">
                    <v-card-text class="text-center">
                        <v-btn to="place/create">Dodaj nowe</v-btn>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </div>
</template>
<script>
    export default {
        computed:{
            places(){
                return this.$store.getters.places;
            }
        },
        methods:{
            del(place){
                this.$confirm('Usuwanie elementu', 'Czy na pewno chcesz usunąć ten element?').then(res => {
                    this.$store.dispatch('places/deletePlace', place);
                }).catch(e => {

                })
            }
        }
    }
</script>
<style lang="scss">
    .white--text{
        .v-card__title{
            background: rgb(0,0,0);
            background: linear-gradient(0deg, rgba(0,0,0,0.865983893557423) 0%, rgba(0,0,0,0.6615021008403361) 40%, rgba(0,0,0,0) 100%);
        }
    }
</style>