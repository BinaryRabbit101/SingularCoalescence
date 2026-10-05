<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/layouts/PublicLayout.vue';

defineOptions({ layout: PublicLayout });

interface UniverseEntry {
    id: number;
    name: string;
    slug: string;
    category: 'faction' | 'location' | 'tech';
    description?: string;
    image?: string;
}

defineProps<{
    entry: UniverseEntry;
}>();

function categoryColor(cat: string) {
    if (cat === 'faction') {
        return '#8b5cf6';
    }

    if (cat === 'location') {
        return '#f97316';
    }

    return '#22c55e';
}
</script>

<template>
    <Head :title="entry.name" />

    <!-- Hero -->
    <div class="relative h-64 overflow-hidden">
        <img
            v-if="entry.image"
            :src="`/storage/${entry.image}`"
            class="h-full w-full object-cover opacity-30"
            :alt="entry.name"
        />
        <div
            class="absolute inset-0"
            style="
                background: linear-gradient(
                    to top,
                    #0a0a0f 30%,
                    transparent 80%
                );
            "
        ></div>
    </div>

    <div class="mx-auto max-w-4xl px-6 py-12">
        <!-- Breadcrumb -->
        <div
            class="mb-8 flex items-center gap-2 text-xs tracking-widest uppercase"
            style="color: #4b5563"
        >
            <Link
                href="/universe"
                style="color: #6b7280"
                onmouseenter="this.style.color = '#9ca3af'"
                onmouseleave="this.style.color = '#6b7280'"
            >
                Universe
            </Link>
            <span>›</span>
            <span
                class="capitalize"
                :style="`color: ${categoryColor(entry.category)};`"
                >{{ entry.category }}</span
            >
        </div>

        <!-- Category badge -->
        <span
            class="mb-4 inline-block rounded-full border px-3 py-1 font-mono text-xs tracking-widest capitalize uppercase"
            :style="`color: ${categoryColor(entry.category)}; border-color: ${categoryColor(entry.category)}40; background: ${categoryColor(entry.category)}10;`"
        >
            {{ entry.category }}
        </span>

        <h1 class="mb-6 text-4xl font-bold" style="color: #e8e8f0">
            {{ entry.name }}
        </h1>

        <div
            class="mb-8 h-px w-16"
            style="background: linear-gradient(90deg, #8b5cf6, #f97316)"
        ></div>

        <div
            v-if="entry.description"
            class="text-base leading-relaxed whitespace-pre-wrap"
            style="color: #9ca3af"
        >
            {{ entry.description }}
        </div>

        <div class="mt-12">
            <Link
                href="/universe"
                class="inline-flex items-center gap-2 text-sm tracking-widest uppercase transition-colors"
                style="color: #6b7280"
                onmouseenter="this.style.color = '#9ca3af'"
                onmouseleave="this.style.color = '#6b7280'"
            >
                ← Back to Universe
            </Link>
        </div>
    </div>
</template>
