<template>
  <div class="container p-5 border" style="margin-top: 10%; border-radius: 10px; width: 50%;">
    <h3 class="text-center mt-2 mb-5">Login</h3>
    <div class="col-md-12">

      <div class="mb-3 text-danger font-weight-bold">
        * Required fields.
      </div>

      <form v-on:submit.prevent="login_user">
        <div class="mb-3">
          <label for="email" class="form-label">Email Address *</label>
          <input type="text" name="email" class="form-control" id="email" aria-describedby="emailHelp"
            placeholder="Enter Email" v-model="form.email" required>
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">Password *</label>
          <input type="password" name="password" class="form-control" id="password" aria-describedby="passHelp"
            placeholder="Enter Password" v-model="form.password" required>
        </div>

        <button type="submit" class="btn btn-primary mt-5">Submit</button><br>

        <router-link to="/register" class="ml-3">Don't have an account? Register here</router-link><br>
        <router-link to="/reset-password" class="ml-3">Forgot Password?</router-link>
      </form>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import Swal from 'sweetalert2';
import { USER_BASE_URL } from '@/apiConfig'; 

export default {
  data() {
    return {
      form: {
        email: '',
        password: '',
      },
    };
  },
  methods: {
    login_user() {
      axios.post(`${USER_BASE_URL}login`, this.form)
        .then((resp) => {
          //console.log(resp.data);
          if (resp.data.status === 'success') {
            localStorage.setItem('authToken', resp.data.data.token);
            const userData = JSON.stringify(resp.data.data.user);
            localStorage.setItem('userData', userData);
            this.$router.push('/welcome');
          } else {
            Swal.fire({
              title: 'Oops!',
              text: resp.data.data,
              icon: 'warning',
            });
          }
        })
        .catch((e) => {
          console.log(e);
          Swal.fire({
            title: 'Oops!',
            text: e,
            icon: 'error',
          });
        });
    },

  },
};
</script>