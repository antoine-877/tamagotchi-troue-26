<script setup>
import LcdScreen from '@/components/LcdScreen.vue';
import PixelIcon from '@/components/PixelIcon.vue';
import { itemIcon } from '@/lib/items';

// The little LCD screen of a shop item: its pixel icon, dimmed and
// stamped « Épuisé » when the stock is empty.
defineProps({
    item: { type: Object, required: true },
});
</script>

<template>
    <div class="relative shrink-0">
        <LcdScreen
            :grain="5"
            class="grid size-24 place-items-center rounded-xl border-4 border-ink"
        >
            <PixelIcon
                :name="itemIcon(item)"
                class="size-16"
                :class="{ 'opacity-25': item.stock === 0 }"
            />
        </LcdScreen>
        <span
            v-if="item.stock === 0"
            class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 -rotate-12 border-2 border-ink bg-ink px-2 py-1 font-mono text-[15px] font-bold tracking-widest text-rose uppercase"
        >
            Épuisé
        </span>
    </div>
</template>
