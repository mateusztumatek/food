<template>
    <div class="w-100">
        <v-card>
            <v-card-text>
                <qrcode-stream @decode="onDecode" @init="onInit"></qrcode-stream>
            </v-card-text>
        </v-card>
    </div>
</template>
<script>
    export default {
        data(){
            return{

            }
        },
        methods:{
            onDecode(decoded){
                window.location.href = decoded;
            },
            async onInit (promise) {
                try {
                    await promise
                } catch (error) {
                    if (error.name === 'NotAllowedError') {
                        this.$store.commit('app/ADD_ERROR', {text: 'Nie masz uprawnień aby korzystać z kamery'});
                    } else if (error.name === 'NotFoundError') {
                        this.$store.commit('app/ADD_ERROR', {text: 'Nie znaleziono kamery w tym urządzeniu'});
                    } else if (error.name === 'NotSupportedError') {
                        this.$store.commit('app/ADD_ERROR', {text: 'Aby korzystać z kamery tego urządzenia musisz być w trybie https'});
                    } else if (error.name === 'NotReadableError') {
                        this.error = "ERROR: is the camera already in use?"
                    } else if (error.name === 'OverconstrainedError') {
                        this.error = "ERROR: installed cameras are not suitable"
                    } else if (error.name === 'StreamApiNotSupportedError') {
                        this.$store.commit('app/ADD_ERROR', {text: 'Błąd w odczycie kamery'});
                    }
                }
            }
        }
    }
</script>