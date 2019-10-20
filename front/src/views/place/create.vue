<template>
    <div class="w-100">
        <v-card>
            <v-card-title>Tworzenie nowego miejsca</v-card-title>
            <v-card-text>
                <v-row justify="center">
                    <v-col cols="12" class="text-center">
                        <v-avatar class="change-avatar" color="indigo" size="200">
                            <img
                                    :src="place.image"
                            >
                            <div class="change"><v-btn @click="$refs.file_input.click()" small color="primary">Zmień</v-btn></div>
                            <input type="file" id="file_input" @change="uploadImage()" style="display: none" ref="file_input"></input>
                        </v-avatar>
                    </v-col>
                    <v-col cols="12">
                        <v-text-field v-model="place.name" label="Nazwa miejsca"></v-text-field>
                    </v-col>
                    <v-col cols="12">
                        <v-text-field v-model="place.description" label="Opis miejsca"></v-text-field>
                    </v-col>

                    <v-col cols="12">
                        <v-textarea
                                outlined
                                v-model="place.description"
                                label="Opis miejsca"
                        ></v-textarea>
                    </v-col>
                </v-row>
            </v-card-text>
        </v-card>
    </div>
</template>
<script>
    export default {
        data(){
            return{
               place:{},
            }
        }
    }
</script>