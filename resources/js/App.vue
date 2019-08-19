<template>
  <div class="container">
    <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
      <ul class="navbar-nav">
        <li class="nav-item">
          <router-link to="/hotel" class="nav-link">Hotel</router-link>
        </li>
        <li class="nav-item">
          <router-link to="/room" class="nav-link">Room Manager</router-link>
        </li>
        <li class="nav-item">
          <router-link to="/booking" class="nav-link">Booking Manager</router-link>
        </li>
        <li class="nav-item">
          <router-link to="/pricelist" class="nav-link">Price List Manager</router-link>
        </li>
        <li class="nav-item">
          <router-link to="/roomtype" class="nav-link">Rooms Types</router-link>
        </li>
      </ul>
    </nav>
    <br />
    <transition name="fade">
      <router-view></router-view>
    </transition>
  </div>
</template>

<style>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s;
}
.fade-enter,
.fade-leave-active {
  opacity: 0;
}
</style>

<script>
export default {
  data() {
    return {
      username: "mdari61@gmail.com",
      password: "hotelbooking"
    };
  },

  created() {
    let data = {
      username: this.username,
      password: this.password
    };

    this.axios
      .post("/api/login", data)
      .then(response => {
        auth.login(response.data.token, response.data.user);
        this.$router.push({ name: "hotel" });
      })
      .catch(response => {
        alert("Sorry kindly try again!");
      });
  }
};
</script>
