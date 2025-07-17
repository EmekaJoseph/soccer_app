<script lang="ts" setup>
import { useUserDataStore } from '@/store/userDataStore';
const userData = useUserDataStore()

const selectedTournament = defineModel()
</script>

<template>
    <div class="col-12">
        <div class="col-lg-12 float-lg-en">
            <div class="card border-0">
                <div class="card-body">
                    <div>SELECT TOURNAMENT:</div>
                    <div class="mt-2">
                        <select v-model="selectedTournament" class="form-select text-uppercase cursor-pointer">
                            <option v-for="i in userData.tournaments" :key="i" :value="i">{{ i.title }}
                            </option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>