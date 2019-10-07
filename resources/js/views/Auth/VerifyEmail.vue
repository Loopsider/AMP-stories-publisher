<template>
    <div>
        <b-row class="justify-content-center">
            <b-col md="8">
                <div class="clearfix">
                    <div class="float-left display-3 mr-4">
                        <img src="/assets/mailbox.png" width="140">
                    </div>
                    <h3 class="pt-lg-3">Verify your email address to login to POSS!</h3>
                    <p class="text-dark mt-3">
                        We've just {{ resent ? 're' : '' }}sent an email to your address <strong>{{ email }}</strong>
                        <br>
                        Please check your email and click on the link provided to verify your address. <br>
                        If you haven't received email, request <a href="javascript:;"
                                                                  @click="() => requestResendEmail()">resend</a> here.
                    </p>
                </div>
            </b-col>
        </b-row>
    </div>
</template>

<script>
  export default {
    name: "verify-email",

    data () {
      return {
        email: this.$store.state.userInfo ? this.$store.state.userInfo.email : '',
        sending: false,
        resent: false,
      }
    },
    mounted () {
      if (this.$store.state.userInfo.email === "") {
        this.$router.push('/auth/login');
      }
      if (this.$route.query.queryURL) {
        const { queryURL } = this.$route.query;
        this.verify(queryURL);
      }

    },
    methods: {
      verify (queryURL) {
        this.sending = true;
        this.$http.post(queryURL).then((response) => {
          this.sending = false;
          const userInfo = {
            email: response.data.user.email,
            name: response.data.user.name,
            language: response.data.user.language,
            role: response.data.user.role,
            verifiedAt: response.data.user.verifiedAt
          };
          this.$store.dispatch('updateUserInfo', userInfo);
          this.$router.push('/backoffice');
        }, (error) => {
          this.sending = false;
        }).catch(Vue.handleClientError);
      },
      requestResendEmail () {
        this.sending = true;
        this.$http.post('/auth/email/resend').then((response) => {
          this.sending = false;
          this.resent = true;
        }, (error) => {
          this.sending = false;
        }).catch(Vue.handleClientError);
      }
    }
  }
</script>

<style scoped>

</style>