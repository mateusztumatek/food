<template>
  <div id="app">
    <ion-app>
        <div id="menu">
            <my-header></my-header>
            <ion-content class="ion-padding" fullscreen>
                <transition name="slide-fade">
                    <router-view />
                </transition>
            </ion-content>

        </div>
        <sidebar></sidebar>
    </ion-app>
  </div>
</template>
<script>
    import Sidebar from './views/partials/sidebar'
    import myHeader from './views/partials/header';
    export default {
        components:{Sidebar, myHeader},
        mounted() {
        }
    }
</script>
<style lang="scss">
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
        transform: translateX(10px);
        opacity: 0;
    }
#app {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
}

#nav {
  padding: 30px;
}

#nav a {
  font-weight: bold;
  color: #2c3e50;
}

#nav a.router-link-exact-active {
  color: #42b983;
}
</style>
