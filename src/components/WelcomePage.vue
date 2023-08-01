<template>
  <div class="container p-5">
    <div class="row">
      <div class="col-md-3">
        <NavigationBar :activeTab="activeTab" />
      </div>

      <div class="col-md-8">
        <div class="row align-items-center mb-4">
          <div class="col-2">
            <img src="../assets/icons/profile_icon.png" alt="People Icon" class="img-fluid" />
          </div>
          <div class="col-10">
            <h3 class="mb-0">Welcome back, {{ userName }}!</h3>
          </div>
        </div>

        <hr class="my-4">

        <div class="card mb-4">
          <div class="card-header">
            <h4>Create a Post</h4>
          </div>
          <div class="card-body">
            <form @submit.prevent="createPost">
              <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" v-model="postTitle" required />
              </div>
              <div class="mb-3">
                <label for="postType" class="form-label">Type</label>
                <select class="form-select" id="postType" v-model="postType" required>
                  <option value="news">News</option>
                  <option value="update">Update</option>
                  <option value="task">Task</option>
                </select>
              </div>
              <div class="mb-3">
                <label for="postMessage" class="form-label">Post</label>
                <textarea class="form-control" id="postMessage" v-model="postMessage" required></textarea>
              </div>
              <button type="submit" class="btn btn-primary">Create Post</button>
            </form>
          </div>
        </div>

        <div v-if="isFetchingPosts">
          <p class="text-muted mt-3">Fetching latest posts...</p>
        </div>
        <div v-else>
          <div v-if="formattedPosts.length > 0">
            <h4 class="mt-4">Latest Posts</h4>
            <div v-for="post in formattedPosts" :key="post.id" class="card mb-3">
              <div class="card-header">
                <h5>{{ post.title }}</h5>
                <small v-if="post.author_id === userId">Posted by you | {{ post.type }}</small>
                <small v-else>Posted by {{ post.author_name }} | {{ post.type }}</small>
              </div>
              <div class="card-body">
                <p>{{ post.post }}</p>
              </div>
              <div class="card-footer text-end">
                <div v-if="post.author_id === userId">
                  <span class="options" @click="editPost(post)">Edit</span>
                  <span> | </span>
                  <span class="options" @click="confirmDelete(post)">Delete</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div v-if="editedPost" class="modal" :class="{ 'show': selectedPostId === editedPost.id }"
          style="display: block;">
          <div class="modal-dialog">
            <div class="modal-content">
              <form @submit.prevent="savePost(editedPost)">
                <div class="modal-header">
                  <h5 class="modal-title">Edit Post</h5>
                  <button type="button" class="btn-close" aria-label="Close" @click="cancelEdit"></button>
                </div>
                <div class="modal-body">
                  <div class="mb-3">
                    <label for="editTitle" class="form-label">Title</label>
                    <input type="text" class="form-control" id="editTitle" v-model="editedPost.title" required />
                  </div>
                  <div class="mb-3">
                    <label for="editType" class="form-label">Type</label>
                    <select class="form-select" id="editType" v-model="editedPost.type" required>
                      <option value="news">News</option>
                      <option value="update">Update</option>
                      <option value="task">Task</option>
                    </select>
                  </div>
                  <div class="mb-3">
                    <label for="editPostMessage" class="form-label">Post</label>
                    <textarea class="form-control" id="editPostMessage" v-model="editedPost.post" required></textarea>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="submit" class="btn btn-primary">Save</button>
                  <button type="button" class="btn btn-secondary" @click="cancelEdit">Cancel</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import NavigationBar from '@/components/NavigationBar.vue';
import axios from 'axios';
import Swal from 'sweetalert2';
import { POST_BASE_URL } from '@/apiConfig';

export default {
  components: {
    NavigationBar,
  },
  data() {
    return {
      userName: '',
      activeTab: 'home',
      postTitle: '',
      postType: 'news',
      postMessage: '',
      isFetchingPosts: false,
      posts: [],
      userId: null,
      selectedPostId: null,
      editedPost: null,
    };
  },
  methods: {
    fetchUserNameFromLocalStorage() {
      const user = JSON.parse(localStorage.getItem('userData'));
      if (user && user.first_name) {
        this.userName = user.first_name;
        this.userId = user.id;
      }
    },
    createPost() {
      const payload = {
        title: this.postTitle,
        type: this.postType,
        post: this.postMessage,
        author_id: JSON.parse(localStorage.getItem('userData')).id,
      };

      axios.post(`${POST_BASE_URL}create`, payload)
        .then((response) => {
          this.postTitle = '';
          this.postType = 'news';
          this.postMessage = '';
          this.fetchAllPosts();
        })
        .catch((error) => {
          console.log(error.response);
          Swal.fire({
            title: 'Error',
            text: 'Error when creating post. Please try again later.',
            icon: 'error',
          });
        });
    },
    fetchAllPosts() {
      this.isFetchingPosts = true;
      axios
        .get(`${POST_BASE_URL}index`)
        .then((response) => {
          this.posts = response.data.data.map(post => ({
            ...post,
            isEditing: false,
            showDeleteConfirmation: false,
          }));
        })
        .catch((error) => {
          console.log(error.response);
          Swal.fire({
            title: 'Error',
            text: 'Error fetching posts. Please try again later.',
            icon: 'error',
          });
        })
        .finally(() => {
          this.isFetchingPosts = false;
        });
    },
    formatPostType(type) {
      switch (type) {
        case 'news':
          return 'News';
        case 'update':
          return 'Update';
        case 'task':
          return 'Task';
        default:
          return '';
      }
    },
    editPost(post) {
      this.editedPost = {
        id: post.id,
        title: post.title,
        type: post.type.toLowerCase(),
        post: post.post,
      };
      this.selectedPostId = post.id;
      post.originalTitle = post.title;
      post.originalType = post.type;
      post.originalPost = post.post;
    },
    confirmDelete(post) {
      Swal.fire({
        title: 'Are you sure?',
        text: 'You are about to delete this post. This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sadly, yes',
        cancelButtonText: 'Cancel',
      }).then((result) => {
        if (result.isConfirmed) {
          this.deletePost(post); 
        }
      });
    },
    savePost(post) {
      const payload = {
        id: post.id,
        title: post.title,
        type: post.type,
        post: post.post,
        author_id: JSON.parse(localStorage.getItem('userData')).id,
      };

      axios.put(`${POST_BASE_URL}update/${post.id}`, payload)
        .then((response) => {
          this.selectedPostId = null;
          this.editedPost = null;
          this.fetchAllPosts(); 
        })
        .catch((error) => {
          console.log(error.response);
          Swal.fire({
            title: 'Error',
            text: 'Error when updating post. Please try again later.',
            icon: 'error',
          });
        });
    },
    cancelEdit() {
      this.selectedPostId = null;
      this.editedPost = null;
    },
    deletePost(post) {
      axios
        .delete(`${POST_BASE_URL}delete/${post.id}`)
        .then((response) => {
          Swal.fire({
            title: 'Deleted!',
            text: 'Your post has been deleted successfully.',
            icon: 'success',
          }).then(() => {
            this.fetchAllPosts();
          });
        })
        .catch((error) => {
          console.log(error.response);
          Swal.fire({
            title: 'Error',
            text: 'Error deleting the post. Please try again later.',
            icon: 'error',
          });
        });
    },
  },
  created() {
    this.fetchUserNameFromLocalStorage();
    this.fetchAllPosts();
  },
  computed: {
    formattedPosts() {
      return this.posts.map((post) => ({
        ...post,
        type: this.formatPostType(post.type),
      }));
    },
  },
};
</script>

<style>
.options {
  cursor: pointer;
}
</style>