<template>
        <ion-header>
            <ion-toolbar color="primary">
                <ion-buttons slot="start">
                    <ion-back-button @click="back()" style="display: block" :icon="'arrow-back'"></ion-back-button>
                    <ion-title>Back Button</ion-title>
                </ion-buttons>
                <ion-buttons slot="end">
                    <ion-menu-button style="display: block"></ion-menu-button>
                </ion-buttons>
            </ion-toolbar>
        </ion-header>
</template>
<script>
    export default {
        methods:{
            back(){
                this.$router.go(-1);
            }
        }
    }
</script>