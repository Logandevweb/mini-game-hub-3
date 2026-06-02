<template>
  <NavView />

  <section
    class="min-h-screen px-4 sm:px-6 md:px-10 py-10 text-white bg-linear-to-br from-[#050816] via-[#120826] to-[#050816]"
  >
    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-10 items-start">
      <!-- CADRE DU JEU -->
      <div
        class="lg:col-span-2 bg-[#08091A] rounded-3xl p-4 sm:p-6 md:p-8 border border-cyan-500/40 shadow-[0_0_25px_rgba(56,189,248,0.25)] relative overflow-hidden"
      >
        <!-- Glow -->
        <div
          class="pointer-events-none absolute -top-32 -left-32 w-60 sm:w-72 h-60 sm:h-72 bg-cyan-500/15 blur-3xl"
        ></div>
        <div
          class="pointer-events-none absolute -bottom-40 right-0 w-64 sm:w-80 h-64 sm:h-80 bg-purple-500/20 blur-3xl"
        ></div>

        <!-- TITLE -->
        <h1
          class="text-center text-2xl sm:text-3xl md:text-4xl font-black tracking-[0.25em] sm:tracking-[0.35em] text-cyan-300 mb-6 sm:mb-8 drop-shadow-[0_0_10px_rgba(56,189,248,0.6)]"
        >
          {{ gameName.toUpperCase() }}
        </h1>

        <!-- IFRAME -->
        <div
          class="rounded-2xl overflow-hidden border border-cyan-400/40 shadow-[0_0_20px_rgba(56,189,248,0.25)] bg-black/40"
        >
          <iframe
            ref="iframeRef"
            :src="gameUrl"
            class="w-full h-87.5 sm:h-112.5 md:h-137.5 lg:h-162.5 border-none"
          ></iframe>
        </div>
      </div>

      <!-- PANNEAU DROIT -->
      <div class="space-y-6">
        <!-- SESSION -->
        <div
          class="bg-[#08091A] rounded-3xl p-5 sm:p-6 border border-purple-500/40 shadow-[0_0_20px_rgba(168,85,247,0.25)] relative overflow-hidden"
        >
          <div
            class="pointer-events-none absolute -top-24 right-0 w-40 sm:w-52 h-40 sm:h-52 bg-purple-500/20 blur-3xl"
          ></div>

          <h2
            class="text-xs sm:text-sm font-semibold tracking-[0.3em] sm:tracking-[0.35em] text-purple-300 mb-4 uppercase drop-shadow-[0_0_6px_rgba(168,85,247,0.6)]"
          >
            TA SESSION
          </h2>

          <div class="space-y-2 text-sm">
            <div class="flex justify-between">
              <span class="text-gray-400">Dernier score</span>
              <span class="text-cyan-100 font-bold">
                {{ lastScore !== null ? lastScore : '-' }}
              </span>
            </div>
          </div>
        </div>

        <!-- LEADERBOARD -->
        <div
          class="bg-[#08091A] rounded-3xl p-5 sm:p-6 border border-cyan-500/40 shadow-[0_0_20px_rgba(56,189,248,0.25)]"
        >
          <h2
            class="text-xs sm:text-sm font-semibold tracking-[0.3em] sm:tracking-[0.35em] text-cyan-300 mb-3 uppercase text-center drop-shadow-[0_0_6px_rgba(56,189,248,0.6)]"
          >
            LEADERBOARD
          </h2>

          <ul class="space-y-3">
            <!-- #1 -->
            <li
              v-if="bestScores[0]"
              class="flex justify-between items-center bg-[#050816] border border-yellow-400/40 px-4 py-3 rounded-xl shadow-[0_0_12px_rgba(250,204,21,0.25)]"
            >
              <span class="font-black text-yellow-400 tracking-wide text-sm sm:text-base">
                #1 — {{ bestScores[0].user.name }}
              </span>
              <span class="text-yellow-400 font-black text-lg sm:text-xl">
                {{ bestScores[0].score }}
              </span>
            </li>

            <!-- #2 -->
            <li
              v-if="bestScores[1]"
              class="flex justify-between items-center bg-[#050816] border border-pink-500/40 px-4 py-3 rounded-xl shadow-[0_0_12px_rgba(236,72,153,0.25)]"
            >
              <span class="font-bold text-pink-400 tracking-wide text-sm sm:text-base">
                #2 — {{ bestScores[1].user.name }}
              </span>
              <span class="text-pink-400 font-black text-lg sm:text-xl">
                {{ bestScores[1].score }}
              </span>
            </li>

            <!-- #3 -->
            <li
              v-if="bestScores[2]"
              class="flex justify-between items-center bg-[#050816] border border-orange-500/40 px-4 py-3 rounded-xl shadow-[0_0_12px_rgba(56,189,248,0.25)]"
            >
              <span class="font-bold text-orange-400 tracking-wide text-sm sm:text-base">
                #3 — {{ bestScores[2].user.name }}
              </span>
              <span class="text-orange-400 font-black text-lg sm:text-xl">
                {{ bestScores[2].score }}
              </span>
            </li>

            <!-- AUTRES -->
            <li
              v-for="(score, index) in bestScores.slice(3)"
              :key="score.id"
              class="flex justify-between items-center bg-[#050816] border border-cyan-400/20 px-4 py-3 rounded-xl hover:border-cyan-400/60 transition"
            >
              <span class="text-white font-semibold tracking-wide text-sm sm:text-base">
                {{ index + 4 }} — {{ score.user.name }}
              </span>
              <span class="text-cyan-200 font-bold text-base sm:text-lg tracking-wide">
                {{ score.score }}
              </span>
            </li>
          </ul>

          <router-link
            :to="`/leaderboard?game=${gameName}`"
            class="block text-center text-cyan-300 text-xs tracking-widest mt-4 hover:text-cyan-400 transition"
          >
            Voir le classement complet →
          </router-link>
        </div>
      </div>
    </div>
  </section>

  <!-- MODAL SCORE FINAL -->
  <div
    v-if="finalScore !== null"
    class="fixed inset-0 bg-black/80 backdrop-blur-md flex items-center justify-center z-50 px-4"
  >
    <div
      class="relative bg-[#08091A] border border-cyan-500 rounded-3xl px-6 sm:px-10 py-10 sm:py-12 w-full max-w-lg text-center shadow-[0_0_40px_rgba(56,189,248,0.6)] animate-[modalPop_0.25s_ease-out]"
    >
      <h2
        class="text-xs sm:text-sm font-semibold tracking-[0.3em] sm:tracking-[0.4em] text-cyan-300 mb-4 uppercase drop-shadow-[0_0_8px_rgba(56,189,248,0.8)]"
      >
        SCORE FINAL
      </h2>

      <p
        class="text-5xl sm:text-6xl md:text-7xl font-black text-cyan-200 drop-shadow-[0_0_20px_rgba(56,189,248,1)] mb-8 tracking-widest"
      >
        {{ finalScore }}
      </p>

      <button
        @click="closeModal"
        class="px-6 sm:px-8 py-3 rounded-xl border border-cyan-400 text-cyan-200 hover:bg-cyan-400/10 transition uppercase tracking-[0.25em] sm:tracking-[0.3em] text-xs sm:text-sm font-semibold shadow-[0_0_12px_rgba(56,189,248,0.4)]"
      >
        CONTINUER
      </button>
    </div>
  </div>
</template>

<script setup>
import { useRoute } from 'vue-router'
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useAuthStore } from '../stores/auth'
import NavView from './NavView.vue'

const route = useRoute()
const iframeRef = ref(null)
const auth = useAuthStore()

const API_URL = import.meta.env.VITE_API_URL

const gameName = route.params.name
const gameUrl = `/games/index.html?game=${gameName}`

const finalScore = ref(null)
const lastScore = ref(null)
const bestScores = ref([])

// Charger leaderboard
const loadLeaderboard = async () => {
  const res = await axios.get(`${API_URL}/games/${gameName}/scores`)
  bestScores.value = res.data.best_scores
}

// Recharger le jeu
const reloadGame = () => {
  iframeRef.value.src = iframeRef.value.src
}

// Fermer modal + relancer jeu
const closeModal = () => {
  finalScore.value = null
  reloadGame()
}

onMounted(() => {
  loadLeaderboard()

  const iframe = iframeRef.value

  iframe.addEventListener('load', () => {
    iframe.contentWindow.onGameEnd = async (score) => {
      finalScore.value = score
      lastScore.value = score

      try {
        await axios.post(
          `${API_URL}/scores`,
          {
            game: gameName,
            score: score,
          },
          {
            headers: {
              Authorization: `Bearer ${auth.token}`,
            },
          },
        )

        await loadLeaderboard()
      } catch (error) {
        console.error('Erreur en envoyant le score :', error)
      }
    }
  })
})
</script>

<style>
@keyframes modalPop {
  from {
    opacity: 0;
    transform: scale(0.85);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}
</style>
