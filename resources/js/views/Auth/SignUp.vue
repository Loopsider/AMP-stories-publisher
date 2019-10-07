<template>
    <div class="container">
        <b-row class="justify-content-center">
            <b-col md="6">
                <form novalidate class="md-layout" @submit.prevent="validateUser">
                    <md-card class="md-layout-item">
                        <md-card-header>
                            <div class="md-title">User Register</div>
                        </md-card-header>
                        <md-card-content>
                            <md-progress-bar md-mode="indeterminate" v-if="sending"/>
                            <md-field :class="getValidationClass('name')">
                                <label for="name">Name</label>
                                <md-input name="name" id="name" autocomplete="given-name" v-model="form.name"
                                          :disabled="sending"/>
                                <span class="md-error" v-if="!$v.form.name.required">The name is required</span>
                            </md-field>
                            <md-field :class="getValidationClass('email')">
                                <label for="email">Email</label>
                                <md-input type="email" name="email" id="email" autocomplete="email" v-model="form.email"
                                          :disabled="sending"/>
                                <span class="md-error" v-if="!$v.form.email.required">The email is required</span>
                                <span class="md-error" v-else-if="!$v.form.email.email">Invalid email</span>
                            </md-field>
                            <md-field :class="getValidationClass('password')">
                                <label for="password">Password</label>
                                <md-input type="password" name="password" id="password" autocomplete="password"
                                          v-model="form.password"
                                          :disabled="sending"/>
                                <span class="md-error" v-if="!$v.form.password.required">The password is required</span>
                            </md-field>
                            <md-field :class="getValidationClass('c_password')">
                                <label for="c_password">Confirm Password</label>
                                <md-input type="password" name="c_password" id="c_password" autocomplete="c_password"
                                          v-model="form.c_password" :disabled="sending"/>
                                <span class="md-error" v-if="!$v.form.c_password.required">The confirm password is required</span>
                            </md-field>
                            <br>
                            <b-button variant="link" to="/auth/login" class="px-0">Login now</b-button>
                        </md-card-content>
                        <md-card-actions>
                            <md-button type="submit" class="md-raised md-primary" :disabled="sending">Register
                            </md-button>
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
    name: "sign-up",
    mixins: [validationMixin],
    data: () => ({
      form: {
        name: null,
        email: null,
        password: null,
        c_password: null,
      },
      userSaved: false,
      sending: false,
      lastUser: null,
      remember: false,
    }),
    validations: {
      form: {
        name: {
          required,
        },
        email: {
          required,
          email,
        },
        password: {
          required,
        },
        c_password: {
          required,
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
        this.$v.$reset();
        this.form.name = null;
        this.form.email = null;
        this.form.password = null;
        this.form.c_password = null;
      },
      register () {
        this.sending = true;
        const data = {
          'name': this.form.name,
          'email': this.form.email,
          'password': this.form.password,
          'c_password': this.form.c_password
        };

        this.$http.post('/auth/register', data).then((response) => {
          this.sending = false;
          const userInfo = {
            id: response.data.user.id,
            email: response.data.user.email,
            language: response.data.user.language,
            name: response.data.user.name,
            role: response.data.user.role,
            verifiedAt: response.data.user.verifiedAt
          };
          const token = {
            accessToken: response.data.access_token,
            expiresIn: response.data.expires_at,
          };
          handleLogin(userInfo, token);
          this.$router.push('/auth/verify-email');
        }, (error) => {
          this.sending = false;
        }).catch(Vue.handleClientError);

      },
      validateUser () {
        this.$v.$touch();

        if (!this.$v.$invalid) {
          this.register();
        }
      }
    }
  }
</script>

<style scoped>

</style>