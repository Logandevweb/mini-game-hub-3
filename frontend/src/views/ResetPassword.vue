<template>
  <section class="min-h-screen flex items-center justify-center bg-[#050816] px-4 sm:px-6 md:px-10">
    <div
      class="w-full max-w-md bg-[#0B1020] border border-cyan-400 rounded-2xl p-6 sm:p-8 md:p-10 shadow-[0_0_35px_rgba(34,211,238,0.4)]"
    >
      <h2
        class="text-3xl sm:text-4xl font-black text-cyan-400 text-center mb-4 sm:mb-6 tracking-wide"
      >
        MiniGameHub
      </h2>

      <h1 class="text-xl sm:text-2xl font-bold text-center text-cyan-200 mb-6 sm:mb-8">
        Réinitialiser le mot de passe
      </h1>

      <form @submit.prevent="submit" class="space-y-4 sm:space-y-5">
        <!-- EMAIL -->
        <div>
          <label class="text-gray-300 font-semibold text-sm sm:text-base">Email</label>
          <input
            v-model="email"
            type="email"
            placeholder="Votre email"
            class="w-full mt-2 p-3 bg-[#050816] border border-cyan-400 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-cyan-300"
            required
          />
        </div>

        <!-- PASSWORD -->
        <div>
          <label class="text-gray-300 font-semibold text-sm sm:text-base"
            >Nouveau mot de passe</label
          >
          <input
            v-model="password"
            type="password"
            placeholder="Nouveau mot de passe"
            class="w-full mt-2 p-3 bg-[#050816] border border-cyan-400 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-cyan-300"
            required
          />
        </div>

        <!-- CONFIRM PASSWORD -->
        <div>
          <label class="text-gray-300 font-semibold text-sm sm:text-base"
            >Confirmer le mot de passe</label
          >
          <input
            v-model="passwordConfirm"
            type="password"
            placeholder="Confirmez le mot de passe"
            class="w-full mt-2 p-3 bg-[#050816] border border-cyan-400 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-cyan-300"
            required
          />
        </div>

        <button
          type="submit"
          class="w-full bg-cyan-500 hover:bg-cyan-400 text-black font-bold p-3 rounded-lg transition text-sm sm:text-base"
        >
          Réinitialiser
        </button>
      </form>

      <p v-if="message" class="text-green-400 mt-4 text-center font-semibold text-sm sm:text-base">
        {{ message }}
      </p>

      <p v-if="error" class="text-red-500 mt-4 text-center font-semibold text-sm sm:text-base">
        {{ error }}
      </p>

      <p class="mt-6 text-center text-gray-300 text-sm sm:text-base">
        Retour à la
        <router-link to="/login" class="text-cyan-300 hover:text-cyan-200 font-semibold">
          connexion
        </router-link>
      </p>
    </div>
  </section>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'
import { useRoute } from 'vue-router'

const API_URL = import.meta.env.VITE_API_URL

const route = useRoute()

const email = ref('')
const password = ref('')
const passwordConfirm = ref('')
const error = ref(null)
const message = ref(null)

const submit = async () => {
  error.value = null
  message.value = null

  if (password.value !== passwordConfirm.value) {
    error.value = 'Les mots de passe ne correspondent pas'
    return
  }

  try {
    const res = await axios.post(`${API_URL}/reset-password`, {
      token: route.params.token,
      email: email.value,
      password: password.value,
      password_confirmation: passwordConfirm.value,
    })

    message.value = res.data.message || 'Mot de passe réinitialisé !'
  } catch (err) {
    error.value = err.response?.data?.message || 'Erreur lors de la réinitialisation'
  }
}
</script>
