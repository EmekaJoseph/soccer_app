<script lang="ts" setup>
import { useUserDataStore } from '@/store/userDataStore';
const userData = useUserDataStore()

const selectedTournament = defineModel()
</script>

<template>
    <fieldset class="border-0 rounded-3 p-3 bg-light-subtle shadow-sm ">
        <legend class="text-muted float-none xsmall p-0 px-2 w-auto small fw-bolder">
            SELECT TOURNAMENT:
        </legend>
        <select v-model="selectedTournament" class="form-select text-uppercase cursor-pointer">
            <option v-for="i in userData.tournaments" :key="i" :value="i">{{ i.title }}
            </option>
        </select>
    </fieldset>

</template>