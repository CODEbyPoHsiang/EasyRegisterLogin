<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">QRcode</div>
          <br />
          <center>
            <!-- qr_code取出時是html標籤要把他利用v-html綁定 -->
                        <div>請掃描QRcode</div>
                      <br />

            <div v-html="qrcode"></div>
                      <br />

            <div>請輸入OTP 六位數字</div>
            <div class="card-body">
              <input
                type="text"
                maxlength="6"
                v-model="one_time_password"
                oninput="this.value=this.value.replace(/[^0-9]/g,'');"
              />
            </div>
          </center>
          <div class="card-footer">
            <button 
              type="submit" 
              class="btn btn-primary pull-right"               
              @click="otpchk"
              >
                確認
              </button>
            <button
              type="button"
              @click="cancel"
              class="btn btn-danger "
            >
              取消
            </button>
          </div>
        </div>
        <!-- <button>確認登入</button> -->
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      qrcode: "",
      email:"",
      google2fa_secret: "",
      one_time_password: "",
      errors: [],
    };
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
            }
      });
     },
    cancel() {
      // localStorage.removeItem("qrcode");
      // localStorage.removeItem("qrcode_scan");
      // localStorage.removeItem("first_login");
      // localStorage.removeItem("google2fa_secret");
      // localStorage.removeItem("email");
      sessionStorage.clear();
      this.$router.push("/login");
    },
  },
  mounted() {
    this.qrcode = sessionStorage.getItem("qrcode");
    // localStorage.removeItem("qrcode");
  },
};
</script>
