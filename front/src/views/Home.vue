<template>
     <div style="width: 100%">
          <v-card
                  hover
                  style="width: 100%"
                  class="mx-auto w-100 my-2"
                  link
                  to="/localization"
          >
               <v-card-text>
                    <div class="w-100 text-center"  style="padding: 20px 0px">
                         <h2>{{$t('Wyszukaj w pobliżu')}}</h2>
                         <img class="my-3" style="max-width: 30%" src="/img/local.svg">
                    </div>
               </v-card-text>
          </v-card>
          <v-card
                  style="width: 100%"
                  class="mx-auto w-100 my-2"
                  hover
                  link
                  to="/qr"
          >
               <v-card-text>
                    <div class="w-100 text-center"  style="padding: 20px 0px">
                         <h2>{{$t('Skanuj QR kod')}}</h2>
                         <img class="my-3" style="max-width: 30%" src="/img/qr-code.svg">
                    </div>
               </v-card-text>
          </v-card>
     </div>
</template>

<script>
import {exampleRequest} from "../api/user";

export default {
    mounted() {

    },
     methods:{
         sendNot(){

         }
     }
};
</script>
