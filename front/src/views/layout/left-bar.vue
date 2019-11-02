<template>
    <div>
        <v-navigation-drawer
                v-model="sidebar.opened"
                app
                clipped
        >
            <v-list dense>
                <v-list-item-group  v-for="item in items"
                              :key="item.text"
                              v-if="item.visible">
                    <v-list-item v-if="!item.items" :to="(item.to && !item.items)? item.to : null">
                        <v-list-item-icon><v-icon v-text="item.icon"></v-icon></v-list-item-icon>
                        <v-list-item-title>{{item.text}}</v-list-item-title>
                    </v-list-item>
                    <v-list-group v-if="item.items">
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
                </v-list-item-group>
            </v-list>
        </v-navigation-drawer>
    </div>
</template>
<script>
    export default {
        props:['open'],
        data(){
            return{
                drawer: false,
                items: [
                    { icon: 'home', text: 'Strona główna', to: '/', visible: true},
                    { icon: 'mdi-alarm-check', text: 'Twoje miejsca', to:'/place', visible: true},
                    { icon: 'mdi-package-variant-closed', text: 'Produkty', to:'/products', visible: true},
                    { icon: 'favorite', text: 'Kategorie', to:'/categories', visible: true,},
                    { icon: 'favorite', text: 'Kategorie2', to:'/cateories', visible: true,
                        items:[
                            {icon: 'home', text: 'Podstrona', to: '/'}
                        ]
                    },
                ],
            }
        },
        computed:{
            sidebar() {
                return this.$store.getters.sidebar;
            }
        }
    }
</script>