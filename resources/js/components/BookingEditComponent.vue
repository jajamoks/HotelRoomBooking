<template>
  <div>
    <h1>Edit Booking</h1>
    <notifications group="notify" />
    <form @submit.prevent="updateDetails">
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label>Name:</label>
            <input type="text" class="form-control" v-model="name" required />
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label>Email:</label>
            <input type="email" class="form-control" v-model="email" required />
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label>Phone Number:</label>
            <input type="tel" class="form-control" v-model="phone_number" required />
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label>Room:</label>
            <select v-model="room_id" name="room_id" id="room_id" class="form-control">
              <option
                v-for="(room, index) in rooms"
                :key="index"
                :value="room.id"
                :selected="room.id === room_id"
              >{{ room.name +' ('+room.room_type.type+')'}}</option>
            </select>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label>Start Date:</label>
            <date-picker v-model="date_start" :config="options"></date-picker>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label>End Date:</label>
            <date-picker v-model="date_end" :config="options"></date-picker>
          </div>
        </div>
      </div>
      <br />
      <div class="form-group">
        <button class="btn btn-primary">Submit</button>
      </div>
    </form>
  </div>
</template>


<script>
export default {
  data() {
    return {
      user_id: "",
      name: "",
      email: "",
      phone_number: "",
      room_id: "",
      date_start: "",
      date_end: "",
      rooms: [],
      options: {
        format: "YYYY-MM-DD",
        useCurrent: true
      }
    };
  },
  created() {
    let uri3 = `/api/booking/edit/${this.$route.params.id}`;
    this.axios.get(uri3).then(response => {
      this.date_start = response.data.date_start;
      this.date_end = response.data.date_end;
      this.room_id = response.data.room_id;

      this.name = response.data.name;
      this.email = response.data.email;
      this.phone_number = response.data.phone_number;
    });

    let uri1 = "/api/roommanager";
    this.axios.get(uri1).then(response => {
      this.rooms = response.data.data;
    });
  },
  methods: {
    updateDetails() {
      let currentObj = this;
      let uri = `/api/booking/update/${this.$route.params.id}`;

      const config = {
        headers: { "content-type": "multipart/form-data" }
      };

      let formData = new FormData();
      formData.append("user_id", this.user_id);
      formData.append("name", this.name);
      formData.append("email", this.email);
      formData.append("phone_number", this.phone_number);
      formData.append("room_id", this.room_id);
      formData.append("date_start", this.date_start);
      formData.append("date_end", this.date_end);

      this.axios.post(uri, formData, config).then(response => {
        Vue.notify({
          type: response.data.code == "00" ? "success" : "warn",
          closeOnClick: "true",
          position: "top right",
          group: "notify",
          title: response.data.code == "00" ? "Successful" : "Failed",
          text: response.data.msg
        });
        if (response.data.code == "00") {
          this.$router.push({ name: "booking" });
        }
      });
    }
  }
};
</script>
