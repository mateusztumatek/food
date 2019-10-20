<template>
    <v-app id="inspire">
        <v-navigation-drawer
                v-model="sidebar.opened"
                :expand-on-hover="!mobile"
                floating
                app
                clipped
        >
            <v-list dense>
                <v-list-item
                        v-for="item in items"
                        :key="item.text"
                        :to="item.to"
                        :exact-active-class="(sidebar.opened)? 'toggle-active': ''"
                        @click=""
                >
                    <v-list-item-action>
                        <v-icon>{{ item.icon }}</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>
                            {{ item.text }}
                        </v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </v-list>
        </v-navigation-drawer>
        <v-app-bar color="transparent" app clipped-left>
                <v-app-bar-nav-icon @click="$store.dispatch('navigation/toggleSideBar')"></v-app-bar-nav-icon>
                <v-toolbar-title>{{title}}</v-toolbar-title>
                <div class="flex-grow-1"></div>
                <v-btn icon>
                    <v-icon>mdi-magnify</v-icon>
                </v-btn>

                <v-btn icon>
                    <v-icon>mdi-heart</v-icon>
                </v-btn>

                <v-btn icon>
                    <v-icon>mdi-dots-vertical</v-icon>
                </v-btn>
        </v-app-bar>
        <v-content>
            <v-container
                    class="fill-height"
                    fluid
            >
                <transition name="fade" mode="out-in">
                  <router-view style="width: 100%"></router-view>
                </transition>
            </v-container>
        </v-content>
    </v-app>
</template>

<script>
    import { mapGetters } from 'vuex';
    export default {
        props: {
            source: String,
        },
        data: () => ({
            open: false,
            items: [
                { icon: 'mdi-account', text: 'Login', to: 'login'},
                { icon: 'mdi-account-badge-horizontal-outline', text: 'Register', to: 'register'}
            ],
            drawer: null,
        }),
        computed:{
            ...mapGetters([
                'sidebar','title','mobile'
            ]),
        },
        mounted() {
        }
    }
</script>
