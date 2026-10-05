<script setup lang="ts">
import { ref } from 'vue';

interface DiaryEntry {
    id: number;
    entry_number: number;
    title: string;
    content: string;
    entry_date?: string;
    published: boolean;
}

defineProps<{
    entry: DiaryEntry;
}>();

const open = ref(false);
</script>

<template>
    <div
        class="overflow-hidden rounded-lg border transition-all duration-300"
        :style="
            open
                ? 'border-color: rgba(139,92,246,0.4); background: rgba(139,92,246,0.05);'
                : 'border-color: rgba(255,255,255,0.08); background: rgba(255,255,255,0.02);'
        "
    >
        <!-- Header -->
        <button
            @click="open = !open"
            class="flex w-full items-center gap-4 px-5 py-4 text-left"
        >
            <span
                class="flex-shrink-0 font-mono text-xs"
                style="color: #8b5cf6; min-width: 4rem"
            >
                #{{ String(entry.entry_number).padStart(3, '0') }}
            </span>
            <div class="min-w-0 flex-1">
                <p class="text-sm font-medium" style="color: #e8e8f0">
                    {{ entry.title }}
                </p>
                <p
                    v-if="entry.entry_date"
                    class="mt-0.5 text-xs"
                    style="color: #6b7280"
                >
                    {{
                        new Date(entry.entry_date).toLocaleDateString('en-US', {
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric',
                        })
                    }}
                </p>
            </div>
            <span
                class="flex-shrink-0 text-xs transition-transform duration-200"
                style="color: #6b7280"
                :style="open ? 'transform: rotate(180deg);' : ''"
            >
                ▾
            </span>
        </button>

        <!-- Content -->
        <div v-if="open" class="px-5 pb-5">
            <div
                class="mb-4 border-t"
                style="border-color: rgba(255, 255, 255, 0.06)"
            ></div>
            <div
                class="prose prose-sm max-w-none text-sm leading-relaxed whitespace-pre-wrap"
                style="color: #9ca3af"
            >
                {{ entry.content }}
            </div>
        </div>
    </div>
</template>
