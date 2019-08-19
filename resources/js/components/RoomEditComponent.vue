<template>
  <div>
    <h1>Edit Room Details</h1>
    <notifications group="notify" />
    <form @submit.prevent="updateDetails" enctype="multipart/form-data">
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label>Name:</label>
            <input type="text" class="form-control" v-model="name" />
          </div>
        </div>
      </div>
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
      room_type: "",
      image: "",
      room_types_collection: []
    };
  },
  created() {
    let uri3 = `/api/roommanager/edit/${this.$route.params.id}`;
    this.axios.get(uri3).then(response => {
      this.name = response.data.name;
      this.image = "/images/rooms/" + response.data.image;
      this.room_type = response.data.room_type_id;
    });

    let uri1 = "/api/roomtype";
    this.axios.get(uri1).then(response => {
      this.room_types_collection = response.data.data;
    });
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
    updateDetails() {
      let currentObj = this;
      let uri = `/api/roommanager/update/${this.$route.params.id}`;

      const config = {
        headers: { "content-type": "multipart/form-data" }
      };

      let formData = new FormData();

      formData.append("image", this.image);
      formData.append("name", this.name);
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
          this.$router.push({ name: "room" });
        }
      });
    }
  }
};
</script>
