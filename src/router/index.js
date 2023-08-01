import Vue from 'vue';
import Router from 'vue-router';
import LoginForm from '@/components/LoginForm.vue';
import RegistrationForm from '@/components/RegistrationForm.vue';
import WelcomePage from '@/components/WelcomePage.vue';
import ResetPasswordForm from '@/components/ResetPasswordForm.vue';
import UserDetailsPage from '@/components/UserDetailsPage.vue';

Vue.use(Router);

const router = new Router({
  routes: [
    { path: '/login', component: LoginForm },
    { path: '/register', component: RegistrationForm },
    { path: '/welcome', component: WelcomePage, meta: { requiresAuth: true } },
    { path: '/reset-password', component: ResetPasswordForm },
    { path: '/user-details', component: UserDetailsPage, meta: { requiresAuth: true } }, 
    { path: '*', redirect: 'login' }, 
  ]
});

router.beforeEach((to, from, next) => {
  if (to.matched.some((record) => record.meta.requiresAuth)) {
    const authToken = localStorage.getItem('authToken');
    if (!authToken) {
      next('/login');
    } else {
      next();
    }
  } else {
    next();
  }
});

export default router;

