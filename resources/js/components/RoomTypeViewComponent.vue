<template>
  <div>
    <h1>Room Type List</h1>
    <div class="row">
      <div class="col-md-10"></div>
      <div class="col-md-2">
        <router-link :to="{ name: 'roomtype_create' }" class="btn btn-primary">Add Room Type</router-link>
      </div>
    </div>
    <br />

    <table class="table table-hover">
      <thead>
        <tr>
          <th>ID</th>
          <th>Type</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="room in roomtypes" :key="room.id">
          <td>{{ room.id }}</td>
          <td>{{ room.type }}</td>
          <td>
            <router-link
              :to="{name: 'roomtype_edit', params: { id: room.id }}"
              class="btn btn-primary"
            >Edit</router-link>
          </td>
          <td>
            <button class="btn btn-danger" @click.prevent="deletePost(room.id)">Delete</button>
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
      roomtypes: []
    };
  },
  created() {
    let uri = "api/roomtype";
    this.axios.get(uri).then(response => {
      this.roomtypes = response.data.data;
    });
  },
  methods: {
    deletePost(id) {
      let uri = `api/roomtype/delete/${id}`;
      this.axios.delete(uri).then(response => {
        Vue.notify({
          type: response.data.code == "00" ? "success" : "warn",
          closeOnClick: "true",
          position: "top right",
          group: "notify",
          title: response.data.code == "00" ? "Successful" : "Failed",
          text: response.data.msg
        });
        this.roomtypes.splice(this.roomtypes.indexOf(id), 1);
      });
    }
  }
};
</script>
