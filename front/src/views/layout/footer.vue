<template>
    <v-scroll-x-transition v-if="activeSellout">
        <v-bottom-navigation
                app
                grow
                color="primary"
        >
            <v-btn :to="'/sellout/'+activeSellout.id+'/manage'">
                <span>Zamówienia</span>
                <v-icon>mdi-cart</v-icon>
            </v-btn>

            <v-btn :to="/sellout/+activeSellout.id+'/settings'">
                <span>Ustawienia</span>
                <v-icon>mdi-settings</v-icon>
            </v-btn>

            <v-btn>
                <span>Czas realizacji</span>
                <v-icon>mdi-clock</v-icon>
            </v-btn>
        </v-bottom-navigation>
    </v-scroll-x-transition>
</template>
<script>
    export default {
        data(){
            return{
                activeBtn: 2
            }
        },
        computed:{
            activeSellout(){return this.$store.getters.activeSellout}
        }
    }
</script>