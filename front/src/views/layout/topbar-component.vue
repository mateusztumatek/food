<template>
    <div>
        <v-app-bar
                app
                clipped-left
                color="red"
                class="my-header"
                v-bind:class="{'searching': search.isSearch}"
                dense
        >
            <v-app-bar-nav-icon @click="toggleSidebar()"></v-app-bar-nav-icon>
                <v-toolbar-items v-bind:class="{'w-100': $root.isMobile()}">
                    <autocomplete></autocomplete>
                </v-toolbar-items>
                <v-spacer></v-spacer>
                <v-toolbar-items>
                    <v-btn icon @click="fullScreen()">
                        <v-icon>fullscreen</v-icon>
                    </v-btn>
                    <v-menu offset-y origin="center center" transition="scale-transition">
                        <template v-slot:activator="{ on }">
                            <v-btn v-on="on" icon>
                                <v-badge color="red" overlap>
                                    <span slot="badge">3</span>
                                    <v-icon medium>notifications</v-icon>
                                </v-badge>
                            </v-btn>
                        </template>
                        <notification-list></notification-list>
                    </v-menu>
                    <v-menu offset-y origin="center center" :nudge-bottom="10" transition="scale-transition">
                        <template v-slot:activator="{ on }">
                            <v-btn v-on="on" icon large >
                                <v-avatar size="30px">
                                    <img v-if="user.avatar" :src="$root.getSrc(user.avatar)" alt="Michael Wang" />
                                </v-avatar>
                            </v-btn>
                        </template>

                        <v-list class="pa-0">
                            <v-list-item
                                    v-for="(item, i) in menu_items"
                                    :key="i"
                                    @click="item.handler"
                            >
                                <v-list-item-icon>
                                    <v-icon v-text="item.icon"></v-icon>
                                </v-list-item-icon>
                                <v-list-item-content>
                                    <v-list-item-title v-text="item.name"></v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                        </v-list>
                    </v-menu>
                </v-toolbar-items>
        </v-app-bar>
    </div>
</template>
<script>
    import { mapGetters, mapActions} from 'vuex';
    import Notification from '../../components/notifications';
    import Autocomplete from './autocomplete';
    import util from '../../utilis';
    export default {
        components:{
          NotificationList: Notification,
            Autocomplete
        },
        data(){
            return{
                menu_items:[
                    {
                        name: 'Edytuj konto',
                        icon: 'mdi-account-edit',
                        handler: () => {this.$router.push('/user/edit')},
                    },
                    {
                        name: 'Dodaj miejsce',
                        icon: 'mdi-account-edit',
                        handler: () => {this.$router.push('/place/create')},
                    },
                    {
                        name: 'logout',
                        icon: 'mdi-logout',
                        handler: () => {this.logout()},
                    }
                ]
            }
        },
        computed:{
            ...mapGetters([
                'user', 'search',
            ]),
        },
        methods:{
          toggleSidebar(){
            this.$store.dispatch('navigation/toggleSideBar');
          },
          fullScreen(){
           util.toggleFullScreen();
          },
          logout(){
              this.$store.dispatch('user/logout');
          }
        }
    }
</script>
<style lang="scss">
    .my-header{
        .v-toolbar__content{
/*
            transition: all 300ms;
*/
        }
    }
    .searching{
        .v-toolbar__content{
            transform: translateX(-40px);
            width: 150%;
        }
    }

</style>