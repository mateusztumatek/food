<template>
    <div>
        <div class="row mx-0">
            <div class="col-md-3">
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
                                clearable
                                @click:clear="params.date_from = null"
                                prepend-icon="event"
                                v-on="on"
                        ></v-text-field>
                    </template>
                    <v-date-picker v-model="params.date_from" no-title></v-date-picker>
                </v-menu>
            </div>
            <div class="col-md-3">
                <v-menu
                        ref="menu2"
                        :close-on-content-click="false"
                        transition="scale-transition"
                        offset-y
                        full-width
                        max-width="290px"
                        min-width="290px"
                >
                    <template v-slot:activator="{ on }">
                        <v-text-field
                                v-model="params.date_to"
                                label="Zamówienia do dnia"
                                hint="MM/DD/YYYY format"
                                persistent-hint
                                clearable
                                @click:clear="params.date_to = null"
                                prepend-icon="event"
                                v-on="on"
                        ></v-text-field>
                    </template>
                    <v-date-picker v-model="params.date_to" no-title ></v-date-picker>
                </v-menu>
            </div>
            <div class="col-md-3">
                <v-text-field clearable v-model="params.amount_from" type="number" label="Wartość zamówień od"></v-text-field>
            </div>
            <div class="col-md-3">
                <v-text-field clearable v-model="params.amount_to" type="number" label="Wartość zamówień do"></v-text-field>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data(){
            return{
                params:{},
                timeout: null
            }
        },
        watch:{
          params:{
              deep: true,
              handler: function(){
                  if(this.timeout) window.clearTimeout(this.timeout);
                  this.timeout = setTimeout(() => {
                      this.emitValues();
                  },600);
              }
          }
        },
        methods:{
            emitValues(){
                this.$emit('change', this.params);
            }
        }
    }
</script>