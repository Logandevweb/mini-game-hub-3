<template>
  <NavView />

  <section class="min-h-screen bg-[#050816] text-white px-4 py-10">
    <div class="max-w-4xl mx-auto">
      <!-- TITLE -->
      <h1 class="text-3xl sm:text-5xl font-black tracking-wider mb-10 text-cyan-400 text-center">
        MON PROFIL
      </h1>

      <!-- PROFILE CARD -->
      <div
        class="bg-[#0B1020] border border-[#1E2A52] rounded-3xl p-6 sm:p-8 mb-10 shadow-[0_0_20px_rgba(34,211,238,0.15)]"
      >
        <div class="flex flex-col items-center text-center">
          <!-- AVATAR -->
          <div
            class="w-28 h-28 sm:w-32 sm:h-32 rounded-full border border-cyan-400 bg-cyan-500/10 overflow-hidden flex items-center justify-center mb-6"
          >
            <img v-if="currentAvatar" :src="currentAvatar" class="w-full h-full object-cover" />

            <span v-else class="text-cyan-300 text-4xl sm:text-5xl font-black">
              {{ profile.user.name?.charAt(0).toUpperCase() }}
            </span>
          </div>

          <!-- NAME -->
          <h2 class="text-2xl sm:text-3xl font-bold text-cyan-300 mb-1">
            {{ profile.user.name }}
          </h2>

          <!-- EMAIL -->
          <p class="text-gray-400 mb-1 text-sm sm:text-base">
            {{ profile.user.email }}
          </p>

          <!-- MEMBER SINCE -->
          <p class="text-gray-500 text-xs sm:text-sm">
            Membre depuis : {{ profile.user.created_at }}
          </p>

          <!-- EDIT BUTTON -->
          <router-link
            to="/profile/edit"
            class="mt-6 px-5 py-3 rounded-xl border border-cyan-400 text-cyan-400 hover:bg-cyan-400/10 transition uppercase tracking-wider text-sm sm:text-base"
          >
            Modifier mon profil
          </router-link>
        </div>
      </div>

      <!-- GAME SELECTOR -->
      <div class="flex flex-wrap justify-center gap-3 mb-10">
        <button
          v-for="game in games"
          :key="game.slug"
          @click="selectGame(game.slug)"
          class="px-4 sm:px-5 py-2.5 rounded-xl border transition-all duration-300 uppercase tracking-wider text-xs sm:text-sm"
          :class="
            game.slug === selectedGame
              ? 'border-cyan-400 text-cyan-400 bg-cyan-400/10 shadow-[0_0_15px_rgba(34,211,238,0.4)]'
              : 'border-[#1E2A52] text-gray-400 hover:border-cyan-400 hover:text-cyan-400'
          "
        >
          {{ game.name }}
        </button>
      </div>

      <!-- SCORES CARD -->
      <div
        v-if="paginatedScores.length > 0"
        class="bg-[#0B1020] border border-[#1E2A52] rounded-3xl p-6 sm:p-8 shadow-[0_0_20px_rgba(34,211,238,0.15)]"
      >
        <h2
          class="text-xl sm:text-2xl font-bold text-cyan-400 mb-6 uppercase tracking-wider text-center sm:text-left"
        >
          Mes scores — {{ currentGameName }}
        </h2>

        <ul class="space-y-3">
          <li
            v-for="(score, index) in paginatedScores"
            :key="score.id"
            class="bg-[#101935] border border-[#1E2A52] rounded-xl px-4 sm:px-6 py-4 flex justify-between items-center hover:bg-white/5 transition"
          >
            <!-- INDEX -->
            <span class="text-white font-bold text-lg sm:text-xl">
              {{ index + 1 + startIndex }}
            </span>

            <!-- SCORE -->
            <span class="text-cyan-400 font-bold text-lg sm:text-xl">
              {{ score.score }}
            </span>
          </li>
        </ul>

        <!-- PAGINATION -->
        <div class="flex flex-wrap justify-center items-center gap-3 sm:gap-4 py-6">
          <!-- Prev -->
          <button
            @click="prevPage"
            :disabled="currentPage === 1"
            class="px-3 sm:px-4 py-2 rounded-lg border border-[#1E2A52] text-gray-400 hover:text-cyan-400 hover:border-cyan-400 disabled:opacity-30 disabled:cursor-not-allowed text-sm"
          >
            Prev
          </button>

          <!-- Page numbers -->
          <div class="flex flex-wrap justify-center gap-2">
            <button
              v-for="page in totalPages"
              :key="page"
              @click="goToPage(page)"
              class="px-3 py-1 rounded-lg border text-xs sm:text-sm transition"
              :class="
                page === currentPage
                  ? 'border-cyan-400 text-cyan-400 bg-cyan-400/10'
                  : 'border-[#1E2A52] text-gray-400 hover:text-cyan-400 hover:border-cyan-400'
              "
            >
              {{ page }}
            </button>
          </div>

          <!-- Next -->
          <button
            @click="nextPage"
            :disabled="currentPage === totalPages"
            class="px-3 sm:px-4 py-2 rounded-lg border border-[#1E2A52] text-gray-400 hover:text-cyan-400 hover:border-cyan-400 disabled:opacity-30 disabled:cursor-not-allowed text-sm"
          >
            Next
          </button>
        </div>
      </div>

      <p v-else class="text-center text-gray-500 text-base sm:text-lg">Aucun score pour ce jeu.</p>
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'
import { useAuthStore } from '../stores/auth'
import NavView from './NavView.vue'

const auth = useAuthStore()
const profile = ref({ user: {}, games: [] })

const API_URL = import.meta.env.VITE_API_URL
const BASE_URL = API_URL.replace('/api', '')

// Avatar
const currentAvatar = computed(() => {
  if (!profile.value.user.avatar) return null
  return `${BASE_URL}/storage/${profile.value.user.avatar}`
})

// Jeux + scores
const games = ref([])
const selectedGame = ref(null)
const currentScores = ref([])
const currentGameName = ref('')

const selectGame = (slug) => {
  selectedGame.value = slug

  const game = games.value.find((g) => g.slug === slug)

  if (game) {
    currentScores.value = game.scores
    currentGameName.value = game.name
    currentPage.value = 1
  }
}

/* PAGINATION */
const currentPage = ref(1)
const itemsPerPage = 10

const totalPages = computed(() => {
  return Math.ceil(currentScores.value.length / itemsPerPage)
})

const startIndex = computed(() => {
  return (currentPage.value - 1) * itemsPerPage
})

const paginatedScores = computed(() => {
  return currentScores.value.slice(startIndex.value, startIndex.value + itemsPerPage)
})

const nextPage = () => {
  if (currentPage.value < totalPages.value) {
    currentPage.value++
    window.scrollTo({ top: 0, behavior: 'smooth' })
  }
}

const prevPage = () => {
  if (currentPage.value > 1) {
    currentPage.value--
    window.scrollTo({ top: 0, behavior: 'smooth' })
  }
}

const goToPage = (page) => {
  currentPage.value = page
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

onMounted(async () => {
  // Charger les infos du user
  const profileRes = await axios.get(`${API_URL}/profile`, {
    headers: { Authorization: `Bearer ${auth.token}` },
  })
  profile.value = profileRes.data

  // Charger les scores du user
  const scoresRes = await axios.get(`${API_URL}/scores/user`, {
    headers: { Authorization: `Bearer ${auth.token}` },
  })
  games.value = scoresRes.data

  // Sélectionner le premier jeu
  if (games.value.length > 0) {
    selectGame(games.value[0].slug)
  }
})
</script>
