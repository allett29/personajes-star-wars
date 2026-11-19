<script setup>
import { cardPanel } from '@/lib/clasesUi';
</script>

<template>
    <section :class="cardPanel">
        <slot />
    </section>
</template>