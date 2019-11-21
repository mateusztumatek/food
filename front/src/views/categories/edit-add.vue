<template>
    <v-dialog @click:outside="close()" v-model="visible" max-width="500px">
        <v-card>
            <v-card-title>
                <span class="headline">Kategoria</span>
            </v-card-title>

            <v-card-text>
                <v-form ref="edit_add_form" v-if="editedItem">
                    <v-container>
                        <v-row>
                            <v-col cols="12" class="text-center">
                                <div class="change"><v-btn @click="$refs.file_input.click()" small color="primary">Zmień zdjęcie</v-btn>
                                    <v-btn v-if="editedItem.image" @click="editedItem.image = null" small color="danger">Usuń zdjęcie</v-btn>
                                </div>
                                <input type="file" id="file_input" @change="uploadFile()" style="display: none" ref="file_input"></input>
                                <v-responsive>
                                    <img style="max-width: 40%" v-if="editedItem.image" :src="getSrc(editedItem.image)">
                                </v-responsive>
                            </v-col>
                            <v-col cols="12" md="6">
                                <v-text-field :rules="nameRules" v-model="editedItem.name" label="Nazwa kategorii"></v-text-field>
                            </v-col>
                            <v-col cols="12" md="6">
                                <v-checkbox
                                        v-model="editedItem.active"
                                        label="Aktywna"
                                ></v-checkbox>
                            </v-col>
                            <v-col cols="12">
                                <v-select
                                        :rules="placeRules"
                                        :disabled="(editedItem.id)? true : false"
                                        v-model="editedItem.place_id"
                                        item-value="id"
                                        :items="places"
                                        item-text="name"
                                        label="Miejse"
                                        :loading="loading"
                                ></v-select>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-form>
            </v-card-text>

            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="close()">Zamknij</v-btn>
                <v-btn color="blue darken-1" text @click="save">Zapisz</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
<script>
    import {upload} from "../../api/upload";
    import {editCategory, storeCategory} from "../../api/categories";

    export default {
        props:['dialog'],
        data(){
            return{
                loading: false,
                nameRules: [
                    v => !!v || 'Nazwa jest wymagana',
                ],
                placeRules: [
                    v => !!v || 'Pole te jest wymagane',
                ],
                editedItem:{},
                visible: false
            }
        },
        computed:{
            places(){
                return this.$store.getters.places;
            },
            user(){
                return this.$store.getters.user;
            }
        },
        watch:{
            dialog: function (val) {
                if(val) this.visible = true;
                else this.visible = false;
                this.editedItem = val;
            },
        },
        mounted(){
            this.loading = true;
          this.$store.dispatch('places/getPlaces', {user_id: this.user.id}).then(res => {this.loading = false});
        },
        methods:{

          close(){
              this.$emit('closed');
              this.visible = false;
          },
            uploadFile(){
                var formData = new FormData();
                formData.append('files[]', this.$refs.file_input.files[0]);
                this.startLoading();
                upload(formData, 'categories').then(response => {
                    this.stopLoading();
                    this.$set(this.editedItem, 'image', response.data[0]);
                }).catch(e => {
                    this.$store.commit('app/ADD_ERROR', e.response.data.message);
                    this.stopLoading();
                })
            },
          save(){
            if(this.$refs.edit_add_form.validate()){
                this.startLoading();
                if(this.editedItem.id){
                    editCategory(this.editedItem.id, this.editedItem).then(response => {
                        this.$emit('updated', response);
                        this.close();
                        this.stopLoading();
                    }).catch(e => {
                        this.$store.commit('app/ADD_ERROR', {text: e.response.data.message});
                        this.stopLoading();
                    })
                }else{
                    storeCategory(this.editedItem).then(response => {
                        this.$emit('stored', response);
                        this.close();
                        this.stopLoading();
                    }).catch(e => {
                        this.$store.commit('app/ADD_ERROR', {text: e.response.data.message});
                        this.stopLoading();
                    })
                }
            }
          },
        }
    }
</script>