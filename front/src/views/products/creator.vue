<template>
    <div>
        <v-dialog :value="visible" @click:outside="$emit('close')" :width="($root.isMobile())? '100%' : '70%'">
            <v-card>
                <v-card-title>
                    <span class="headline">Dodaj produkt</span>
                </v-card-title>
                <v-card-text>
                    <v-stepper non-linear
                               style="background-color: transparent; box-shadow: none !important;"
                               v-model="step">
                        <v-stepper-header>
                            <v-stepper-step editable @click="step=1" step="1">Name of step 1</v-stepper-step>

                            <v-divider></v-divider>

                            <v-stepper-step editable @click="step=2" step="2">Name of step 2</v-stepper-step>

                            <v-divider></v-divider>

                            <v-stepper-step editable step="3" @click="step=3">Name of step 3</v-stepper-step>
                        </v-stepper-header>

                        <v-stepper-items>
                            <v-stepper-content step="1">
                                <v-card
                                        class="mb-12"
                                        color="grey lighten-1"
                                        height="200px"
                                ></v-card>

                                <v-btn
                                        color="primary"
                                        @click="step = 2"
                                >
                                    Continue
                                </v-btn>

                                <v-btn text>Cancel</v-btn>
                            </v-stepper-content>

                            <v-stepper-content step="2">
                                <v-card
                                        class="mb-12"
                                        color="grey lighten-1"
                                        height="200px"
                                ></v-card>

                                <v-btn
                                        color="primary"
                                >
                                    Continue
                                </v-btn>

                                <v-btn text>Cancel</v-btn>
                            </v-stepper-content>

                            <v-stepper-content step="3">
                                <v-card
                                        class="mb-12"
                                        color="grey lighten-1"
                                        height="200px"
                                ></v-card>

                                <v-btn
                                        color="primary"
                                >
                                    Continue
                                </v-btn>

                                <v-btn text>Cancel</v-btn>
                            </v-stepper-content>
                        </v-stepper-items>
                    </v-stepper>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="$emit('close')">Close</v-btn>
                    <v-btn color="blue darken-1" text @click="$emit('close')">Save</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>
<script>
    export default {
        props:['visible'],
        data(){
            return{
                step: 1,
                product: {}
            }
        }
    }
</script>