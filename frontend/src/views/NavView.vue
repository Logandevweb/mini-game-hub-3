<template>
  <nav
    class="w-full h-20 bg-[#082032] border-b border-cyan-500/30 px-6 flex items-center justify-between shadow-[0_0_20px_rgba(56,189,248,0.15)]"
  >
    <!-- LOGO -->
    <div class="flex items-center gap-4 flex-1">
      <div
        class="w-10 h-10 bg-cyan-500/10 border border-cyan-400/40 rounded-xl flex items-center justify-center shadow-[0_0_10px_rgba(56,189,248,0.3)]"
      >
        <span class="text-cyan-300 font-black tracking-wide">MGB</span>
      </div>

      <span
        class="hidden sm:block text-xl font-black tracking-widest text-cyan-300 drop-shadow-[0_0_8px_rgba(56,189,248,0.6)]"
      >
        MINI GAME HUB
      </span>
    </div>

    <!-- DESKTOP LINKS -->
    <div
      class="hidden md:flex items-center justify-center gap-10 flex-none text-gray-300 font-medium text-sm tracking-widest"
    >
      <router-link to="/home" class="hover:text-cyan-400 transition" :class="isActive('/home')">
        HOME
      </router-link>

      <router-link
        to="/leaderboard"
        class="hover:text-cyan-400 transition"
        :class="isActive('/leaderboard')"
      >
        LEADERBOARD
      </router-link>

      <router-link
        v-if="auth.isAuthenticated() && auth.user?.role === 'admin'"
        to="/admin/users"
        class="hover:text-cyan-400 transition"
        :class="isActive('/admin')"
      >
        ADMIN
      </router-link>
    </div>

    <!-- AVATAR / LOGIN (DESKTOP) -->
    <div class="relative hidden md:flex items-center justify-end flex-1">
      <router-link
        v-if="!auth.isAuthenticated()"
        to="/login"
        class="px-5 py-2 bg-cyan-500/10 border border-cyan-400/40 rounded-xl text-cyan-300 font-semibold hover:bg-cyan-500/20 transition tracking-widest text-sm shadow-[0_0_10px_rgba(56,189,248,0.2)]"
      >
        LOGIN
      </router-link>

      <div v-else class="flex items-center">
        <div
          @click="toggleMenu"
          class="w-10 h-10 rounded-full bg-cyan-500/10 border border-cyan-400/40 flex items-center justify-center overflow-hidden cursor-pointer shadow-[0_0_10px_rgba(56,189,248,0.25)] hover:border-cyan-400 hover:shadow-[0_0_15px_rgba(56,189,248,0.4)] transition"
        >
          <img
            v-if="auth.user?.avatar"
            :src="`${BASE_URL}/storage/${auth.user.avatar}`"
            class="w-full h-full object-cover"
          />
          <span v-else class="text-cyan-300 font-bold text-lg">
            {{ auth.user?.name?.charAt(0).toUpperCase() }}
          </span>
        </div>

        <!-- DROPDOWN -->
        <div
          v-if="menuOpen"
          class="absolute right-0 top-full mt-3 w-64 bg-[#0F1628] border border-cyan-500/30 rounded-xl shadow-[0_0_25px_rgba(56,189,248,0.25)] animate-fadeIn z-50"
        >
          <div
            class="absolute -top-2 right-6 w-4 h-4 bg-[#0F1628] border-l border-t border-cyan-500/30 rotate-45"
          ></div>

          <div class="p-4">
            <p class="text-cyan-300 font-bold text-lg tracking-wide mb-2">{{ auth.user?.name }}</p>
            <div class="h-px bg-cyan-500/20 mb-3"></div>

            <router-link
              to="/profile"
              class="block w-full px-2 py-2 rounded-md text-gray-300 hover:text-cyan-400 hover:bg-cyan-400/10 transition"
              @click="closeMenu"
            >
              Profil
            </router-link>

            <div class="h-px bg-cyan-500/20 my-3"></div>

            <button
              @click="logout"
              class="w-full text-left px-2 py-2 rounded-md text-red-300 hover:text-red-400 hover:bg-red-500/10 transition"
            >
              Logout
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- BURGER MENU (MOBILE) -->
    <button @click="mobileOpen = !mobileOpen" class="md:hidden text-cyan-300 text-3xl">☰</button>
  </nav>

  <!-- MOBILE MENU -->
  <div
    v-if="mobileOpen"
    class="md:hidden w-full bg-[#0F1628] border-b border-cyan-500/30 shadow-[0_0_20px_rgba(56,189,248,0.25)] animate-fadeIn"
  >
    <div class="flex flex-col text-gray-300 text-lg tracking-widest p-6 gap-6">
      <router-link to="/home" class="hover:text-cyan-400 transition" @click="closeMobile">
        HOME
      </router-link>

      <router-link to="/leaderboard" class="hover:text-cyan-400 transition" @click="closeMobile">
        LEADERBOARD
      </router-link>

      <router-link
        v-if="auth.isAuthenticated() && auth.user?.role === 'admin'"
        to="/admin/users"
        class="hover:text-cyan-400 transition"
        @click="closeMobile"
      >
        ADMIN
      </router-link>

      <div class="h-px bg-cyan-500/20"></div>

      <router-link
        v-if="auth.isAuthenticated()"
        to="/profile"
        class="hover:text-cyan-400 transition"
        @click="closeMobile"
      >
        Profil
      </router-link>

      <button
        v-if="auth.isAuthenticated()"
        @click="logout"
        class="text-left text-red-300 hover:text-red-400 transition"
      >
        Logout
      </button>

      <router-link
        v-if="!auth.isAuthenticated()"
        to="/login"
        class="hover:text-cyan-400 transition"
        @click="closeMobile"
      >
        LOGIN
      </router-link>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRoute } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const route = useRoute()
const auth = useAuthStore()

const API_URL = import.meta.env.VITE_API_URL
const BASE_URL = API_URL.replace('/api', '')

const menuOpen = ref(false)
const mobileOpen = ref(false)

const toggleMenu = () => (menuOpen.value = !menuOpen.value)
const closeMenu = () => (menuOpen.value = false)
const closeMobile = () => (mobileOpen.value = false)

const logout = () => {
  auth.logout()
  menuOpen.value = false
  mobileOpen.value = false
}

const isActive = (path) => (route.path.startsWith(path) ? 'text-cyan-400' : '')
</script>

<style>
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(-6px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
.animate-fadeIn {
  animation: fadeIn 0.18s ease-out;
}
</style>
