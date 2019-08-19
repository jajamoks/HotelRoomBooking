<template>
  <div>
    <h1>Price List</h1>
    <div class="row">
      <div class="col-md-10"></div>
      <div class="col-md-2">
        <router-link :to="{ name: 'pricelist_create' }" class="btn btn-primary">Add Price</router-link>
      </div>
    </div>
    <br />

    <table class="table table-hover">
      <thead>
        <tr>
          <th>ID</th>
          <th>Room Type</th>
          <th>Price</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="price in pricelist" :key="price.id">
          <td>{{ price.id }}</td>
          <td>{{ price.room_type_id }}</td>
          <td>USD {{ price.price }}</td>
          <td>
            <router-link
              :to="{name: 'pricelist_edit', params: { id: price.id }}"
              class="btn btn-primary"
            >Edit</router-link>
          </td>
          <td>
            <button class="btn btn-danger" @click.prevent="deletePost(price.id)">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  data() {
    return {
      pricelist: []
    };
  },
  created() {
    let uri = "api/pricelist";
    this.axios.get(uri).then(response => {
      this.pricelist = response.data.data;
    });
  },
  methods: {
    deletePost(id) {
      let uri = `api/pricelist/delete/${id}`;
      this.axios.delete(uri).then(response => {
        Vue.notify({
          type: response.data.code == "00" ? "success" : "warn",
          closeOnClick: "true",
          position: "top right",
          group: "notify",
          title: response.data.code == "00" ? "Successful" : "Failed",
          text: response.data.msg
        });
        this.pricelist.splice(this.pricelist.indexOf(id), 1);
      });
    }
  }
};
</script>
