<template>
    <div>
        <v-dialog v-model="visible" max-width="290">
            <v-card>
                <v-card-title class="headline">{{title}}</v-card-title>
                <v-card-text>{{message}}</v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn text @click="$emit('cancel')">Disagree</v-btn>
                    <v-btn text @click="$emit('confirm')">Agree</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>
<script>
    export default {
        props: ['title', 'message'],
        data(){
            return{
                visible: false,
            }
        },
        mounted() {
            this.visible = true;
        }
    }
</script>