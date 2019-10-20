<template>
    <div>
        <v-card v-if="place">
            <v-card-title>
                {{place.name}}
            </v-card-title>
        </v-card>
    </div>
</template>
<script>
    export default {
        computed:{
            place(){
                return this.$store.getters.place;
            }
        }
    }
</script>