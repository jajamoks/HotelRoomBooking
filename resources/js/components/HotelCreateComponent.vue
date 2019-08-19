<template>
  <div>
    <h1>Add Hotel</h1>
    <notifications group="notify" />
    <form @submit.prevent="addRecord" enctype="multipart/form-data">
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
            <label>Zip Code:</label>
            <input type="text" class="form-control" v-model="zip_code" required />
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label>Country:</label>
            <input type="text" class="form-control" v-model="country" required />
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label>State:</label>
            <input type="text" class="form-control" v-model="state" required />
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label>City:</label>
            <input type="text" class="form-control" v-model="city" required />
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label>Address:</label>
            <input type="text" class="form-control" v-model="address" required />
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div v-if="image">
            <img :src="image" />
          </div>
          <div class="form-group">
            <label>Image:</label>
            <input type="file" class="form-control" v-on:change="onImageChange" />
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
      name: "",
      email: "",
      phone_number: "",
      zip_code: "",
      state: "",
      country: "",
      city: "",
      address: "",
      image: ""
    };
  },
  methods: {
    onImageChange(e) {
      this.image = e.target.files[0];

      var files = e.target.files || e.dataTransfer.files;
      if (!files.length) return;
      this.createImage(files[0]);
    },
    createImage(file) {
      var image = new Image();
      var reader = new FileReader();
      var vm = this;

      reader.onload = e => {
        vm.image = e.target.result;
      };
      reader.readAsDataURL(file);
    },
    addRecord() {
      let currentObj = this;
      let uri = "/api/hotel/create";

      const config = {
        headers: { "content-type": "multipart/form-data" }
      };

      let formData = new FormData();

      formData.append("image", this.image);
      formData.append("name", this.name);
      formData.append("email", this.email);
      formData.append("phone_number", this.phone_number);
      formData.append("zip_code", this.zip_code);
      formData.append("state", this.state);
      formData.append("city", this.city);
      formData.append("address", this.address);
      formData.append("country", this.country);

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
          this.$router.push({ name: "hotel" });
        }
      });
    }
  }
};
</script>
