<template>
    <v-app id="inspire">
        <my-leftbar :open="open_leftbar"></my-leftbar>
        <my-topbar></my-topbar>
        <v-content>
            <v-container class="fill-height">
                <transition name="fade" mode="out-in">
                    <router-view :key="key" />
                </transition>
            </v-container>
        </v-content>
        <div class="loading-container" v-if="app.loading">
            <div class="scaling-squares-spinner" >
                <div class="square"></div>
                <div class="square"></div>
                <div class="square"></div>
                <div class="square"></div>
            </div>
        </div>
        <errors-component></errors-component>
    </v-app>
</template>

<script>
    import myHeader from './views/layout/left-bar';
    import myTopbar from './views/layout/topbar-component';
    import ErrorsComponent from './components/errors';
    export default {
        name: 'App',
        components:{
            myLeftbar: myHeader,
            myTopbar: myTopbar,
            ErrorsComponent
        },
        computed: {
            app(){
              return this.$store.getters.app;
            },
            key() {
                return this.$route.fullPath;
            },
        },
        mounted(){
            this.$root.$eventBus.$on('left_bar', () => {
                console.log('fwafwa');
                this.open_leftbar = !this.open_leftbar;
            })
        },
        data(){
            return{

                open_leftbar: false,
            }
        }
    };
</script>
