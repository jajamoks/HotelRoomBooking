<template>
  <div>
    <h1>Editing Price Details</h1>
    <notifications group="notify" />
    <form @submit.prevent="updateDetails" enctype="multipart/form-data">
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label>Room Type:</label>
            <select v-model="room_type" name="room_type" id="room_type" class="form-control">
              <option
                v-for="(rtype, index) in room_types_collection"
                :key="index"
                :value="rtype.id"
                :selected="rtype.id === room_type"
              >{{ rtype.type }}</option>
            </select>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label>Price:</label>
            <input type="number" class="form-control" v-model="price" required min="1" />
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
      price: "",
      room_type: "",
      room_types_collection: []
    };
  },
  created() {
    let uri3 = `/api/pricelist/edit/${this.$route.params.id}`;
    this.axios.get(uri3).then(response => {
      this.price = response.data.price;
      this.room_type = response.data.room_type_id;
    });

    let uri1 = "/api/roomtype";
    this.axios.get(uri1).then(response => {
      this.room_types_collection = response.data.data;
    });
  },
  methods: {
    updateDetails() {
      let currentObj = this;
      let uri = `/api/pricelist/update/${this.$route.params.id}`;

      const config = {
        headers: { "content-type": "multipart/form-data" }
      };

      let formData = new FormData();
      formData.append("price", this.price);
      formData.append("room_type", this.room_type);

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
          this.$router.push({ name: "pricelist" });
        }
      });
    }
  }
};
</script>
