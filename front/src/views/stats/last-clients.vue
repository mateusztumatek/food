<template>
    <div>
        <v-card>
            <v-card-title>Ostatni klienci</v-card-title>

            <v-list>
                <v-list-item v-for="client in clients.data">
                    <v-list-item-avatar :size="60" v-if="client.user">
                        <v-img  :src="getSrc(client.user.avatar)"></v-img>
                    </v-list-item-avatar>

                    <v-list-item-content>
                        <v-list-item-title  v-if="client.user">{{client.user.name}}</v-list-item-title>
                        <v-list-item-subtitle>
                            <p v-if="client.user_device" class="mb-0"><v-icon class="mr-2 body-2">mdi-devices</v-icon>{{client.user_device}}<v-chip x-small class="ml-2" v-if="client.is_mobile">Mobile</v-chip><v-chip x-small class="ml-2" v-else>Desktop</v-chip></p>
                            <p class="mb-0"><v-icon class="mr-2 body-2">mdi-city-variant-outline</v-icon>{{client.country}}, {{client.city}}</p>
                        </v-list-item-subtitle>
                    </v-list-item-content>

                    <v-list-item-icon>
                        <v-menu bottom left>
                            <template v-slot:activator="{ on }">
                                <v-btn
                                        dark
                                        icon
                                        v-on="on"
                                >
                                    <v-icon>mdi-dots-vertical</v-icon>
                                </v-btn>
                            </template>
                            <v-list>
                                <v-list-item v-if="client.user" :href="'/account/'+client.user.id">
                                    <v-list-item-title >Zobacz profil</v-list-item-title>
                                </v-list-item>
                                <v-list-item >
                                    <v-list-item-title >Wyślij kod promocyjny</v-list-item-title>
                                </v-list-item>
                            </v-list>
                        </v-menu>
                    </v-list-item-icon>
                </v-list-item>
            </v-list>
        </v-card>
    </div>
</template>
<script>
    import {getClients} from "../../api/stats";

    export default {
        data(){
            return{
                clients: [],
                loading: true
            }
        },
        mounted() {
          this.getClients();
        },
        methods:{
            getClients(){
                getClients().then(response => {
                    this.clients = response;
                })
            }
        }
    }
</script>