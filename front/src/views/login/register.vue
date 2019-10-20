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
                        <v-toolbar-title> <span v-if="!user">Register form</span><span v-else>Zweryfikuj email</span></v-toolbar-title>
                    </v-toolbar>
                    <v-card-text>
                        <v-form
                                ref="form"
                                v-model="valid"
                                lazy-validation
                        >
                            <v-text-field
                                    v-model = "user.login"
                                    label="Login"
                                    name="login"
                                    prepend-icon="person"
                                    :error="(errors.login)? true : false"
                                    :error-messages="errors.login"
                                    :rules="nameRules"
                                    type="text"
                            ></v-text-field>
                            <v-text-field
                                    v-model = "user.email"
                                    label="Email"
                                    :rules="emailRules"
                                    :error="(errors.email)? true : false"
                                    :error-messages="errors.email"
                                    name="email"
                                    prepend-icon="email"
                                    type="email"
                            ></v-text-field>
                            <v-text-field
                                    v-model = "user.password"
                                    id="password"
                                    label="Password"
                                    name="password"
                                    :error="(errors.password)? true : false"
                                    :error-messages="errors.password"
                                    :rules="passwordRules"
                                    prepend-icon="lock"
                                    type="password"
                            ></v-text-field>
                            <v-text-field
                                    v-model = "user.password_confirmation"
                                    id="password"
                                    label="Password confirmation"
                                    name="password"
                                    :error="(errors.password_confirmation)? true : false"
                                    :error-messages="errors.password_confirmation"
                                    prepend-icon="lock"
                                    type="password"
                            ></v-text-field>
                        </v-form>
                    </v-card-text>
                    <v-card-actions>
                        <div class="flex-grow-1"></div>
                        <v-btn color="primary" @click="registerUser()">Register</v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
    </div>
</template>
<script>
    import {mapActions} from 'vuex';
    export default {
        data(){
            var strongRegex = new RegExp("^(((?=.*[a-z])(?=.*[A-Z]))|((?=.*[a-z])(?=.*[0-9]))|((?=.*[A-Z])(?=.*[0-9])))(?=.{6,})");
            return{
                valid: true,
                loading: false,
                user:{},
                errors:[],
                nameRules: [
                    v => !!v || 'Name is required',
                    v => (v && v.length <= 25) || 'Name must be less than 25 characters',
                ],
                passwordRules: [
                    v => !!v || 'Password is required',
                    v => (strongRegex.test(v)) || 'The password must contain at least 1 lowercase, 1 uppercase, 1 numeric, alphabetical character',
                ],
                emailRules: [
                    v => !!v || 'E-mail is required',
                    v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
                ],
            }
        },
        methods:{
            registerUser(){
                this.loading = true;
                if(this.$refs.form.validate()){
                    this.$store.dispatch('user/register', this.user).then(response => {
                        this.loading = false;
                        this.$router.push('/');
                        this.user = response;
                    }).catch(e => {
                        this.errors = e.response.data.errors;
                        setTimeout(() => {
                            this.errors = [];
                        }, 3500);
                        this.loading = false;
                    })
                }else{
                    this.loading = false;
                }
            }
        }
    }
</script>