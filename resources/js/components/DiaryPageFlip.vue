<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue';

interface DiaryEntry {
    id: number;
    entry_number: number;
    title: string;
    content: string;
    entry_date?: string;
    published: boolean;
}

const props = defineProps<{
    entries: DiaryEntry[];
}>();

const currentIndex = ref(0);
const flipClass = ref('');
const isAnimating = ref(false);

const currentEntry = computed(() => props.entries[currentIndex.value]);
const total = computed(() => props.entries.length);

function formatDate(dateStr?: string): string {
    if (!dateStr) {
        return '';
    }

    return new Date(dateStr).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });
}

function formatEntryNumber(num: number): string {
    return `#${String(num).padStart(3, '0')}`;
}

function navigate(direction: 'next' | 'prev') {
    if (isAnimating.value) {
        return;
    }

    if (direction === 'next' && currentIndex.value >= total.value - 1) {
        return;
    }

    if (direction === 'prev' && currentIndex.value <= 0) {
        return;
    }

    isAnimating.value = true;
    const outClass =
        direction === 'next' ? 'page-flip-out' : 'page-flip-out-reverse';
    const inClass =
        direction === 'next' ? 'page-flip-in' : 'page-flip-in-reverse';

    flipClass.value = outClass;

    setTimeout(() => {
        currentIndex.value += direction === 'next' ? 1 : -1;
        flipClass.value = inClass;

        setTimeout(() => {
            flipClass.value = '';
            isAnimating.value = false;
        }, 300);
    }, 290);
}

function onKeyDown(e: KeyboardEvent) {
    if (e.key === 'ArrowRight') {
        navigate('next');
    }

    if (e.key === 'ArrowLeft') {
        navigate('prev');
    }
}

onMounted(() => window.addEventListener('keydown', onKeyDown));
onUnmounted(() => window.removeEventListener('keydown', onKeyDown));
</script>

<template>
    <div class="mx-auto w-full max-w-2xl select-none">
        <!-- Empty state -->
        <div v-if="!total" class="py-20 text-center" style="color: #4b5563">
            No diary entries yet.
        </div>

        <template v-else>
            <!-- Page -->
            <div
                :class="[
                    'overflow-hidden rounded-xl shadow-2xl transition-shadow duration-300',
                    flipClass,
                ]"
                style="
                    background: linear-gradient(
                        160deg,
                        #13111a 0%,
                        #0f0d16 100%
                    );
                    border: 1px solid rgba(139, 92, 246, 0.25);
                    box-shadow:
                        0 20px 60px rgba(0, 0, 0, 0.6),
                        0 0 40px rgba(139, 92, 246, 0.08);
                    transform-origin: center center;
                "
            >
                <!-- Page header -->
                <div
                    class="border-b px-8 pt-5 pb-4"
                    style="
                        border-color: rgba(255, 255, 255, 0.06);
                        background: rgba(139, 92, 246, 0.04);
                    "
                >
                    <!-- Top navigation -->
                    <div class="mb-4 flex items-center justify-between">
                        <button
                            @click="navigate('prev')"
                            :disabled="currentIndex === 0 || isAnimating"
                            class="flex items-center gap-1.5 rounded-lg border px-3 py-1.5 text-xs tracking-widest uppercase transition-all duration-200"
                            :style="
                                currentIndex === 0
                                    ? 'opacity: 0.2; color: #6b7280; border-color: rgba(255,255,255,0.06); cursor: not-allowed;'
                                    : 'color: #a78bfa; border-color: rgba(139,92,246,0.3); background: rgba(139,92,246,0.06);'
                            "
                        >
                            ‹ Prev
                        </button>
                        <span class="font-mono text-xs" style="color: #4b5563"
                            >{{ currentIndex + 1 }} / {{ total }}</span
                        >
                        <button
                            @click="navigate('next')"
                            :disabled="
                                currentIndex === total - 1 || isAnimating
                            "
                            class="flex items-center gap-1.5 rounded-lg border px-3 py-1.5 text-xs tracking-widest uppercase transition-all duration-200"
                            :style="
                                currentIndex === total - 1
                                    ? 'opacity: 0.2; color: #6b7280; border-color: rgba(255,255,255,0.06); cursor: not-allowed;'
                                    : 'color: #a78bfa; border-color: rgba(139,92,246,0.3); background: rgba(139,92,246,0.06);'
                            "
                        >
                            Next ›
                        </button>
                    </div>
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <span
                                class="font-mono text-xs tracking-widest"
                                style="color: #8b5cf6"
                            >
                                {{
                                    formatEntryNumber(currentEntry.entry_number)
                                }}
                            </span>
                            <h2
                                class="mt-1 text-lg leading-snug font-semibold"
                                style="color: #e8e8f0"
                            >
                                {{ currentEntry.title }}
                            </h2>
                            <p
                                v-if="currentEntry.entry_date"
                                class="mt-1 text-xs"
                                style="color: #6b7280"
                            >
                                {{ formatDate(currentEntry.entry_date) }}
                            </p>
                        </div>
                        <!-- Decorative corner mark -->
                        <div
                            class="mt-1 flex-shrink-0 text-2xl opacity-20"
                            style="color: #8b5cf6"
                        >
                            ✦
                        </div>
                    </div>
                </div>

                <!-- Ruled lines background + content -->
                <div
                    class="min-h-72 px-8 py-6"
                    style="
                        background-image: repeating-linear-gradient(
                            to bottom,
                            transparent,
                            transparent 27px,
                            rgba(139, 92, 246, 0.06) 27px,
                            rgba(139, 92, 246, 0.06) 28px
                        );
                        background-attachment: local;
                    "
                >
                    <div
                        class="font-mono text-sm leading-7 whitespace-pre-wrap"
                        style="color: #c4c4d4; line-height: 28px"
                    >
                        {{ currentEntry.content }}
                    </div>
                </div>

                <!-- Page footer / navigation -->
                <div
                    class="flex items-center justify-between border-t px-8 py-5"
                    style="
                        border-color: rgba(255, 255, 255, 0.06);
                        background: rgba(0, 0, 0, 0.15);
                    "
                >
                    <!-- Prev -->
                    <button
                        @click="navigate('prev')"
                        :disabled="currentIndex === 0 || isAnimating"
                        class="flex items-center gap-2 rounded-lg border px-4 py-2 text-xs tracking-widest uppercase transition-all duration-200"
                        :style="
                            currentIndex === 0
                                ? 'opacity: 0.2; color: #6b7280; border-color: rgba(255,255,255,0.06); cursor: not-allowed;'
                                : 'color: #a78bfa; border-color: rgba(139,92,246,0.3); background: rgba(139,92,246,0.06);'
                        "
                    >
                        ‹ Prev
                    </button>

                    <!-- Counter -->
                    <div class="text-center">
                        <span class="font-mono text-xs" style="color: #6b7280">
                            {{ currentIndex + 1 }} / {{ total }}
                        </span>
                        <!-- Dot indicators (up to 7) -->
                        <div
                            class="mt-1.5 flex items-center justify-center gap-1"
                        >
                            <div
                                v-for="i in Math.min(total, 7)"
                                :key="i"
                                class="rounded-full transition-all duration-300"
                                :style="
                                    i - 1 === currentIndex
                                        ? 'width: 16px; height: 4px; background: #8b5cf6;'
                                        : 'width: 4px; height: 4px; background: rgba(255,255,255,0.15);'
                                "
                            ></div>
                            <span
                                v-if="total > 7"
                                class="ml-1 text-xs"
                                style="color: #4b5563"
                                >…</span
                            >
                        </div>
                    </div>

                    <!-- Next -->
                    <button
                        @click="navigate('next')"
                        :disabled="currentIndex === total - 1 || isAnimating"
                        class="flex items-center gap-2 rounded-lg border px-4 py-2 text-xs tracking-widest uppercase transition-all duration-200"
                        :style="
                            currentIndex === total - 1
                                ? 'opacity: 0.2; color: #6b7280; border-color: rgba(255,255,255,0.06); cursor: not-allowed;'
                                : 'color: #a78bfa; border-color: rgba(139,92,246,0.3); background: rgba(139,92,246,0.06);'
                        "
                    >
                        Next ›
                    </button>
                </div>
            </div>

            <!-- Keyboard hint -->
            <p class="mt-4 text-center text-xs" style="color: #374151">
                ← → arrow keys to navigate
            </p>
        </template>
    </div>
</template>
