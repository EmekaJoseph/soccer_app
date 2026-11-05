import { onMounted, ref } from 'vue'
import { useStorage } from '@vueuse/core'
import { useUserDataStore } from '@/store/userDataStore'

/**
 * Loads the account's tournaments and keeps one selected; the choice is
 * remembered so switching between admin pages stays on the same tournament.
 * `onSelect` runs once on mount and again whenever the selection changes.
 */
export function useTournamentPicker(onSelect: (tourId: string, tournament: any) => unknown) {
    const userData = useUserDataStore()
    const selectedTournament = ref<any>(undefined)
    const rememberedId = useStorage('socc_selected_tournament', '')
    const loading = ref(true)

    async function changed() {
        if (!selectedTournament.value) return
        rememberedId.value = selectedTournament.value.tour_id
        userData.apiError = false
        await onSelect(selectedTournament.value.tour_id, selectedTournament.value)
        userData.pageError = userData.apiError
    }

    onMounted(async () => {
        userData.apiError = false
        userData.pageError = false
        await userData.getTournaments()
        userData.pageError = userData.apiError
        selectedTournament.value =
            userData.tournaments.find((t) => t.tour_id === rememberedId.value) ?? userData.tournaments[0]
        await changed()
        loading.value = false
    })

    return { selectedTournament, changed, loading }
}
