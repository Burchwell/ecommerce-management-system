<style scoped lang="scss">
</style>
<template>
    <div class="login-wrapper ">
        <div class="bg-pic">
            <div class="bg-caption pull-bottom sm-pull-bottom text-white p-l-20 m-b-20">

            </div>
        </div>
        <b-row class="login-container bg-white float-left">
            <b-col class="p-50 m-t-30 sm-p-l-15 sm-p-r-15 sm-p-t-40">
                <h1 data-v-4f3b21ef="" class="p-t-25" style="margin-left: 210px;background: -webkit-linear-gradient(#800020, #8f0f2f);-webkit-background-clip: text;-webkit-text-fill-color: transparent;font-size: 59px;"><img data-v-4f3b21ef="" src="/images/skar-audio-logo-black.svg" width="300px" style="margin-bottom: -30px;margin-left: -166px;"> <br data-v-4f3b21ef="">
                    Core
                </h1>
                <b-form @submit.prevent="login" method="POST" class="p-t-15">

                    <div class="form-group form-group-default">
                        <label>Login</label>
                        <div class="controls">
                            <b-input-group>
                                <template v-slot:prepend>
                                    <b-input-group-text>
                                        <i class="fas fa-user"/>
                                    </b-input-group-text>
                                </template>
                                <b-form-input
                                    v-model="email"
                                    placeholder="Username"
                                    autocomplete="username email"
                                >
                                </b-form-input>
                            </b-input-group>

                        </div>
                    </div>


                    <div class="form-group form-group-default">
                        <label>Password</label>
                        <div class="controls">
                            <b-input-group>
                                <template v-slot:prepend>
                                    <b-input-group-text>
                                        <i class="fas fa-key"/>
                                    </b-input-group-text>
                                </template>
                                <b-form-input
                                    v-model="password"
                                    prependHtml="<i class='fas fa-user'></i>"
                                    placeholder="Password"
                                    type="password"
                                    autocomplete="curent-password"
                                >
                                </b-form-input>
                            </b-input-group>
                        </div>
                    </div>
                    <b-row>
                        <b-col>
        <span class="text-danger" v-if="showMessage">
                                    {{ this.message }}
                                </span>
                        </b-col>
                    </b-row>
                    <b-row>
                        <b-col>
                            <div class="custom-control custom-switch d-inline-block">
                                <input type="checkbox" class="custom-control-input" id="customSwitch1">
                                <label class="custom-control-label" for="customSwitch1">Remember Me?</label>
                            </div>
                            <a href="/auth/password-reset" class="normal float-right text-secondary">Lost your password?</a>
                        </b-col>
                    </b-row>
                    <b-row>
                        <b-col class="mt-3">
                            <button data-v-4f3b21ef="" type="submit" class="btn px-4 float-right btn-danger" style="
    background: linear-gradient(#800020, #8f0f2f);
    background: #800020;
    border-color: #800020;
">Sign in</button>
                        </b-col>
                    </b-row>
                    <div class="m-b-5 m-t-30">

                    </div>
                </b-form>
            </b-col>

            <div class="pull-bottom sm-pull-bottom p-3">
                <div class="m-b-30 p-r-80 sm-m-t-20 sm-p-r-15 sm-p-b-20 clearfix">
                    <div class="col-sm-9 no-padding m-t-10">
                        <p class="small-text normal hint-text">
                            ©2020 Skar Audio, Inc. All Rights Reserved.
                        </p>
                    </div>
                </div>
            </div>
        </b-row>
    </div>
</template>
<script>

import api from "../../api";

export default {
    name: 'Login',
    components: {},
    data() {
        return {
            email: '',
            password: '',
            showMessage: true,
            message: '',
            backgroundUrl: ""
        }
    },
    methods: {
        async goRegister() {
            await this.$router.push({path: 'register'});
        },
        async login() {
            try {
                const response = await api.post('/auth/login', {
                    email: this.email,
                    password: this.password,
                });
                this.email = '';
                this.password = '';
                localStorage.setItem("access_token", response.data.data.token);
                localStorage.setItem("user", JSON.stringify(response.data.data.user));
                api.defaults.headers.Authorization = `Bearer ${response.data.data.token}`
                await this.$router.push('/dashboard');
            } catch (err) {
                this.message = 'Incorrect E-mail or password';
                this.showMessage
                localStorage.removeItem('user');
                localStorage.removeItem('token');
                console.log(err);
            }
        }
    }
}
</script>
