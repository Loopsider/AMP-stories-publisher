<template>
    <div class="container">
        <b-row class="justify-content-center">
            <b-col md="5">
                <form novalidate class="md-layout" @submit.prevent="validateUser">
                    <md-card class="md-layout-item">
                        <md-progress-bar md-mode="indeterminate" v-if="sending"/>
                        <md-card-header>
                            <div class="md-title">User Login</div>
                        </md-card-header>
                        <md-card-content>
                            <md-field :class="getValidationClass('email')">
                                <label for="email">Email</label>
                                <md-input type="email" name="email" id="email" autocomplete="email" v-model="form.email"
                                          :disabled="sending"/>
                                <span class="md-error" v-if="!$v.form.email.required">The email is required</span>
                                <span class="md-error" v-else-if="!$v.form.email.email">Invalid email</span>
                            </md-field>
                            <md-field :class="getValidationClass('email')">
                                <label for="password">Password</label>
                                <md-input type="password" name="password" id="password" autocomplete="password"
                                          v-model="form.password" :disabled="sending"/>
                                <span class="md-error" v-if="!$v.form.password.required">The email is required</span>
                            </md-field>
                            <md-checkbox v-model="remember" class="md-primary">Remember me</md-checkbox>
                            <br>
                            <b-button variant="link" class="px-0">Forgot password?</b-button>
                            <b-button variant="link" to="/auth/register" class="px-0 float-right">Register now
                            </b-button>
                        </md-card-content>
                        <md-card-actions>
                            <md-button type="submit" class="md-raised md-primary" :disabled="sending">Login</md-button>
                        </md-card-actions>
                    </md-card>
                </form>
            </b-col>
        </b-row>
    </div>
</template>

<script>
  import { validationMixin } from 'vuelidate'
  import { handleLogin } from "../../utils/auth";

  import {
    required,
    email,
  } from 'vuelidate/lib/validators'

  export default {
    name: 'login',
    mixins: [validationMixin],
    data: () => ({
      form: {
        email: null,
        password: null
      },
      userSaved: false,
      sending: false,
      lastUser: null,
      remember: false,
    }),
    validations: {
      form: {
        email: {
          required,
          email
        },
        password: {
          required
        }
      }
    },
    methods: {
      getValidationClass (fieldName) {
        const field = this.$v.form[fieldName]

        if (field) {
          return {
            'md-invalid': field.$invalid && field.$dirty
          }
        }
      },
      clearForm () {
        this.$v.$reset()
        this.form.email = null;
        this.form.password = null;
      },
      login () {
        this.sending = true;
        const data = {
          'email': this.form.email,
          'password': this.form.password,
        };

        this.$http.post('/auth/login', data).then((response) => {
          this.sending = false;
          const userInfo = {
            id: response.data.user.id,
            email: response.data.user.email,
            name: response.data.user.name,
            language: response.data.user.language,
            role: response.data.user.role,
            verifiedAt: response.data.user.verifiedAt
          };
          const token = {
            accessToken: response.data.access_token,
            expiresIn: response.data.expires_at,
          };
          handleLogin(userInfo, token);
          this.$router.push('/backoffice');
        }, (error) => {
          this.sending = false;
        }).catch(Vue.handleClientError);
      },
      validateUser () {
        this.$v.$touch()

        if (!this.$v.$invalid) {
          this.login()
        }
      }
    }
  }
</script>

<style lang="scss" scoped>
    .md-progress-bar {
        position: absolute;
        top: 0;
        right: 0;
        left: 0;
    }
</style>