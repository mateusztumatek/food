<template>
    <div>
        <v-dialog v-model="dialog" dark fullscreen persistent hide-overlay transition="dialog-bottom-transition">
            <v-card :loading="loading">
                <v-toolbar dark color="primary">
                    <v-btn icon dark @click="close()">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                    <v-toolbar-title>{{$t('Wybierz plik')}}</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                        <v-btn dark text @click="$emit('select')">Save</v-btn>
                    </v-toolbar-items>
                </v-toolbar>
                <div class="row mx-0" v-if="!loading">
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-3" v-for="m in media">
                                <v-card>
                                    <v-img
                                            :src="getImage(m.path)+ '?rnd=' + cacheKey"
                                            class="white--text align-end"
                                            gradient="to bottom, rgba(0,0,0,.1), rgba(0,0,0,.5)"
                                            height="200px"
                                            v-if="!loading"
                                    >
                                        <v-card-title>
                                            <v-btn :loading="uploading" class="ma-1" color="primary" @click="select(m)">{{$t('Wybierz plik')}}</v-btn>
                                            <div>
                                                <v-btn :loading="uploading" class="ma-1" color="red" fab small @click="remove(m)"><v-icon>mdi-trash-can-outline</v-icon></v-btn>
                                                <v-btn :loading="uploading" class="ma-1" color="primary" fab small @click="$root.$eventBus.$emit('imageEdit', {src: m.path})"><v-icon>mdi-pencil</v-icon></v-btn>
                                            </div>
                                        </v-card-title>
                                    </v-img>
                                </v-card>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <input type="file" id="file_input" @change="uploadFile()" style="display: none" ref="file_input"></input>
                        <v-btn :loading="uploading" outlined tile min-height="120px" class="w-100" @click="$refs.file_input.click()">{{$t('Wgraj plik')}}</v-btn>
                    </div>
                </div>
            </v-card>
        </v-dialog>
    </div>
</template>
<script>
    import {getUserUploads, removeFile} from "../api/upload";
    import {upload} from "../api/upload";

    export default {
        data(){
            return{
                cacheKey: null,
                dialog: true,
                media: null,
                uploading: false,
                loading: false,
            }
        },
        mounted() {
            this.$root.$eventBus.$on('updateImages', () => {
                this.loading = true;
                this.cacheKey = Math.round(Math.random() * 10);
                setTimeout(() => {
                    this.loading = false;
                }, 400)
            })
            this.getMedia();
        },
        methods:{
            remove(media){
                this.$confirm(this.$t('Czy na pewno chcesz usunąć ten plik')).then(() => {
                    this.uploading = true;
                    removeFile({path: media.path}).then(response => {
                        this.uploading = false;
                        this.media.splice(this.media.findIndex(x => x.path == media.path), 1);
                    })
                })
            },
            uploadFile(event){
                var formData = new FormData();
                formData.append('files[]', this.$refs.file_input.files[0]);
                this.uploading = true;
                upload(formData, 'media').then(response => {
                    this.uploading = false;
                    this.media.push({path: response.data[0]});
                }).catch(e => {
                    this.uploading = false;
                })
            },
            getImage(src){
                return this.$root.base_url+'/storage/'+src;
            },
            select(media){
                this.dialog = false;
                this.$emit('select', media.path);
            },
            close(){
                this.dialog = false;
                this.$emit('closeMe');
            },
            getMedia(){
                getUserUploads().then(response => {
                    this.media = response;
                    if(typeof this.media == 'object') this.media = Object.values(this.media);
                }).catch(e => {
                    this.$store.commit('app/ADD_ERROR', {text: 'Nie udało się pobrać plików'});
                })
            }
        }
    }
</script>