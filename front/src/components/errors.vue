<template>
    <div>
        <v-snackbar
                :value="true"
                v-for="error in app_errors"
        >
            {{ error.text }}
            <v-btn
                    color="red"
                    @click="$store.commit('app/CLOSE_ERROR', error)"
            >
                Close
            </v-btn>
        </v-snackbar>
    </div>
</template>
<script>
    export default {
        computed:{
            app_errors(){
                return this.$store.getters.app.errors;
            }
        },
        watch:{
            app_errors: function () {
            }
        }
    }
</script>