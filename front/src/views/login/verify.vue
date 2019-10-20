<template>
    <div style="width: 100%">
        <v-card
                style="width: 100%"
                class="mx-auto w-100"
        >
            <v-card-title>Verify your email</v-card-title>
            <v-card-text>If you dont have any massage on your email click link bellow</v-card-text>
            <v-card-actions>
                <v-btn @click="resend()" text>Click</v-btn>
            </v-card-actions>
        </v-card>
    </div>
</template>
<script>
    import {resend} from "../../api/user";

    export default {
        data(){
            return{
                messages: [],
            }
        },
        methods:{
            resend(){
                resend().then(response => {
                    this.messages = response;
                })
            }
        }
    }
</script>