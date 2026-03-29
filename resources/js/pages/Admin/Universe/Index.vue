<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/layouts/AdminLayout.vue';

interface UniverseEntry {
    id: number;
    name: string;
    category: string;
    published: boolean;
    sort_order: number;
}

defineProps<{
    entries: UniverseEntry[];
}>();

function destroy(id: number, name: string) {
    if (confirm(`Delete "${name}"?`)) {
        router.delete(`/admin/universe/${id}`);
    }
}

function categoryColor(cat: string) {
    if (cat === 'faction') return '#8b5cf6';
    if (cat === 'location') return '#f97316';
    return '#22c55e';
}
</script>

<template>
    <AdminLayout>
        <Head title="Universe — Admin" />

        <div class="max-w-4xl">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-2xl font-bold" style="color: #e8e8f0;">Universe Entries</h1>
                    <p class="text-sm mt-1" style="color: #6b7280;">{{ entries.length }} total</p>
                </div>
                <Link href="/admin/universe/create"
                      class="px-4 py-2 rounded text-xs tracking-widest uppercase font-medium"
                      style="background: linear-gradient(135deg, #8b5cf6, #f97316); color: white;">
                    + New Entry
                </Link>
            </div>

            <div v-if="!entries.length" class="text-center py-16 rounded-xl border"
                 style="border-color: rgba(255,255,255,0.08); color: #4b5563;">
                No universe entries yet.
            </div>

            <div v-else class="space-y-2">
                <div v-for="entry in entries" :key="entry.id"
                     class="flex items-center gap-4 rounded-xl border p-4"
                     style="border-color: rgba(255,255,255,0.08); background: rgba(255,255,255,0.02);">
                    <span class="text-xs font-mono px-2 py-0.5 rounded border capitalize flex-shrink-0"
                          :style="`color: ${categoryColor(entry.category)}; border-color: ${categoryColor(entry.category)}40;`">
                        {{ entry.category }}
                    </span>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium truncate" style="color: #e8e8f0;">{{ entry.name }}</p>
                    </div>
                    <span v-if="!entry.published" class="text-xs px-1.5 py-0.5 rounded font-mono flex-shrink-0"
                          style="background: rgba(249,115,22,0.1); color: #f97316; border: 1px solid rgba(249,115,22,0.2);">
                        draft
                    </span>
                    <div class="flex gap-2 flex-shrink-0">
                        <Link :href="`/admin/universe/${entry.id}/edit`"
                              class="px-3 py-1.5 rounded border text-xs tracking-wider transition-colors"
                              style="border-color: rgba(139,92,246,0.3); color: #a78bfa;"
                              onmouseenter="this.style.background='rgba(139,92,246,0.08)'"
                              onmouseleave="this.style.background='transparent'"
                        >
                            Edit
                        </Link>
                        <button @click="destroy(entry.id, entry.name)"
                                class="px-3 py-1.5 rounded border text-xs tracking-wider transition-colors"
                                style="border-color: rgba(239,68,68,0.2); color: #ef4444;"
                                onmouseenter="this.style.background='rgba(239,68,68,0.08)'"
                                onmouseleave="this.style.background='transparent'"
                        >
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
