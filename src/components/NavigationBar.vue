<template>
  <div >
    <nav class="nav flex-column">
      <a class="nav-link" :class="{ active: activeTab === 'home' }" @click="goToWelcomePage">Home</a>
      <a class="nav-link" :class="{ active: activeTab === 'profile' }" @click="goToUserDetails">My Profile</a>
      <a class="nav-link" @click="logout">Logout</a>
    </nav>
  </div>
</template>

<script>
export default {
  props: {
    activeTab: String,
  },
  methods: {
    goToWelcomePage() {
      this.$router.push('/welcome');
    },
    goToUserDetails() {
      if (this.$route.path !== '/user-details') {
        this.$router.push('/user-details');
      }
    },
    logout() {
      localStorage.removeItem('authToken');
      localStorage.removeItem('userData');
      this.$router.push('/login');
    },
  },
};
</script>

<style>
.nav-link {
  cursor: pointer;
}

.nav-link.active {
  background-color: #007bff; 
  color: #fff; 
}

</style>
