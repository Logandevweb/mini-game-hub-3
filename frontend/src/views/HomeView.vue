<template>
  <NavView />
  <GamesList />
</template>

<script setup>
import { useAuthStore } from '../stores/auth'
import GamesList from '../views/GamesList.vue'
import NavView from './NavView.vue'

const auth = useAuthStore()
</script>
