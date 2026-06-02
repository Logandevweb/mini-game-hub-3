<template>
  <section class="min-h-screen flex items-center justify-center bg-[#050816] px-4 sm:px-6 md:px-10">
    <div
      class="w-full max-w-md bg-[#0B1020] border border-cyan-400 rounded-2xl p-6 sm:p-8 md:p-10 shadow-[0_0_35px_rgba(34,211,238,0.4)]"
    >
      <!-- NOM DU SITE -->
      <h2
        class="text-3xl sm:text-4xl font-black text-cyan-400 text-center mb-4 sm:mb-6 tracking-wide"
      >
        MiniGameHub
      </h2>

      <h1 class="text-xl sm:text-2xl font-bold text-center text-cyan-200 mb-6 sm:mb-8">
        Connexion
      </h1>

      <form @submit.prevent="login" class="space-y-4 sm:space-y-5">
        <!-- EMAIL -->
        <div>
          <label class="text-gray-300 font-semibold text-sm sm:text-base">Email</label>
          <input
            v-model="email"
            type="email"
            placeholder="Entrez votre email"
            class="w-full mt-2 p-3 bg-[#050816] border border-cyan-400 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-cyan-300"
            required
          />
        </div>

        <!-- PASSWORD -->
        <div>
          <label class="text-gray-300 font-semibold text-sm sm:text-base">Mot de passe</label>
          <input
            v-model="password"
            type="password"
            placeholder="Entrez votre mot de passe"
            class="w-full mt-2 p-3 bg-[#050816] border border-cyan-400 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-cyan-300"
            required
          />
        </div>

        <!-- FORGOT PASSWORD -->
        <div class="text-right">
          <router-link
            to="/forgot-password"
            class="text-cyan-300 hover:text-cyan-200 text-xs sm:text-sm"
          >
            Mot de passe oublié
          </router-link>
        </div>

        <!-- SUBMIT -->
        <button
          type="submit"
          class="w-full bg-cyan-500 hover:bg-cyan-400 text-black font-bold p-3 rounded-lg transition text-sm sm:text-base"
        >
          Se connecter
        </button>
      </form>

      <!-- SEPARATOR -->
      <div class="flex items-center my-4 sm:my-6">
        <div class="flex-1 h-px bg-gray-700"></div>
        <span class="px-3 text-gray-400 text-xs sm:text-sm">ou</span>
        <div class="flex-1 h-px bg-gray-700"></div>
      </div>

      <!-- GUEST ACCESS -->
      <button
        @click="router.push('/home')"
        type="button"
        class="w-full bg-gray-700 hover:bg-gray-600 text-cyan-300 font-bold p-3 rounded-lg transition text-sm sm:text-base"
      >
        Accès invité
      </button>

      <!-- ERROR -->
      <p v-if="error" class="text-red-500 mt-4 text-center font-semibold text-sm sm:text-base">
        {{ error }}
      </p>

      <!-- REGISTER -->
      <p class="mt-6 text-center text-gray-300 text-sm sm:text-base">
        Pas encore de compte ?
        <router-link to="/register" class="text-cyan-300 hover:text-cyan-200 font-semibold">
          Créer un compte
        </router-link>
      </p>
    </div>
  </section>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'
import { useAuthStore } from '../stores/auth'
import { useRouter } from 'vue-router'

const auth = useAuthStore()
const router = useRouter()

const API_URL = import.meta.env.VITE_API_URL

const email = ref('')
const password = ref('')
const error = ref(null)

const login = async () => {
  error.value = null

  try {
    const response = await axios.post(`${API_URL}/login`, {
      email: email.value,
      password: password.value,
    })

    auth.setToken(response.data.token)
    auth.setUser(response.data.user)

    router.push('/home')
  } catch (err) {
    error.value = err.response?.data?.message || 'Identifiants incorrects'
  }
}
</script>
