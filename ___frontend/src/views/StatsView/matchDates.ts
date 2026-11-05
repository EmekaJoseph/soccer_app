export const NO_DATE_LABEL = 'Date to be confirmed'

function parse(value: string | null | undefined): Date | null {
    if (!value) return null
    const date = new Date(value)
    return Number.isNaN(date.getTime()) ? null : date
}

export function matchDayLabel(value: string | null | undefined): string {
    const date = parse(value)
    if (!date) return NO_DATE_LABEL
    return date.toLocaleDateString(undefined, { weekday: 'short', day: 'numeric', month: 'short', year: 'numeric' })
}

export function kickoffTime(value: string | null | undefined): string {
    return parse(value)?.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) ?? 'TBC'
}

/** Groups neighbouring items by day, keeping the order the API sorted them in. */
export function groupByMatchDay<T>(items: T[], dateOf: (item: T) => string | null | undefined) {
    const groups: { label: string; items: T[] }[] = []
    for (const item of items) {
        const label = matchDayLabel(dateOf(item))
        const last = groups[groups.length - 1]
        if (last?.label === label) last.items.push(item)
        else groups.push({ label, items: [item] })
    }
    return groups
}
