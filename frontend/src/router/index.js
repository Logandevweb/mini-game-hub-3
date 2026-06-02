import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import Login from '../views/Login.vue'
import Register from '../views/Register.vue'
import HomeView from '../views/HomeView.vue'
import GameView from '../views/GameView.vue'
import Leaderboard from '@/views/Leaderboard.vue'
import ProfileView from '@/views/ProfileView.vue'
import ProfileEditView from '@/views/ProfileEditView.vue'
import UsersList from '@/views/UsersList.vue'
import ForgotPassword from '@/views/ForgotPassword.vue'
import ResetPassword from '@/views/ResetPassword.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      redirect: '/home',
    },
    {
      path: '/login',
      name: 'login',
      component: Login,
    },
    {
      path: '/register',
      name: 'register',
      component: Register,
    },
    {
      path: '/home',
      name: 'home',
      component: HomeView,
    },
    {
      path: '/game/:name',
      name: 'game',
      component: GameView,
    },
    {
      path: '/leaderboard',
      name: 'leaderboard',
      component: Leaderboard,
    },
    {
      path: '/profile',
      name: 'profile',
      component: ProfileView,
      meta: { requiresAuth: true },
    },
    {
      path: '/profile/edit',
      name: 'profile-edit',
      component: ProfileEditView,
      meta: { requiresAuth: true },
    },
    {
      path: '/admin/users',
      name: 'admin-users',
      component: UsersList,
      meta: { requiresAuth: true, requiresAdmin: true },
    },
    {
      path: '/forgot-password',
      name: 'forgot-password',
      component: ForgotPassword,
    },
    {
      path: '/reset-password/:token',
      name: 'reset-password',
      component: ResetPassword,
    },
  ],
})

router.beforeEach((to, from) => {
  const auth = useAuthStore()

  if (to.meta.requiresAuth && !auth.isAuthenticated()) {
    return { name: 'login' }
  }

  return true
})

export default router
