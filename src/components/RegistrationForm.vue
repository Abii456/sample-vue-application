<template>
  <div class="container p-5 border" style="margin-top: 5%; border-radius: 10px; width: 50%;">
    <h3 class="text-center mt-2 mb-5">Registration</h3>
    <div class="col-md-12">

      <div class="mb-3 text-danger font-weight-bold">
        * Required fields.
      </div>

      <form v-on:submit.prevent="create_user">

        <div class="mb-3">
          <label for="first_name" class="form-label">First Name *</label>
          <input type="text" name="first_name" class="form-control" id="first_name"
            placeholder="Enter First Name" v-model="form.first_name" required>
        </div>

        <div class="mb-3">
          <label for="last_name" class="form-label">Last Name *</label>
          <input type="text" name="last_name" class="form-control" id="last_name" placeholder="Enter Last Name"
            v-model="form.last_name" required>
        </div>

        <div class="mb-3">
          <label for="address" class="form-label">Address *</label>
          <input type="text" name="address" class="form-control" id="address" placeholder="Enter Address"
            v-model="form.address" required>
        </div>

        <div class="mb-3">
          <label for="email" class="form-label">Email Address *</label>
          <input type="email" name="email" class="form-control" id="email" aria-describedby="emailHelp"
            placeholder="Enter Email" v-model="form.email" required>
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">Password *</label>
          <input type="password" name="password" class="form-control" id="password" aria-describedby="passHelp"
            placeholder="Enter Password" v-model="form.password" required>
        </div>

        <button type="submit" class="btn btn-primary mt-5">Submit</button><br>
        <router-link to="/login" class="ml-3">Already have an account? Sign In here</router-link>
      </form>

    </div>
  </div>
</template>

<script>
import "bootstrap/dist/css/bootstrap.min.css";
import axios from 'axios';
import Swal from 'sweetalert2';
import { USER_BASE_URL } from '@/apiConfig';

export default {
  data() {
    return {
      form: {
        first_name: '',
        last_name: '',
        email: '',
        password: '',
        address: ''
      },
      errors: {}
    };
  },
  methods: {
    create_user() {
      axios
        .post(`${USER_BASE_URL}register`, this.form)
        .then((resp) => {
          //console.log(resp.data);
          if (resp.data.status === 'success') {
            Swal.fire({
              title: 'Success',
              text: 'User is successfully created.',
              icon: 'success',
            }).then(() => {
              this.$nextTick(() => {
                localStorage.setItem('authToken', resp.data.data.token);
                localStorage.setItem('userData', JSON.stringify(resp.data.data));
                this.$router.push('/welcome');
              });
            });
          } else {
            this.errors = resp.data.error;
            this.showValidationErrors();
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
    showValidationErrors() {
      let errorMessages = [];
      for (const field in this.errors) {
        errorMessages.push(this.errors[field][0]);
      }
      const errorMessage = errorMessages.map((error) => `• ${error}`).join('<br>');
      Swal.fire({
        title: errorMessages.length > 1 ? 'Please fix the following error/s:' : 'Please fix the following error:',
        html: `<div class="error-messages">${errorMessage}</div>`,
        icon: 'warning'
      });
    }
  }
};
</script>

<style>.error-messages {
  text-align: left;
}</style>