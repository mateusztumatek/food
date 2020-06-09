<template>
    <div>
        <v-speed-dial
                v-model="open"
                direction="bottom"
        >
            <template v-slot:activator>
                <v-btn
                        icon
                        v-model="open"
                        fab
                >
                    <v-icon v-if="open">mdi-close</v-icon>
                    <v-icon v-else>mdi-flag-variant</v-icon>
                </v-btn>
            </template>
            <v-btn
                    v-for="(lang, key) in langs"
                    fab
                    dark
                    small
                    @click="changeLang(key)"
            >
                <img style="max-width: 30px" :src="$root.base_url+'/images/'+key+'.png'">
            </v-btn>
        </v-speed-dial>
    </div>
</template>
<script>
    export default {
        data(){
            return{
                open: false,
            }
        },
        computed:{
            langs(){
                return this.$store.getters.app.langs;
            },
        },
        methods:{
            changeLang(lang){
                this.$i18n.locale = lang;
                console.log(this.$i18n);
            }
        }
    }
</script>