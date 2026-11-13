<script setup>
import { computed } from 'vue';
import PixelIcon from '@/components/PixelIcon.vue';
import { isLow } from '@/lib/mood';
import { gauges } from '@/lib/species';

// A big gauge card: kicker, icon, large number, ten segments.
// The card turns rose when the gauge is low.
const props = defineProps({
    gauge: { type: String, required: true },
    value: { type: Number, required: true },
});

const low = computed(() => isLow(props.gauge, props.value));
</script>

<template>
    <div
        class="@container flex min-w-0 flex-col gap-1.5 border-4 border-ink px-3.5 pt-3 pb-3.5 text-ink transition-colors duration-300 ease-[steps(3,end)] motion-reduce:transition-none"
        :class="low ? 'bg-rose' : 'bg-paper'"
    >
        <div class="flex items-center justify-between gap-2">
            <span class="truncate kicker">{{ gauges[gauge] }}</span>
            <PixelIcon :name="gauge" class="size-6 shrink-0" />
        </div>
        <!-- A wide card (phone, one card per row) puts the segments beside the number. -->
        <div
            class="flex flex-col gap-2 @min-[18rem]:flex-row @min-[18rem]:items-center @min-[18rem]:gap-4"
        >
            <div class="flex items-end justify-between gap-2">
                <p
                    class="font-mono text-[clamp(2.5rem,5vw,3.75rem)] leading-none font-bold"
                >
                    {{ value }}<span class="sr-only"> sur 100</span>
                </p>
                <span
                    v-if="low"
                    class="mb-1 bg-ink px-1.5 py-0.5 font-mono text-[11px] font-bold tracking-widest text-rose uppercase"
                >
                    Basse
                </span>
            </div>
            <div aria-hidden="true" class="flex flex-1 gap-[3px]">
                <span
                    v-for="i in 10"
                    :key="i"
                    class="h-2.5 flex-1 border-2 border-ink"
                    :class="{ 'bg-ink': value >= i * 10 - 5 }"
                />
            </div>
        </div>
    </div>
</template>
