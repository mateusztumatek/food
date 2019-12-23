<template>
    <v-app id="inspire">
        <my-leftbar :open="open_leftbar"></my-leftbar>
        <my-topbar></my-topbar>
        <v-content>
            <my-header v-if="header"></my-header>
            <v-container style="align-items: start" v-bind:class="{'px-0 pt-0': $route.meta.fullWidth}">
                <div class="my-container" v-bind:class="{'full-width': $route.meta.fullWidth}">
                    <transition name="fade" mode="out-in">
                        <router-view :key="key" />
                    </transition>
                </div>
            </v-container>
        </v-content>
        <my-footer></my-footer>
        <div class="loading-container" v-if="app.loading">
            <div class="scaling-squares-spinner" >
                <div class="square"></div>
                <div class="square"></div>
                <div class="square"></div>
                <div class="square"></div>
            </div>
        </div>
        <ProductView></ProductView>
        <cart-component></cart-component>
        <errors-component></errors-component>
        <image-editor></image-editor>
    </v-app>
</template>

<script>
    import myHeader from './views/layout/header';
    import myLeftBar from './views/layout/left-bar';
    import myTopbar from './views/layout/topbar-component';
    import ErrorsComponent from './components/errors';
    import myFooter from './views/layout/footer';
    import ProductView from './views/products/show';
    import CartComponent from './views/cart/index';
    import ImageEditor from './components/image-editor';
    export default {
        name: 'App',
        components:{
            ImageEditor,
            myLeftbar: myLeftBar ,
            myTopbar: myTopbar,
            ErrorsComponent,
            myFooter,
            myHeader,
            ProductView,
            CartComponent
        },
        computed: {
            app(){
              return this.$store.getters.app;
            },
            key() {
                return this.$route.fullPath;
            },
            header(){
                return this.$store.getters.header;
            }
        },
        mounted(){
            this.$root.$eventBus.$on('left_bar', () => {
                console.log('fwafwa');
                this.open_leftbar = !this.open_leftbar;
            })
            console.log(this.$route);
        },
        data(){
            return{

                open_leftbar: false,
            }
        }
    };
</script>
<style lang="scss">
    @media screen and (max-width: 1200px){
        .my-container{
            width: 100%;
            max-width: 100%;
        }
    }

</style>