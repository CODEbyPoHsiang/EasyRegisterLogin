<template>
  <div class="container">

 <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">註冊</div>
          <div class="card-body">
             <center>
            <form> 
      <div>
        <label>使用者帳號  </label>
        <input type="text" v-model="name" />
        <span v-if="errors.name">
          {{ errors.name[0] }}
        </span>
      </div>
      <div>
        <label>使用者信箱 </label>
        <input type="text" v-model="email" />
        <span v-if="errors.email">
          {{ errors.email[0] }}
        </span>
      </div>

      <div>
        <label>使用者密碼  </label>
        <input type="password" v-model="password" />
        <span v-if="errors.password">
          {{ errors.password[0] }}
        </span>
      </div>

     <div>
        <label>確認密碼</label>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
        <input type="password" v-model="password_confirmation" />
        <span v-if="errors.password_confirmation">
          {{ errors.password_confirmation[0] }}
        </span>
      </div>
     

      <!-- <button>確認登入</button> -->
       <center><button type="submit" class="btn btn-primary" @click="register">
        新增帳號
      </button></center>

    </form> </center>
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
      name: "",
      password: "",
      password_confirmation: "",
      errors: [],
    };
  },
  mounted() {
    console.log("這是register.vue");
  },
  methods: {
    register(e) {
      e.preventDefault();
      axios.get("/sanctum/csrf-cookie").then((response) => {
        axios
          .post("/api/register", {
            email: this.email,
            name: this.name,
            password: this.password,
            password_confirmation: this.password_confirmation,
          })
          .then((response) => {
            // console.log(response);
            if (response.request.status === 200) {
                alert("註冊成功，請登入");
              this.$router.push("/login");
              // return this.$router.push("/login");
            } else {
              alert(response.data.message);
            }

            // console.log(response.request.status);
            // console.log(response.data.token);
          })
          .catch((error) => {
            this.errors = error.response.data.errors;
          });
      });
    },
  },
};
</script>
