<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">OTP</div>

          <div class="card-body">
            <center>
              <div>請輸入OTP 六位數字</div>
              <br/>
              <input
                type="text"
                v-model.lazy="one_time_password"
                maxlength="6"
                oninput="this.value=this.value.replace(/[^0-9]/g,'');"
                ref="focus"
              />
            </center>
          </div>

          <div class="card-footer">
            <button
              type="button"
              @click="cancel"
              class="btn btn-danger "
            >
              取消
            </button>
            <button
              type="button"
              @click="otpchk"
              class="btn btn-primary pull-right"
            >
              確認
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
</template>

<script>
export default {
   data() {
    return {
      email:"",
      google2fa_secret: "",
      one_time_password: "",
      errors: [],
    };
   },
   watch:{
   one_time_password() {

     axios
          .post("/api/google2fa_login", {
           google2fa_secret: sessionStorage.getItem('google2fa_secret'),
           email: sessionStorage.getItem('email'),
           one_time_password: this.one_time_password,
          }).then((response) => {
            console.log(response);
            switch (response.data.success) {
              case true:
                sessionStorage.setItem("token", response.data.login_token);
                sessionStorage.setItem("auth", "true");
                //emit 改變父層navbar元件
                this.$emit("singin", "true");
                this.$router.push("/userabout");
                break;
              case false:
                alert(response.data.message)
                break;
              case "optempty":
                alert(response.data.message)
                break;
            }
      });
   }
   },
  methods: {
     otpchk(e) {
      e.preventDefault();
      axios
          .post("/api/google2fa_login", {
           google2fa_secret: sessionStorage.getItem('google2fa_secret'),
           email: sessionStorage.getItem('email'),
           one_time_password: this.one_time_password,
          }).then((response) => {
            console.log(response);
            switch (response.data.success) {
              case true:
                sessionStorage.setItem("token", response.data.login_token);
                sessionStorage.setItem("auth", "true");

                //emit 改變父層navbar元件
                this.$emit("singin", "true");

                this.$router.push("/userabout");
                break;
              case false:
                alert(response.data.message)
                break;
              case "optempty":
                alert(response.data.message)
                break;
            }
      });
     },
    cancel() {
      // localStorage.removeItem("otp2fa");
      // localStorage.removeItem("first_login");
      // localStorage.removeItem("google2fa_secret");
      // localStorage.removeItem("email");
      sessionStorage.clear();

      this.$router.push({"/login"});
      this.email = "";
      this.password = "";
    },
  },
  mounted() {
        // 載入即游標在input上
    this.$refs.focus.focus();
    console.log("這是otp.vue");
  },
};
</script>
