<template>
    <div>
        <v-app-bar elevation="0">
            <v-app-bar-nav-icon @click="$router.go(-1)"><v-icon>mdi-arrow-left</v-icon></v-app-bar-nav-icon>
            <v-toolbar-title>{{header.title}}</v-toolbar-title>
        </v-app-bar>
    </div>
</template>
<script>
    export default {
        computed:{
            header(){return this.$store.getters.header}
        }
    }
</script>