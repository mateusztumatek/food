<template>
    <div>
        <v-row
                align="center"
                justify="center"
        >
            <v-col
                    cols="12"
                    sm="8"
                    md="4"
            >
                <v-card class="elevation-12">
                    <v-toolbar
                            color="primary"
                            dark
                            flat>
                        <v-toolbar-title>Login form</v-toolbar-title>
                    </v-toolbar>
                    <v-card-text>
                        <v-form>
                            <v-text-field
                                    v-model = "user.login"
                                    label="Login"
                                    name="login"
                                    :error="(errors.login)? true : false"
                                    :error-messages="errors.login"
                                    prepend-icon="person"
                                    type="text"
                            ></v-text-field>
                            <v-text-field
                                    v-model = "user.password"
                                    id="password"
                                    :error="(errors.password)? true : false"
                                    :error-messages="errors.password"
                                    label="Password"
                                    name="password"
                                    prepend-icon="lock"
                                    type="password"
                            ></v-text-field>
                        </v-form>

                    </v-card-text>
                    <v-card-actions>
                        <div class="flex-grow-1"></div>
                        <v-btn color="primary" @click="loginUser()">Login</v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
    </div>
</template>
<script>
    import {exampleRequest} from "../../api/user";
    import { mapGetters } from 'vuex';
    import {upload} from "../../api/upload";

    export default {
        data(){
            return{
                user:{},
                errors:[]
            }
        },
        methods:{
            loginUser(){
                this.startLoading();
                this.$store.dispatch('user/login', this.user).then(response => {
                    this.$store.dispatch('order/getUserOrders');
                    this.$router.push({ path: this.redirect || '/' });
                    this.stopLoading();
                }).catch(e => {
                    this.stopLoading();
                    this.errors = e.response.data.errors;
                    setTimeout(() => {
                        this.errors = [];
                    }, 3000)
                })
            },


        }
    }
</script>