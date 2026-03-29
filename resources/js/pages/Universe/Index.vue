<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import PublicLayout from '@/layouts/PublicLayout.vue';

defineOptions({ layout: PublicLayout });

interface UniverseEntry {
    id: number;
    slug: string;
    name: string;
    category: 'faction' | 'location' | 'tech';
    description?: string;
    image?: string;
}

const props = defineProps<{
    entries: UniverseEntry[];
}>();

const activeCategory = ref<'all' | 'faction' | 'location' | 'tech'>('all');

const filtered = computed(() =>
    activeCategory.value === 'all'
        ? props.entries
        : props.entries.filter(e => e.category === activeCategory.value)
);

const categoryStyle = (cat: string) => {
    if (cat === 'faction') return { color: '#8b5cf6', border: 'rgba(139,92,246,0.3)', bg: 'rgba(139,92,246,0.08)' };
    if (cat === 'location') return { color: '#f97316', border: 'rgba(249,115,22,0.3)', bg: 'rgba(249,115,22,0.08)' };
    return { color: '#22c55e', border: 'rgba(34,197,94,0.3)', bg: 'rgba(34,197,94,0.08)' };
};
</script>

<template>
    <Head title="Universe" />

        <div class="max-w-7xl mx-auto px-6 py-20">
            <!-- Header -->
            <div class="mb-12">
                <p class="text-xs tracking-widest uppercase mb-3" style="color: #8b5cf6;">World Building</p>
                <h1 class="text-4xl font-bold mb-4" style="color: #e8e8f0;">The Universe</h1>
                <p class="text-base max-w-2xl" style="color: #6b7280;">
                    Factions pulling strings, locations scarred by history, and the technology that keeps it all barely together.
                </p>
            </div>

            <!-- Filter tabs -->
            <div class="flex items-center gap-2 mb-10 flex-wrap">
                <button
                    v-for="cat in [['all', 'All'], ['faction', 'Factions'], ['location', 'Locations'], ['tech', 'Technology']]"
                    :key="cat[0]"
                    @click="activeCategory = cat[0] as any"
                    class="px-4 py-2 rounded text-xs tracking-widest uppercase border transition-all duration-200"
                    :style="activeCategory === cat[0]
                        ? 'background: rgba(139,92,246,0.15); border-color: rgba(139,92,246,0.4); color: #a78bfa;'
                        : 'background: transparent; border-color: rgba(255,255,255,0.08); color: #6b7280;'"
                >
                    {{ cat[1] }}
                </button>
            </div>

            <!-- Grid -->
            <div v-if="filtered.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <Link
                    v-for="entry in filtered"
                    :key="entry.id"
                    :href="`/universe/${entry.slug}`"
                    class="group rounded-xl overflow-hidden border transition-all duration-300"
                    style="border-color: rgba(255,255,255,0.08); background: rgba(255,255,255,0.02);"
                    onmouseenter="this.style.borderColor='rgba(139,92,246,0.25)'; this.style.transform='translateY(-3px)'"
                    onmouseleave="this.style.borderColor='rgba(255,255,255,0.08)'; this.style.transform=''"
                >
                    <!-- Image -->
                    <div class="h-40 overflow-hidden relative"
                         :style="`background: linear-gradient(160deg, ${categoryStyle(entry.category).bg} 0%, rgba(10,10,15,1) 80%);`">
                        <img v-if="entry.image" :src="`/storage/${entry.image}`"
                             class="w-full h-full object-cover opacity-60 group-hover:opacity-80 transition-opacity"
                             :alt="entry.name" />
                        <div class="absolute bottom-3 left-3">
                            <span class="text-xs font-mono px-2 py-0.5 rounded border capitalize"
                                  :style="`color: ${categoryStyle(entry.category).color}; border-color: ${categoryStyle(entry.category).border}; background: rgba(10,10,15,0.7); backdrop-filter: blur(4px);`">
                                {{ entry.category }}
                            </span>
                        </div>
                    </div>

                    <div class="p-5">
                        <h3 class="font-bold text-base mb-2" style="color: #e8e8f0;">{{ entry.name }}</h3>
                        <p v-if="entry.description" class="text-sm line-clamp-2" style="color: #6b7280;">{{ entry.description }}</p>
                    </div>
                </Link>
            </div>

            <div v-else class="text-center py-20">
                <p style="color: #4b5563;">Nothing in this category yet.</p>
            </div>
        </div>
</template>
