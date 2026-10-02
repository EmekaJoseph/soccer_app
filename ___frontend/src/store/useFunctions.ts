import Swal from 'sweetalert2'
import { useToast } from 'vue-toast-notification'
import { hostURL } from '@/store/axiosManager'

type DebounceFunction<T extends (...args: any[]) => any> = ((...args: Parameters<T>) => void) & { cancel: () => void }

const toaster = () => useToast({ position: 'top-right' })

export default {
    isValidEmail: (email: string) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email),

    truncateStr: (str: string, num: number) => (str.length > num ? str.slice(0, num) + '...' : str),

    toast: {
        success: (text: string) => toaster().success(text),
        error: (text: string) => toaster().error(text),
        info: (text: string) => toaster().default(text),
        warning: (text: string) => toaster().warning(text),
    },

    confirm: (text: string, btnText: string) =>
        Swal.fire({
            text,
            showCancelButton: true,
            confirmButtonText: btnText,
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            width: '320px',
            customClass: { confirmButton: 'swal-confirm-button', cancelButton: 'swal-cancel-button' },
        }),

    confirmDelete: (text: string, btnText: string) =>
        Swal.fire({
            text,
            showCancelButton: true,
            confirmButtonText: btnText,
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#dc3545',
            reverseButtons: true,
            width: '320px',
            customClass: { confirmButton: 'swal-confirm-button-delete', cancelButton: 'swal-cancel-button' },
        }),

    confirmOptions: (text: string, btnTextConfirm: string, btnTextDeny: string) =>
        Swal.fire({
            text,
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: btnTextConfirm,
            denyButtonText: btnTextDeny,
            reverseButtons: true,
            customClass: {
                confirmButton: 'swal-confirm-button',
                cancelButton: 'swal-cancel-button',
                denyButton: 'swal-confirm-button-delete',
            },
        }),

    capsFirstLetter: (value: string) => value.charAt(0).toUpperCase() + value.slice(1),

    /** "Quarter_Final" -> "Quarter Final" */
    stageLabel: (stage?: string | null, fallback = 'Match') => (stage ? stage.replaceAll('_', ' ') : fallback),

    debounce<T extends (...args: any[]) => any>(func: T, delay: number): DebounceFunction<T> {
        let timer: ReturnType<typeof setTimeout> | undefined
        const debounced = (...args: Parameters<T>) => {
            clearTimeout(timer)
            timer = setTimeout(() => func(...args), delay)
        }
        debounced.cancel = () => clearTimeout(timer)
        return debounced
    },

    greet: () => {
        const hour = new Date().getHours()
        return hour < 12 ? 'Good morning' : hour < 18 ? 'Good afternoon' : 'Good evening'
    },

    dateDisplay: (date: string | Date, withTime = false) =>
        new Date(date).toLocaleString(undefined, {
            month: 'short', day: 'numeric', year: 'numeric',
            ...(withTime ? { hour: '2-digit', minute: '2-digit' } : {}),
        }),

    /** ISO string -> value for <input type="datetime-local"> in the viewer's timezone. */
    toDatetimeLocal: (date: string | Date) => {
        const d = new Date(date)
        return new Date(d.getTime() - d.getTimezoneOffset() * 60000).toISOString().slice(0, 16)
    },

    /** Full URL of an uploaded image (paths are stored relative to the API's public folder). */
    resolvePhotoSrc: (path?: string | null) => (path ? `${hostURL}/${path}` : ''),
}
