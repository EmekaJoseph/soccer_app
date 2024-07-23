<script lang="ts" setup>
import { useUserDataStore } from '@/store/userDataStore';
const userData = useUserDataStore()

const selectedTournament = defineModel()
</script>

<template>
    <select v-model="selectedTournament" class="form-select text-uppercase cursor-pointer">
        <option v-for="i in userData.tournaments" :key="i" :value="i">{{ i.title }}
        </option>
    </select>
</template>