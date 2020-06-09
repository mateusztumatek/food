<template>
    <ion-menu side="start" type="push" content-id="menu">
        <ion-header>
            <ion-toolbar color="danger">
                <ion-title>End Menu</ion-title>
            </ion-toolbar>
        </ion-header>
        <ion-content>
            <ion-list>
                <ion-item>
                    <router-link to="about">
                        <ion-icon  name="home" slot="start"></ion-icon>
                        <ion-label>Home</ion-label>
                    </router-link>
                </ion-item>
                <ion-item clickable="true" href="/about">
                    <ion-icon  name="home" slot="start"></ion-icon>
                    <ion-label>Home</ion-label>
                </ion-item>
                <ion-item clickable="true" href="/about">
                    <ion-icon  name="home" slot="start"></ion-icon>
                    <ion-label>Home</ion-label>
                </ion-item>
                <ion-item clickable="true" href="/about">
                    <ion-icon  name="home" slot="start"></ion-icon>
                    <ion-label>Home</ion-label>
                </ion-item>
            </ion-list>
        </ion-content>
    </ion-menu>
</template>
<script>
    export default {
        mounted() {
            console.log(this.$route)
        }
    }
</script>
