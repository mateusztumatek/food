<template>
    <div>
        <v-navigation-drawer
                v-model="sidebar.opened"
                app
                clipped
        >
            <v-list dense>
                <v-list-group
                        v-for="item in items"
                        :key="item.text"
                        v-if="item.visible"
                        :to="(item.to)? item.to : null"
                >
                    <template v-slot:activator>
                        <v-list-item-title>{{item.icon}}</v-list-item-title>
                    </template>
                    <v-list-item-icon>
                        <v-icon></v-icon>
                    </v-list-item-icon>
                    <v-list-item-content>
                        <v-list-item-title>
                            {{ item.text }}
                        </v-list-item-title>
                        <v-list-group
                                prepend-icon="account_circle"
                                value="true"
                        >


                            <v-list-item>
                                <v-list-item
                                        link>
                                    <v-list-item-icon>
                                        <v-icon>mdi-home</v-icon>
                                    </v-list-item-icon>
                                    <v-list-item-title> text</v-list-item-title>
                                </v-list-item>
                            </v-list-item>
                        </v-list-group>
                    </v-list-item-content>
                </v-list-group>
                <v-subheader class="mt-4 grey--text text--darken-1">SUBSCRIPTIONS</v-subheader>
                <v-list>
                    <v-list-item
                            v-for="item in items2"
                            :key="item.text"
                            @click=""
                    >
                        <v-list-item-avatar>
                            <img
                                    :src="`https://randomuser.me/api/portraits/men/${item.picture}.jpg`"
                                    alt=""
                            >
                        </v-list-item-avatar>
                        <v-list-item-title v-text="item.text"></v-list-item-title>
                    </v-list-item>
                </v-list>
                <v-list-item
                        class="mt-4"
                        @click=""
                >
                    <v-list-item-action>
                        <v-icon color="grey darken-1">mdi-plus-circle-outline</v-icon>
                    </v-list-item-action>
                    <v-list-item-title class="grey--text text--darken-1">Browse Channels</v-list-item-title>
                </v-list-item>
                <v-list-item @click="">
                    <v-list-item-action>
                        <v-icon color="grey darken-1">mdi-settings</v-icon>
                    </v-list-item-action>
                    <v-list-item-title class="grey--text text--darken-1">Manage Subscriptions</v-list-item-title>
                </v-list-item>
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
                    { icon: 'favorite', text: 'Kategorie', to:'/wishlist', visible: true },
                ],
                items2: [
                    { picture: 28, text: 'Joseph' },
                    { picture: 38, text: 'Apple' },
                    { picture: 48, text: 'Xbox Ahoy' },
                    { picture: 58, text: 'Nokia' },
                    { picture: 78, text: 'MKBHD' },
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