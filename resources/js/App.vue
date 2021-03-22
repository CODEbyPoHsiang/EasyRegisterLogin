<template>
  <div class="vue-tempalte">
    <div class="text-center" style="margin: 20px 0px 20px 0px">
      <span class="text-white"
        >Laravel SPA with Vue , Auth (Sanctum), CURD </span
      >
    </div>

    <nav class="navbar navbar-expand-lg navbar-primary  bg-primary ">
      <div class="collapse navbar-collapse">
        <!-- for logged-in user-->
        <div class="navbar-nav" v-if="isLoggedIn === 'true'" >
          <router-link to="/allproduct" class="nav-item nav-link"
            >產品清單</router-link
          >
          <!-- <router-link to="/createproduct" class="nav-item nav-link"
            >建立產品</router-link
          > -->
          <router-link to="/userabout" class="nav-item nav-link"
            >使用者資料</router-link
          >
       

          <button
            type="button"
            @click="logout"
            class="btn btn-danger pull-right"
          >
            登出
          </button>
        </div>
        <!-- for non-logged user-->
        <div class="navbar-nav" v-else>
          <router-link to="/home" class="nav-item nav-link">Home</router-link>
          <router-link to="/login" class="nav-item nav-link">Login</router-link>
          <router-link to="/register" class="nav-item nav-link"
            >Register</router-link
          >
        </div>
      </div>
    </nav>
    <br />
    <router-view     @singin="singin" />
  </div>
</template>

<script>
export default {
  data() {
    return {
      email:"",
      isLoggedIn: "false",
    };
  },
  created() {
    if (window.sessionStorage.getItem("auth") === "true") {
      this.isLoggedIn = "true";
    }
  },

  methods: {
    logout() {
      axios
        .post("api/logout",{
            email: sessionStorage.getItem("email"),
          })
        .then((response) => {
          sessionStorage.clear();
          //登出把寫在表頭的
          axios.defaults.headers.common["Authorization"] ="" ;
          this.isLoggedIn = "false";
          this.$router.push("/login");
        })
        .catch((error) => {
          console.log(error);
        });
    },
    singin(para) {
      this.isLoggedIn = para;
    },
    handleStorageChange() {
      // axios.post(`/api/remove_password/${localStorage.getItem("email")}`).then((response) => {
      // console.log(response.data);
      sessionStorage.clear();
      this.isLoggedIn = "false";
      this.$router.push("/login");
      // });
    },
  },
  ready() {
    window.addEventListener("storage", this.handleStorageChange);
  },
  beforeDestroy() {
    window.removeEventListener("storage", this.handleStorageChange);
  },
  mounted() {
    window.addEventListener("storage", this.handleStorageChange);
  },
 
};
</script>

