<template>
  <div>
    <h1>Edit Room Type</h1>
    <notifications group="notify" />
    <form @submit.prevent="updateDetails">
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label>Type:</label>
            <input type="text" class="form-control" v-model="type" required />
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
      type: ""
    };
  },
  created() {
    let uri = `/api/roomtype/edit/${this.$route.params.id}`;
    this.axios.get(uri).then(response => {
      this.type = response.data.type;
    });
  },
  methods: {
    updateDetails() {
      let currentObj = this;
      let uri = `/api/roomtype/update/${this.$route.params.id}`;

      const config = {
        headers: { "content-type": "multipart/form-data" }
      };

      let formData = new FormData();
      formData.append("type", this.type);

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
          this.$router.push({ name: "roomtype" });
        }
      });
    }
  }
};
</script>
