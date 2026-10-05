<script setup lang="ts">
import { onBeforeUnmount, onMounted, ref } from 'vue';

interface Banner {
    src: string;
    alt: string;
}

const props = defineProps<{
    banners: Banner[];
}>();

const ADVANCE_MS = 7000;
const SWIPE_PX = 40;

const current = ref(0);
let timer: ReturnType<typeof setInterval> | null = null;
let touchStartX: number | null = null;

function show(index: number): void {
    const count = props.banners.length;
    current.value = ((index % count) + count) % count;
}

// Once someone picks a slide themselves, stop moving it under them.
function stopAuto(): void {
    if (timer) {
        clearInterval(timer);
        timer = null;
    }
}

function pick(index: number): void {
    stopAuto();
    show(index);
}

function onTouchStart(event: TouchEvent): void {
    touchStartX = event.touches[0]?.clientX ?? null;
}

function onTouchEnd(event: TouchEvent): void {
    if (touchStartX === null) {
        return;
    }

    const dx = (event.changedTouches[0]?.clientX ?? touchStartX) - touchStartX;
    touchStartX = null;

    if (Math.abs(dx) >= SWIPE_PX) {
        pick(current.value + (dx < 0 ? 1 : -1));
    }
}

onMounted(() => {
    const reducedMotion = window.matchMedia(
        '(prefers-reduced-motion: reduce)',
    ).matches;

    if (props.banners.length > 1 && !reducedMotion) {
        timer = setInterval(() => show(current.value + 1), ADVANCE_MS);
    }
});

onBeforeUnmount(stopAuto);
</script>

<template>
    <div
        role="region"
        aria-roledescription="carousel"
        aria-label="Pictures from the story"
    >
        <div
            class="relative aspect-[21/9] w-full overflow-hidden rounded-xl border"
            style="border-color: rgba(255, 255, 255, 0.08)"
            @touchstart.passive="onTouchStart"
            @touchend="onTouchEnd"
        >
            <img
                v-for="(banner, index) in banners"
                :key="banner.src"
                :src="`/storage/${banner.src}`"
                :alt="banner.alt"
                :aria-hidden="index !== current"
                :loading="index === 0 ? 'eager' : 'lazy'"
                class="absolute inset-0 h-full w-full object-cover transition-opacity duration-700"
                :class="index === current ? 'opacity-100' : 'opacity-0'"
                draggable="false"
            />
        </div>

        <div
            v-if="banners.length > 1"
            class="mt-2 flex items-center justify-center gap-1"
        >
            <button
                v-for="(banner, index) in banners"
                :key="banner.src"
                type="button"
                class="flex h-8 w-8 items-center justify-center"
                :aria-label="`Show picture ${index + 1} of ${banners.length}`"
                :aria-current="index === current ? 'true' : undefined"
                @click="pick(index)"
            >
                <span
                    class="block h-2 rounded-full transition-all duration-300"
                    :class="index === current ? 'w-6' : 'w-2'"
                    :style="{
                        background:
                            index === current
                                ? 'linear-gradient(90deg, #8b5cf6, #f97316)'
                                : 'rgba(255, 255, 255, 0.25)',
                    }"
                ></span>
            </button>
        </div>
    </div>
</template>
