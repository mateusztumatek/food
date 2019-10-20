<template>
    <div style="width: 100%">
        <v-card
                style="width: 100%"
                class="mx-auto w-100"
        >
            <v-card-title>Edytuj swoje konto</v-card-title>
            <v-card-text>
                <v-row justify="center">
                    <v-col cols="12" class="text-center">
                        <v-avatar class="change-avatar" color="indigo" size="200">
                            <img
                                    :src="$root.getSrc(user.avatar)"
                                    :alt="user.name"
                            >
                            <div class="change"><v-btn @click="$refs.file_input.click()" small color="primary">Zmień</v-btn></div>
                            <input type="file" id="file_input" @change="uploadLogo()" style="display: none" ref="file_input"></input>
                        </v-avatar>
                    </v-col>
                    <v-col cols="12">
                        <v-text-field :value="user.name" @input="updateUser('name', $event)" label="Imię i nazwisko"></v-text-field>
                    </v-col>
                    <v-col cols="12">
                        <v-text-field :value="user.city" @input="updateUser('city', $event)" label="Miasto"></v-text-field>
                    </v-col>

                    <v-col cols="12">
                        <v-textarea
                                outlined
                                :value="user.desc"
                                @input="updateUser('desc', $event)"
                                label="Twój krótki opis"
                        ></v-textarea>
                    </v-col>
                </v-row>
            </v-card-text>

            <v-card-actions>
                <v-btn @click="save()" text>Send</v-btn>
            </v-card-actions>
        </v-card>
    </div>
</template>
<script>
    import UploadImage from 'vue-upload-image';
    import {upload} from "../../api/upload";
    export default {
        components:{UploadImage},
        data(){
            return{
                editUser: {},
            }
        },
        computed:{
            user(){
                return this.$store.getters.user;
            }
        },

        methods:{
            save(){
                this.startLoading();
                this.$store.dispatch('user/update', {data: this.user, id: this.user.id}).then(response => {this.stopLoading();}).catch(e => {this.stopLoading()});
            },
            updateUser(field, event){
                this.$store.commit('user/setField', {field: field, data: event});
            },
            uploadLogo(){
                var formData = new FormData();
                formData.append('files[]', this.$refs.file_input.files[0]);
                this.startLoading();
                upload(formData, 'users').then(response => {
                    this.stopLoading();
                    this.updateUser('avatar', response.data[0]);
                    this.$store.dispatch('user/update', {data: this.user, id: this.user.id})
                })
            }
        }
    }
</script>
