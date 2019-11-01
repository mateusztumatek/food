<template>
    <div>
        <v-dialog :value="visible" @click:outside="$emit('close')" :width="($root.isMobile())? '100%' : '70%'">
            <v-card>
                <v-card-title>
                    <span class="headline">Dodaj produkt</span>
                </v-card-title>
                <v-card-text>
                    <v-stepper
                               style="background-color: transparent; box-shadow: none !important;"
                               v-model="step">
                        <v-stepper-header>
                            <v-stepper-step :complete="dones[1]" editable @click="step=1" step="1">Dane usługi / produktu</v-stepper-step>

                            <v-divider></v-divider>

                            <v-stepper-step :complete="dones[2]" editable @click="(dones[2])? step=2 : null" step="2">Kategoria</v-stepper-step>

                            <v-divider></v-divider>

                            <v-stepper-step :complete="dones[3]" editable step="3" @click="(dones[3])? step=3 : null">Cena i czas przygotowania</v-stepper-step>
                        </v-stepper-header>

                        <v-stepper-items>
                            <v-stepper-content step="1">
                                <first v-on:next_step="nextStep" v-on:done="done"></first>
                            </v-stepper-content>

                            <v-stepper-content step="2">
                                <second  v-on:next_step="nextStep" v-on:done="done"></second>
                            </v-stepper-content>

                            <v-stepper-content step="3">
                                <third v-on:next_step="nextStep" v-on:done="done"></third>
                            </v-stepper-content>
                        </v-stepper-items>
                    </v-stepper>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="$emit('close')">Zamknij</v-btn>
                    <v-btn color="blue darken-1" v-if="isDone() || product.id" text @click="save()">Zapisz</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>
<script>
    import First from './create-partials/first';
    import Second from './create-partials/second';
    import Third from './create-partials/third';

    export default {
        name: 'Product_creator',
        components:{
          First, Second, Third
        },
        props:['visible'],
        computed:{
          product(){return this.$store.getters.products.new_product}
        },
        data(){
            return{
                step: 1,
                dones:{},
            }
        },
        methods:{
            isDone(){
              if(this.dones[1] && this.dones[2] && this.dones[3]) return true;
              return false;
            },
            done(step, value){
                this.$set(this.dones, step, value);
            },
            nextStep(value){
                this.step = value;
            },
            save(){
                this.startLoading();
                if(this.product.id) var dispatch = 'products/editProduct';
                else dispatch = 'products/saveProduct';
                this.$store.dispatch(dispatch).then(response => {
                    this.$emit('close');
                    this.stopLoading();
                }).catch(e => {
                    this.stopLoading();
                });
            }
        }
    }
</script>