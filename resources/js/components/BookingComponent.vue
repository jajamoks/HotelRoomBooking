

<template>
  <div>
    <h1>Booking</h1>
    <div class="row">
      <div class="col-md-10"></div>
      <div class="col-md-2"></div>
    </div>
    <br />

    <table class="table table-hover">
      <thead>
        <tr>
          <th>ID</th>
          <th>Room Name</th>
          <th>Customer Name</th>
          <th>Check In</th>
          <th>Check Out</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="book in bookings" :key="book.id">
          <td>{{ book.id }}</td>
          <td>{{ book.room.name }}</td>
          <td>{{ book.customer.last_name }} {{ book.customer.fist_name }}</td>
          <td>{{ book.date_start }}</td>
          <td>{{ book.date_end }}</td>

          <td>
            <router-link
              :to="{name: 'booking_edit', params: { id: book.id }}"
              class="btn btn-primary"
            >Edit</router-link>
          </td>
          <td>
            <button class="btn btn-danger" @click.prevent="deletePost(book.id)">Delete</button>
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
      bookings: []
    };
  },
  created() {
    let uri = "api/booking";
    this.axios.get(uri).then(response => {
      this.bookings = response.data.data;
    });
  },
  methods: {
    deletePost(id) {
      let uri = `api/booking/delete/${id}`;
      this.axios.delete(uri).then(response => {
        Vue.notify({
          type: response.data.code == "00" ? "success" : "warn",
          closeOnClick: "true",
          position: "top right",
          group: "notify",
          title: response.data.code == "00" ? "Successful" : "Failed",
          text: response.data.msg
        });
        this.bookings.splice(this.bookings.indexOf(id), 1);
      });
    }
  }
};
</script>
