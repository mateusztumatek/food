<template>
    <div>
        <v-dialog width="70%" v-model="dialog" persistent>
            <v-card>
                <v-card-title>
                    <div class="d-flex w-100" style="justify-content: space-between">
                        <div><span class="headline">{{$t('Edytuj zdjęcie')}}</span></div>
                        <div>
                            <v-btn small class="float-right" @click="reset()" fab icon><v-icon>mdi-close</v-icon></v-btn>
                        </div>
                    </div>
                </v-card-title>
                <v-card-text v-if="options.includeUI.loadImage.path">
                    <img :src="url">
                    <tui-image-editor style="height: 70vh" ref="tuiImageEditor" :include-ui="useDefaultUI" :options="options"></tui-image-editor>
                </v-card-text>
                <v-card-actions>
                    <v-btn :loading="loading" class="w-100" tile color="primary" @click="updateImage()">{{$t('Zapisz')}}</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>
<script>
    import {urltoFile} from "../utilis/helper";
    import {updateFile} from "../api/upload";
    import 'tui-image-editor/dist/tui-image-editor.css';
    import 'tui-color-picker/dist/tui-color-picker.css';
    import ImageEditor from '@toast-ui/vue-image-editor/src/ImageEditor.vue'
    const icona = require('tui-image-editor/dist/svg/icon-a.svg')
    const iconb = require('tui-image-editor/dist/svg/icon-b.svg')
    const iconc = require('tui-image-editor/dist/svg/icon-c.svg')
    const icond = require('tui-image-editor/dist/svg/icon-d.svg')
    //const bg = require('tui-image-editor/examples/img/bg.png')
    var blackTheme = { // or white
        // main icons
        'menu.normalIcon.path': icond,
        'menu.activeIcon.path': iconb,
        'menu.disabledIcon.path': icona,
        'menu.hoverIcon.path': iconc,
    }
    export default {
        components:{
            'tui-image-editor': ImageEditor
        },
        data(){
            return{
                url: null,
                path:null,
                loading: false,
                dialog: false,
                useDefaultUI: true,
                options: { // for tui-image-editor component's "options" prop
                    usageStatistics: false,
                    cssMaxWidth: 700,
                    menuBarPosition: 'bottom',
                    includeUI: {
                        theme: blackTheme, // or white
                        loadImage: {
                            path: null,
                            name: 'SampleImage'
                        },
                    }
                },
            }
        },
        mounted(){
            this.$root.$eventBus.$on('imageEdit', this.openEditor);
        },
        methods:{
            openEditor(file){
                this.dialog = true;
                this.path = file.src;
                this.options.includeUI.loadImage.path = this.$root.base_url+'/stor/'+file.src;
            },
            reset(){
                this.dialog = false;
                this.options.includeUI.loadImage.path = null;
            },
            updateImage(){
                var dataURL = this.$refs.tuiImageEditor.invoke('toDataURL');
                var v = this;
                urltoFile(dataURL, 'file.png')
                    .then(function(file){
                        var data = new FormData();
                        data.append('file', file);
                        data.append('path', v.path);
                        v.loading = true;
                        updateFile(data).then(res => {
                            v.dialog = false;
                            v.reset();
                            v.$store.commit('app/ADD_MESSAGE', v.$t('Zaktualizowano plik poprawnie'));
                            $('img').each((index, image) => {
                                $(image).attr('src', $(image).attr('src')+'?time='+new Date().getTime());
                            })
                            v.$root.$eventBus.$emit('updateImages');
                            v.loading = false;
                        }).catch(e => {
                            v.$store.commit('app/ADD_ERROR', 'Nie udało się zaktualizować pliku');
                            v.loading = false;
                        })
                    })
            }
        }
    }
</script>
<style lang="scss">
    .tui-image-editor-container {
        height: 70vh !important;
    }
    .tui-image-editor-container .tui-image-editor-checkbox input{
        opacity: 1 !important;
    }
    .tui-image-editor-header-logo{
        display: none;
    }
</style>