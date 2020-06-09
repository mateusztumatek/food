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
        <div class="messages">
            <transition-group name="slide-fade">
                <v-alert :key="message.text" dismissible type="success" v-for="message in messages">
                    {{message.text}}
                </v-alert>
            </transition-group>

        </div>
    </div>
</template>
<script>
    export default {
        computed:{
            messages(){return this.$store.getters.app.messages},
            app_errors(){
                return this.$store.getters.app.errors;
            }
        },
        watch:{
            messages: function () {
                if(this.messages.length > 0){
                    setTimeout(() => {
                        this.$store.commit('app/RESET_MESSAGES');
                    }, 4000)
                }
            }
        }
    }
</script>
<style lang="scss">
    .messages{
        position: fixed;
        width: 50%;
        left: 25%;
        bottom: 60px;
        z-index: 100000;
    }
    @media screen and (max-width: 1200px){
        .messages{
            width: 90%;
            left: 5%;
        }
    }
    /* Enter and leave animations can use different */
    /* durations and timing functions.              */
    .slide-fade-enter-active {
        transition: all .3s ease;
    }
    .slide-fade-leave-active {
        transition: all .8s cubic-bezier(1.0, 0.5, 0.8, 1.0);
    }
    .slide-fade-enter, .slide-fade-leave-to
        /* .slide-fade-leave-active below version 2.1.8 */ {
        transform: translateY(40px);
        opacity: 0;
    }
</style>