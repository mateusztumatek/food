<template>
    <div>
        <v-card class="mx-auto"
                max-width="550"
                outlined>
        <v-toolbar card dense color="transparent">
            <v-toolbar-title><h4>{{$t('Powiadomienia')}}</h4></v-toolbar-title>
        </v-toolbar>
        <v-divider></v-divider>
        <v-card-text>
            <v-list two-line>
                <v-list-item-group
                        multiple
                        active-class="pink--text"
                >
                    <template v-for="(item, index) in notifications">
                        <v-list-item>
                            <template v-slot:default="{ active, toggle }">
                                <v-list-item-content>
                                    <v-list-item-title v-text="item.title"></v-list-item-title>
                                    <v-list-item-subtitle class="text--primary" v-text="item.data.text"></v-list-item-subtitle>
                                    <v-list-item-subtitle v-text="item.subtitle"></v-list-item-subtitle>
                                </v-list-item-content>

                                <v-list-item-action>
                                    <v-list-item-action-text v-text="item.action"></v-list-item-action-text>
                                    <v-icon
                                            v-if="!active"
                                            color="grey lighten-1"
                                    >
                                        star_border
                                    </v-icon>

                                    <v-icon
                                            v-else
                                            color="yellow"
                                    >
                                        star
                                    </v-icon>
                                </v-list-item-action>
                            </template>
                        </v-list-item>
                    </template>
                </v-list-item-group>
            </v-list>
            <v-divider></v-divider>
            <v-btn block class="ma-0">All</v-btn>
            <v-divider></v-divider>
        </v-card-text>
     </v-card>
    </div>

</template>

<script>
    export default {
        data: () => ({

        }),
        computed:{
            notifications(){
                return this.$store.getters.notifications;
            }
        }
    }
</script>