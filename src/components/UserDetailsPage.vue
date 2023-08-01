<template>
    <div class="container p-5">
        <div class="row">
            <div class="col-md-3">
                <NavigationBar :activeTab="activeTab" />
            </div>

            <div class="col-md-8">
                <h3 class="mb-4">My Profile</h3>
                <form @submit.prevent="saveProfile" id="profileForm">
                    <div class="mb-3 d-flex">
                        <div class="me-3">
                            <label for="firstName" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="firstName" :value="user.first_name"
                                :disabled="!isEditing" @input="onFirstNameChange" required />
                        </div>
                        <div>
                            <label for="lastName" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="lastName" :value="user.last_name"
                                :disabled="!isEditing" @input="onLastNameChange" required />
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" @input="onEmailChange" :value="user.email"
                            :disabled="!isEditing" required />
                    </div>
                    <hr>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input :type="isEditing ? 'text' : 'password'" class="form-control" id="password"
                            :value="isEditing ? user.password : '********'" :disabled="!isEditing" @input="onPasswordChange"
                            required />
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" class="form-control" id="address" :value="user.address" :disabled="!isEditing"
                            @input="onAddressChange" required />
                    </div>
                    <div class="mb-3" v-if="isEditing">
                        <button class="btn btn-success me-2">Save Profile</button>
                        <button class="btn btn-danger" @click="cancelEdit">Cancel</button>
                    </div>
                    <div class="mb-3" v-else>
                        <button class="btn btn-primary" @click="editProfile">Update My Profile</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
  
<script>
import NavigationBar from '@/components/NavigationBar.vue';
import axios from 'axios';
import Swal from 'sweetalert2';
import { USER_BASE_URL } from '@/apiConfig';

export default {
    components: {
        NavigationBar,
    },
    data() {
        return {
            user: {
                first_name: '',
                last_name: '',
                email: '',
                address: '',
                password: '',
            },
            activeTab: 'profile',
            isPasswordVisible: false,
            isEditing: false,
            isSavingProfile: false,
        };
    },
    mounted() {
        const userData = JSON.parse(localStorage.getItem('userData'));
        if (userData) {
            this.user.first_name = userData.first_name;
            this.user.last_name = userData.last_name;
            this.user.email = userData.email;
            this.user.address = userData.address;
            this.initialUserData = { ...this.user };
        }
    },
    methods: {
        editProfile() {
            this.isEditing = true;
            this.user.password = '';
        },
        saveProfile() {
            if (this.isSavingProfile) {
                return;
            }

            if (!this.isEditing) {
                return;
            }
            const isValidForm = document.getElementById('profileForm').checkValidity();
            if (!isValidForm) {
                return;
            }
            const payload = {
                id: JSON.parse(localStorage.getItem('userData')).id,
                first_name: this.user.first_name,
                last_name: this.user.last_name,
                email: this.user.email,
                address: this.user.address,
            };
            axios.put(`${USER_BASE_URL}update-user-details`, payload)
                .then((response) => {
                    localStorage.setItem('userData', JSON.stringify(response.data.data));
                    localStorage.setItem('authToken', response.data.data.token);
                    this.initialUserData = { ...response.data.data };
                    Swal.fire({
                        title: 'Success',
                        text: 'User details updated successfully.',
                        icon: 'success',
                    });
                    this.isEditing = false;
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
                }).finally(() => {
                    this.isSavingProfile = false;
                });
        },
        onFirstNameChange(event) {
            this.user.first_name = event.target.value;
        },
        onLastNameChange(event) {
            this.user.last_name = event.target.value;
        },
        onPasswordChange(event) {
            this.user.password = event.target.value;
        },
        onAddressChange(event) {
            this.user.address = event.target.value;
        },
        onEmailChange(event) {
            this.user.email = event.target.value;
        },
        cancelEdit() {
            this.isEditing = false;
            this.user = { ...this.initialUserData };
        },
    },
};
</script>
  
<style>
</style>
  