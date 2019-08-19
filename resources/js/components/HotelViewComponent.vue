<template>
  <div>
    <h1>Hotel</h1>
    <div class="row">
      <div class="col-md-10"></div>
      <div class="col-md-2">
        <router-link :to="{ name: 'hotel_create' }" class="btn btn-primary">Add Hotel</router-link>
      </div>
    </div>
    <br />

    <table class="table table-hover">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Email</th>
          <th>Contact</th>
          <th>City</th>
          <th>Country</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="hotel in hotels" :key="hotel.id">
          <td>{{ hotel.id }}</td>
          <td>{{ hotel.name }}</td>
          <td>{{ hotel.email }}</td>
          <td>{{ hotel.phone_number }}</td>
          <td>{{ hotel.city }}</td>
          <td>{{ hotel.country }}</td>
          <td>
            <router-link
              :to="{name: 'hotel_edit', params: { id: hotel.id }}"
              class="btn btn-primary"
            >Edit</router-link>
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
      hotels: []
    };
  },
  created() {
    this.axios
      .get("api/hotel")
      .then(response => {
        this.hotels = response.data.data;
      })
      .catch(response => {
        // List errors on response...
      });
  },
  methods: {
    deletePost(id) {
      let uri = `api/hotel/delete/${id}`;
      this.axios.delete(uri).then(response => {
        this.hotels.splice(this.hotels.indexOf(id), 1);
      });
    }
  }
};
</script>
