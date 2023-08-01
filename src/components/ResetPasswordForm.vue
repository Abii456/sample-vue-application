<template>
    <div class="container p-5 border" style="margin-top: 10%; border-radius: 10px; width: 50%;">
        <h3 class="text-center mt-2 mb-5">Reset Password</h3>
        <div class="col-md-12">
            <form v-on:submit.prevent="resetPassword">
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address *</label>
                    <input type="email" name="email" class="form-control" id="email"
                        aria-describedby="emailHelp" placeholder="Enter Email" v-model="form.email" required />
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">New Password *</label>
                    <input type="password" name="password" class="form-control" id="password"
                        placeholder="Enter New Password" v-model="form.password" required />
                </div>

                <div class="mb-3">
                    <label for="confirmPassword" class="form-label">Confirm New Password *</label>
                    <input type="password" name="confirmPassword" class="form-control" id="confirmPassword"
                        placeholder="Confirm New Password" v-model="form.confirmPassword" required
                        @input="checkPasswordMatch" />
                    <div id="confirmPasswordError" class="text-danger">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary mt-5">Submit</button><br>

                <router-link to="/register" class="ml-3">Don't have an account? Register here</router-link><br>
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
                confirmPassword: '',
            },
        };
    },
    methods: {
        resetPassword() {
            axios
                .put(`${USER_BASE_URL}update-user-details`, this.form)
                .then((resp) => {
                    //console.log(resp.data);
                    if (resp.data.status === 'success') {
                        localStorage.setItem('authToken', resp.data.data.token);
                        localStorage.setItem('userData', JSON.stringify(resp.data.data));
                        Swal.fire({
                            title: 'Success',
                            text: 'Password has been reset.',
                            icon: 'success',
                        }).then(() => {
                            this.$nextTick(() => {
                                this.$router.push('/welcome');
                            });
                        });
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: resp.data.error,
                            icon: 'error',
                        });
                    }
                })
                .catch((error) => {
                    console.log(error.response);
                    if (error.response && error.response.data && error.response.data.error) {
                        Swal.fire({
                            title: 'Error',
                            text: error.response.data.error,
                            icon: 'error',
                        });
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: 'An error occurred. Please try again later.',
                            icon: 'error',
                        });
                    }
                });
        },
        checkPasswordMatch() {
            const confirmPasswordInput = document.getElementById('exampleInputConfirmPassword1');
            if (this.form.password !== this.form.confirmPassword) {
                confirmPasswordInput.setCustomValidity('Passwords do not match.');
            } else {
                confirmPasswordInput.setCustomValidity('');
            }
        },
    },
};

</script>
  
<style></style>
  