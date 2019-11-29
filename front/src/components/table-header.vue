<template>
    <div>
        <v-menu
                ref="menu1"
                :close-on-content-click="false"
                transition="scale-transition"
                offset-y
                full-width
                max-width="290px"
                min-width="290px"
        >
            <template v-slot:activator="{ on }">
                <v-text-field
                        v-model="params.date_from"
                        label="Zamówienia od dnia"
                        hint="MM/DD/YYYY format"
                        persistent-hint
                        prepend-icon="event"
                        v-on="on"
                ></v-text-field>
            </template>
            <v-date-picker v-model="params.date_from" no-title @input=""></v-date-picker>
        </v-menu>
    </div>
</template>
<script>
    export default {
        data(){
            return{
                params:{}
            }
        }
    }
</script>