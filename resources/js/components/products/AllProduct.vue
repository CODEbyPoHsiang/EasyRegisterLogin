<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <center>
          <input type="text" v-model="keywords" placeholder="請輸入品名" />

          <button type="submit" class="btn btn-primary" @click="search">
            搜尋
          </button>
          <button type="submit" class="btn btn-primary" onClick="history.go()">
            重新整理
          </button>
          <br/>
          <br/>
        </center>

        <div class="card">
          <div class="card-header">產品清單
            <router-link
                        :to="{
                          path: '/createproduct',
                         
                        }"
                        class="btn btn-success pull-right"
                        >新增</router-link
                      >
          </div>
          <div class="card-body">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>品名</th>
                  <th>價格</th>
                  <th>建立時間</th>
                  <th>更新時間</th>
                  <th colspan="2">操作</th>
                </tr>
              </thead>
              <tbody>
                <!-- <span v-if="errorskeywords"> -->
                <!-- </span> -->

                <tr v-for="product in products" :key="product.id">
                  <td>{{ product.id }}</td>
                  <td>{{ product.name }}</td>
                  <td>{{ product.detail }}</td>
                  <td>{{ product.created_at }}</td>
                  <td>{{ product.updated_at }}</td>
                  <td colspan="2">
                    <div class="btn-group" role="group">
                      <router-link
                        :to="{
                          path: '/editproduct',
                          query: { id: product.id },
                        }"
                        class="btn btn-primary"
                        >編輯</router-link
                      >&nbsp;&nbsp;
                      <button
                        class="btn btn-danger"
                        @click="deleteProduct(product.id)"
                      >
                        刪除
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
            <!-- <span v-if="errorskeywords">  -->
            <center>
              <!-- 查無資料顯示 -->
              <tr>
                {{
                  errorskeywords
                }}
              </tr>
            </center>

            <!-- </span> -->
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
      keywords: null,
      products: [],
      errorskeywords: "",
    };
  },
  created() {
    // axios.get("/sanctum/csrf-cookie").then((response) => {
      // console.log(response.config.headers.Authorization),
        axios.get("api/products").then((response) => {
          console.log(response.data);
          this.products = response.data;
        });
    // });
  },
  methods: {
    deleteProduct(id) {
      let yes = confirm(`你確定刪除編號【${id}】的產品嗎？`);
      if (yes) {
        axios.get("/sanctum/csrf-cookie").then((response) => {
          console.log(response.config.headers.Authorization),
            axios.delete(`api/products/${id}`).then((response) => {
              let i = this.products.map((data) => data.id).indexOf(id);
              this.products.splice(i, 1);
            });
        });
      }
    },
    search() {
      axios.get("/sanctum/csrf-cookie").then((response) => {
        axios
          .post("/api/search", {
            keywords: this.keywords,
          })

          .then((response) => {
            console.log(response.data);
            if (response.request.status === 200) {
              this.products = response.data;
              this.errorskeywords = "";
            } else {
              (this.products = []),
                (this.errorskeywords = response.data.message);
            }

            // console.log(response.request.status);
            // console.log(response.data.token);
          });
      });
    },
  },
};
</script>
