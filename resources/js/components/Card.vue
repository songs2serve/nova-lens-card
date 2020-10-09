<template>
    <card class="h-auto overflow-hidden">
        <a class="flex items-center justify-between px-3 py-3 text-base font-bold no-underline border-b border-50 text-80 hover:bg-20" :href="lensUrl">
            {{ card.name }}

            <svg class="w-4 h-4 text-70" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>

        <div class="relative w-full overflow-x-auto">
            <resource-table
                class="text-sm table-tight"
                :authorized-to-relate="false"
                :resource-name="card.resourceName"
                :resources="card.resources"
                :actions-are-available="false"
                :should-show-checkboxes="false"
                ref="resourceTable"
            />
        </div>
    </card>
</template>

<script>
export default {
    props: ['card'],

    computed: {
        lensUrl() {
            return Nova.config.base + '/resources/' + this.card.resourceName + '/lens/' + this.card.lens.uriKey
        }
    }
}
</script>
