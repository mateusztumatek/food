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
                                <v-list-item  @click="showCodePicker(client)">
                                    <v-list-item-title>Wyślij kod promocyjny</v-list-item-title>
                                </v-list-item>
                            </v-list>
                        </v-menu>
                    </v-list-item-icon>
                </v-list-item>
            </v-list>

            <v-dialog :value="(selectedClient)? true : false" persistent max-width="600px">
                <v-card>
                    <v-card-title>
                        <span class="headline">Wyślij kod do klienta </span>
                    </v-card-title>
                    <v-card-text>
                        <v-list>
                            <v-list-item @click="sendCode(code)" v-for="code in codes">
                                <v-list-item-title>
                                    {{code.code}}
                                </v-list-item-title>
                                <v-list-item-action>
                                    <span v-if="code.percentage">{{code.percentage}}%</span>
                                    <span v-else>{{code.value}}zł</span>
                                </v-list-item-action>
                            </v-list-item>
                        </v-list>
                    </v-card-text>
                </v-card>
            </v-dialog>
        </v-card>
    </div>
</template>
<script>
    import {getClients} from "../../api/stats";
    import {getCodes, sendToClient} from "../../api/codes";

    export default {
        data(){
            return{
                selectedClient: null,
                clients: [],
                loading: true,
                codes: [],
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
            },
            showCodePicker(client){
                this.selectedClient = client;
                this.getCodes();
            },
            getCodes(){
                this.loadingCodes = true;
                getCodes().then(response => {
                    this.codes = response.data;
                })
            },
            sendCode(code){
                var data = {
                    code_id: code.id
                };
                if(this.selectedClient.user){
                    data['user_id'] = this.selectedClient.user_id;
                }
                data['session_key'] = this.selectedClient.session_key;

                sendToClient(data).then(response => {
                    this.$store.commit('app/ADD_MESSAGE', 'Wysłano kod do klienta ');
                })
            }
        }
    }
</script>