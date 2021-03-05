<template>
 <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">使用者資料</div>
          <div class="card-body">
            <div>
    <p>使用者名稱：{{ user.name }}</p>
    <p>使用者信箱：{{ user.email }}</p>
    <!-- <button type="button" class="btn btn-danger" @click="logout">登出</button> -->
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
      user: "",
    };
  },
  mounted() {
    axios.defaults.headers.common["Authorization"] =
      "Bearer " + sessionStorage.getItem("token");
    const email = sessionStorage.getItem("email");
    axios.get(`/api/user/${email}`).then((response) => {
      // console.log(response.data);
      this.user = response.data;
    });
  },
  methods: {
    logout() {
      axios
        .post("api/logout")
        .then((response) => {
          // localStorage.removeItem("auth");
          sessionStorage.removeItem("token");
          sessionStorage.removeItem("auth");
          sessionStorage.removeItem("email");
          this.$emit("singin", "false");
          this.$router.push("/login");
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
};
</script>
