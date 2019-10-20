<template>
    <div style="width: 100%">
        <v-card
                style="width: 100%"
                class="mx-auto w-100"
        >
            <v-card-title>Send notification</v-card-title>
            <v-card-text>If you dont have any massage on your email click link bellow</v-card-text>
            <v-card-actions>
                <v-btn @click="sendNot()" text>Send</v-btn>
            </v-card-actions>
        </v-card>
    </div>
</template>
<script>
    export default {

    }
</script>