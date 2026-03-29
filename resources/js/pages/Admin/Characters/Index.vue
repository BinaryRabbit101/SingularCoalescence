<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/layouts/AdminLayout.vue';

interface Character {
    id: number;
    name: string;
    slug: string;
    tagline?: string;
    published: boolean;
    sort_order: number;
}

defineProps<{
    characters: Character[];
}>();

function destroy(id: number, name: string) {
    if (confirm(`Delete "${name}"? This will also delete all diary entries and music tracks.`)) {
        router.delete(`/admin/characters/${id}`);
    }
}
</script>

<template>
    <AdminLayout>
        <Head title="Characters — Admin" />

        <div class="max-w-4xl">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-2xl font-bold" style="color: #e8e8f0;">Characters</h1>
                    <p class="text-sm mt-1" style="color: #6b7280;">{{ characters.length }} total</p>
                </div>
                <Link href="/admin/characters/create"
                      class="px-4 py-2 rounded text-xs tracking-widest uppercase font-medium transition-all"
                      style="background: linear-gradient(135deg, #8b5cf6, #f97316); color: white;">
                    + New Character
                </Link>
            </div>

            <div v-if="!characters.length" class="text-center py-16 rounded-xl border"
                 style="border-color: rgba(255,255,255,0.08); color: #4b5563;">
                No characters yet.
            </div>

            <div v-else class="space-y-3">
                <div v-for="character in characters" :key="character.id"
                     class="flex items-center gap-4 rounded-xl border p-4"
                     style="border-color: rgba(255,255,255,0.08); background: rgba(255,255,255,0.02);">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <p class="font-medium text-sm" style="color: #e8e8f0;">{{ character.name }}</p>
                            <span v-if="!character.published" class="text-xs px-1.5 py-0.5 rounded font-mono"
                                  style="background: rgba(249,115,22,0.1); color: #f97316; border: 1px solid rgba(249,115,22,0.2);">
                                draft
                            </span>
                        </div>
                        <p v-if="character.tagline" class="text-xs mt-0.5 truncate" style="color: #6b7280;">{{ character.tagline }}</p>
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <a :href="`/characters/${character.slug}`" target="_blank"
                           class="px-3 py-1.5 rounded border text-xs tracking-wider transition-colors"
                           style="border-color: rgba(255,255,255,0.1); color: #6b7280;"
                           onmouseenter="this.style.color='#9ca3af'" onmouseleave="this.style.color='#6b7280'">
                            View
                        </a>
                        <Link :href="`/admin/characters/${character.id}/edit`"
                              class="px-3 py-1.5 rounded border text-xs tracking-wider transition-colors"
                              style="border-color: rgba(139,92,246,0.3); color: #a78bfa;"
                              onmouseenter="this.style.background='rgba(139,92,246,0.08)'"
                              onmouseleave="this.style.background='transparent'"
                        >
                            Edit
                        </Link>
                        <button @click="destroy(character.id, character.name)"
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
