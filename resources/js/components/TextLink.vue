<script setup lang="ts">
import type { LinkComponentBaseProps, Method } from '@inertiajs/core';
import { Link } from '@inertiajs/vue3';

// An underlined text link, 44 px tall. `plain` keeps the colour of the text
// around it (ink on a coloured flat) instead of the link colour.
type Props = {
    href: LinkComponentBaseProps['href'];
    method?: Method;
    as?: string;
    plain?: boolean;
};

defineProps<Props>();
</script>

<template>
    <Link
        :href="href"
        :method="method"
        :as="as"
        class="inline-flex min-h-11 items-center font-extrabold underline decoration-2 underline-offset-4 hover:decoration-4"
        :class="{ 'text-link': !plain }"
    >
        <slot />
    </Link>
</template>
