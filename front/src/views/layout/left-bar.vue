<template>
    <div>
        <v-navigation-drawer
                v-model="sidebar.opened"
                app
                clipped

        >
            <v-list dense>
                <v-list-item-group
                            v-for="item in items"
                              :key="item.text"
                              v-if="item.visible && checkItem(item)">
                    <v-list-item @mouseover="hovered($event)" @mouseleave="hovered(null)" v-if="!item.items && item.type != 'subtitle'" :to="(item.to && !item.items)? item.to : null">
                        <v-list-item-icon><v-icon v-text="item.icon"></v-icon></v-list-item-icon>
                        <v-list-item-title>{{item.text}}</v-list-item-title>
                    </v-list-item>
                    <v-list-group v-if="item.items && item.type != 'subtitle'">
                        <template v-slot:activator>
                            <v-list-item-icon><v-icon v-text="item.icon"></v-icon></v-list-item-icon>
                            <v-list-item-title>{{item.icon}}</v-list-item-title>
                        </template>
                        <v-list-item v-for="subitem in item.items" :to="subitem.to">
                            <v-list-item-content>
                                <v-list-item-title>{{subitem.text}}</v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </v-list-group>
                    <v-subheader class="mt-4 grey--text text--darken-1" v-if="item.type == 'subtitle'">{{item.text}}</v-subheader>
                </v-list-item-group>
                <div class="navigation-tick"></div>
            </v-list>
            <div v-if="user.id && places.length > 0">
                <v-subheader class="mt-4 grey--text text--darken-1">Sprzedaże</v-subheader>
                <v-list>
                    <v-list-item
                            v-for="sellout in sellouts"
                            :key="sellout.id"
                            :to="'/sellout/'+sellout.id+'/manage'"
                    >
                        <v-list-item-title v-bind:class="{'red--text': !sellout.is_attempted && sellout.id != activeSellout.id}">{{sellout.name}}<span v-if="!sellout.is_attempted && sellout.id != activeSellout.id" style="font-weight: 900" class="caption float-right">Nie aktywna</span></v-list-item-title>
                    </v-list-item>
                </v-list>
                <div class="text-center">
                    <v-btn color="primary" to="/sellout/create"><v-icon>mdi-cart-minus</v-icon>Rozpocznij sprzedaż</v-btn>
                </div>
            </div>

        </v-navigation-drawer>
    </div>
</template>
<script>
    export default {
        props:['open'],
        watch:{
            $route:{
                handler(){
                    this.setTickHeight();
                },
                deep: true
            }
        },
        data(){
            return{
                drawer: false,
                items: [
                    { icon: 'home', text: 'Strona główna', to: '/', visible: true},
                    { icon: 'mdi-account-badge-horizontal', text: 'Załóż konto', guest: true, to: '/register', visible: true},
                    { icon: 'mdi-account', text: 'Zaloguj się', to: '/login', guest: true, visible: true},
                    {type: 'subtitle', text: 'Manage your shop', visible: true, hasPlace: true},
                    { icon: 'mdi-alarm-check', text: 'Twoje miejsca', to:'/place', visible: true, hasPlace: true},
                    { icon: 'mdi-package-variant-closed', text: 'Produkty', to:'/products', visible: true, hasPlace:true},
                    { icon: 'favorite', text: 'Kategorie', to:'/categories', visible: true, hasPlace: true},
                    { icon: 'favorite', text: 'Kategorie2', to:'/cateories', visible: true, hasPlace: true,
                        items:[
                            {icon: 'home', text: 'Podstrona', to: '/'}
                        ]
                    },
                ],
            }
        },
        computed:{
            user() {return this.$store.getters.user},
            places() {return this.$store.getters.places},
            sellouts() {return this.$store.getters.sellouts},
            activeSellout() {return (this.$store.getters.activeSellout)? this.$store.getters.activeSellout : {}},
            sidebar() {
                return this.$store.getters.sidebar;
            }
        },
        mounted(){
            this.setTickHeight();
        },
        methods:{
            hovered(item){
                if(item){
                    this.setTickHeight(item.target);
                }else{
                    this.setTickHeight();
                }
            },
            setTickHeight(item = null){
                setTimeout(() => {
                    $('.navigation-tick').height($('.v-navigation-drawer__content').find('.v-item-group').first().height());
                    if(item){
                        if($(item).hasClass('v-list-item')){
                            $('.navigation-tick').css('opacity', 1);
                            $('.navigation-tick').css('top', $(item).offset().top - $('.v-navigation-drawer').offset().top);
                        }
                    }else{
                        if(typeof $('.v-item--active') != 'undefined' && $('.v-item--active').length){
                            $('.navigation-tick').css('opacity', 1);
                            $('.navigation-tick').css('top', $('.v-item--active').offset().top - $('.v-navigation-drawer').offset().top);
                        }else{
                            $('.navigation-tick').css('opacity', 0);
                        }
                    }
                }, 100)
            },
            checkItem(item){
                if(item.guest && this.user.id) return false;
                if(item.hasPlace){
                    if(!this.user.id) return false
                    if(this.places.length == 0) return false;
                    return true;
                }else{
                    return true;
                }
            }
        }
    }
</script>