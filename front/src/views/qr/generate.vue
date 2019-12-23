<template>
    <div class="w-100">
        <v-card>
            <v-card-title>Generuj QR kod</v-card-title>
            <v-card-text>
                <v-select label="wybierz miejsce" item-text="name" return-object :items="sellouts" v-model="selected">
                </v-select>
                <div class="give-me-space" v-if="selected">
                    <div class="qr-holder">
                        <qr-code
                                :text="selected.url"
                                color="black"
                                bg-color="white"
                                error-level="L">
                        </qr-code>
                    </div>
                </div>
            </v-card-text>
            <v-card-actions style="flex-wrap: wrap" v-if="selected">
                <div class="col-md-6">
                    <v-btn link class="w-100" :href="$root.base_url+'/sellout/'+selected.id+'/qr'">Pobierz QR Kod</v-btn>
                </div>
                <div class="col-md-6">
                    <v-btn link class="w-100" :href="$root.base_url+'/sellout/'+selected.id+'/pdf'">Pobierz Broszurę</v-btn>
                </div>
            </v-card-actions>
        </v-card>
    </div>
</template>
<script>
    export default {
        data(){
            return{
                selected: null
            }
        },
        computed:{
            sellouts(){return this.$store.getters.sellouts}
        },
        mounted() {
            console.log(this.sellouts.url);
        }
    }
</script>
<style lang="scss">
    .qr-holder{
        padding: 15px;
        background-color: white;
        width: fit-content;
        margin: auto;
    }
</style>