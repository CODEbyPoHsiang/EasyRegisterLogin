<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">

        <!-- Login的components  start-->
        <div class="card" v-if="first_login=== 'true'">
          <div class="card-header">登入</div>
          <div class="card-body">
            <center>
              <form>
                <div>
                  <label>信箱</label>
                  <input type="text" v-model="email" v-autofocus/>
                  <span v-if="errors.email">
                    {{ errors.email[0] }}
                  </span>
                </div>

                <div>
                  <label>密碼</label>
                  <input type="password" v-model="password" />
                  <span v-if="errors.password">
                    {{ errors.password[0] }}
                  </span>
                </div>

                <!-- <button>確認登入</button> -->
                <button type="submit" class="btn btn-primary" @click="login">
                  登入
                </button>
              </form>
            </center>
          </div>
        </div>
        <!-- Login的components  end-->

        <!-- OTP的components  start-->
        <div class="card" v-if="otp==='true'">
          <div class="card-header">OTP</div>

          <div class="card-body">
            <center>
              <div>請輸入OTP 六位數字</div>
              <br/>
              <input
                type="text"
                v-model="one_time_password"
                maxlength="6"
                v-autofocus
                oninput="this.value=this.value.replace(/[^0-9]/g,'');"
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
        <!-- OTP的components  end-->

        <div class="card" v-if="getcode==='true'">
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
                v-autofocus
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

      </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      email: "",
      password: "",
      errors: [],
      otp:"",
      first_login:"",
      getcode:"",
      qrcode:"",
            google2fa_secret: "",
      one_time_password: "",
      
    };
  },
  methods: {
    login(e) {
      e.preventDefault();
      // axios.get("/sanctum/csrf-cookie").then((response) => {
        axios
          .post("/api/login", {
            email: this.email,
            password: this.password,
          })
          .then((response) => {
            console.log(response);
            switch (response.data.success) {
                          
              case "getcode":
                this.getcode = "true";
                this.first_login = "false";
                // sessionStorage.setItem("qrcode", response.data.QR_code);
                // localStorage.setItem("email", response.data.email);
                sessionStorage.setItem("first_login", "true");
                sessionStorage.setItem("qrcode_scan", "true");
                sessionStorage.setItem("email", response.data.email);
                sessionStorage.setItem(
                  "google2fa_secret",
                  response.data.google2fa_secret
                );
                // document.cookie = `token = ${response.data.token}`;
                // this.$emit("singin", "true");
                // this.$router.push("/qrcode");
                this.qrcode=response.data.QR_code;
                break;
              case "toConfirmTwoFa":
                this.otp = "true";
                this.first_login = "false";
                sessionStorage.setItem("first_login", "true");
                sessionStorage.setItem("otp2fa", "true");
                sessionStorage.setItem("email", response.data.email);
                sessionStorage.setItem(
                  "google2fa_secret",
                  response.data.google2fa_secret
                );
                // document.cookie = `token = ${response.data.token}`;
                // this.$emit("singin", "true");
                // this.$router.push("/otp2fa");
                break;
              case false:
                alert(response.data.message);
                break;
            }
            // if (response.data.success === "getcode") {
            //   localStorage.setItem("qrcode", response.data.QR_code);
            //   // localStorage.setItem("email", response.data.email);
            //   localStorage.setItem("auth", "true");
            //   localStorage.setItem("qrcode_scan", "true");
            //   // document.cookie = `token = ${response.data.token}`;
            //   // this.$emit("singin", "true");
            //   this.$router.push("/qrcode");
            //   // return this.$router.push("/login");
            // }
            //  if (response.data.success === "toConfirmTwoFa") {
            //   // localStorage.setItem("qrcode", response.data.QR_code);
            //   // localStorage.setItem("email", response.data.email);
            //   localStorage.setItem("auth", "true");
            //   localStorage.setItem("otp2fa", "true");
            //   // document.cookie = `token = ${response.data.token}`;
            //   // this.$emit("singin", "true");
            //   this.$router.push("/otp2fa");
            //   // return this.$router.push("/login");
            // }
            // else {
            //   alert(response.data.message);
            // }
            // // console.log(response.request.status);
            // // console.log(response.data.token);
          });
      // });
    },
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
    logout() {
      axios
        .post("api/logout")
        .then((response) => {
          sessionStorage.removeItem("token");
          sessionStorage.removeItem("auth");
          sessionStorage.removeItem("email");
          // document.cookie = `token = `;
          this.$emit("singin", "false");
          this.$router.push("/login");
        })
        .catch((error) => {
          console.log(error);
        });
    },
    cancel() {
      // localStorage.removeItem("qrcode");
      // localStorage.removeItem("qrcode_scan");
      // localStorage.removeItem("first_login");
      // localStorage.removeItem("google2fa_secret");
      // localStorage.removeItem("email");
      sessionStorage.clear();
      this.getcode = "false";
      this.otp = "false";
      this.first_login = "true";
      this.$router.push("/login");
    },
  },
  mounted() {
    this.first_login = "true";
    console.log("這是login.vue");
  },
};
</script>