<script setup lang="ts">
import { computed, onUnmounted, type ComponentPublicInstance } from 'vue';
import Sortable from 'sortablejs';
import { Head, Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/layouts/AdminLayout.vue';

interface Character {
    id: number;
    name: string;
}

interface DiaryEntry {
    id: number;
    entry_number: number;
    title: string;
    published: boolean;
    character: Character;
}

const props = defineProps<{
    entries: DiaryEntry[];
    characters: Character[];
}>();

const grouped = computed(() => {
    const map = new Map<number, { character: Character; entries: DiaryEntry[] }>();
    for (const entry of props.entries) {
        if (!map.has(entry.character.id)) {
            map.set(entry.character.id, { character: entry.character, entries: [] });
        }
        map.get(entry.character.id)!.entries.push(entry);
    }
    return [...map.values()].sort((a, b) => a.character.name.localeCompare(b.character.name));
});

const sortables = new Map<number, Sortable>();

function registerList(el: Element | ComponentPublicInstance | null, characterId: number) {
    if (!(el instanceof HTMLElement)) {
        sortables.get(characterId)?.destroy();
        sortables.delete(characterId);
        return;
    }
    if (sortables.has(characterId)) return;
    sortables.set(characterId, new Sortable(el, {
        animation: 150,
        handle: '.drag-handle',
        ghostClass: 'sortable-ghost',
        onEnd() {
            const ids = [...el.querySelectorAll('[data-id]')]
                .map(node => parseInt(node.getAttribute('data-id')!));
            router.post('/admin/diary-entries/reorder', { ids }, {
                preserveScroll: true,
                preserveState: true,
            });
        },
    }));
}

onUnmounted(() => {
    sortables.forEach(s => s.destroy());
    sortables.clear();
});

function destroy(id: number, title: string) {
    if (confirm(`Delete "${title}"?`)) {
        router.delete(`/admin/diary-entries/${id}`);
    }
}
</script>

<template>
    <AdminLayout>
        <Head title="Diary Entries — Admin" />

        <div class="max-w-4xl">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-2xl font-bold" style="color: #e8e8f0;">Diary Entries</h1>
                    <p class="text-sm mt-1" style="color: #6b7280;">{{ entries.length }} total</p>
                </div>
                <Link href="/admin/diary-entries/create"
                      class="px-4 py-2 rounded text-xs tracking-widest uppercase font-medium"
                      style="background: linear-gradient(135deg, #8b5cf6, #f97316); color: white;">
                    + New Entry
                </Link>
            </div>

            <div v-if="!entries.length" class="text-center py-16 rounded-xl border"
                 style="border-color: rgba(255,255,255,0.08); color: #4b5563;">
                No diary entries yet.
            </div>

            <div v-else class="space-y-10">
                <div v-for="group in grouped" :key="group.character.id">
                    <div class="flex items-center gap-3 mb-3">
                        <h2 class="text-xs font-semibold tracking-widest uppercase" style="color: #8b5cf6;">
                            {{ group.character.name }}
                        </h2>
                        <span class="text-xs" style="color: #4b5563;">
                            {{ group.entries.length }} {{ group.entries.length === 1 ? 'entry' : 'entries' }}
                        </span>
                        <div class="flex-1 h-px" style="background: rgba(139,92,246,0.15);"></div>
                    </div>

                    <div :ref="(el) => registerList(el, group.character.id)" class="space-y-2">
                        <div v-for="entry in group.entries" :key="entry.id" :data-id="entry.id"
                             class="flex items-center gap-4 rounded-xl border p-4"
                             style="border-color: rgba(255,255,255,0.08); background: rgba(255,255,255,0.02);">
                            <div class="drag-handle flex-shrink-0 cursor-grab active:cursor-grabbing" style="color: #374151;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                    <circle cx="9" cy="5" r="1.5"/><circle cx="9" cy="12" r="1.5"/><circle cx="9" cy="19" r="1.5"/>
                                    <circle cx="15" cy="5" r="1.5"/><circle cx="15" cy="12" r="1.5"/><circle cx="15" cy="19" r="1.5"/>
                                </svg>
                            </div>
                            <span class="text-xs font-mono flex-shrink-0 w-12" style="color: #8b5cf6;">
                                #{{ String(entry.entry_number).padStart(3, '0') }}
                            </span>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium truncate" style="color: #e8e8f0;">{{ entry.title }}</p>
                            </div>
                            <span v-if="!entry.published" class="text-xs px-1.5 py-0.5 rounded font-mono flex-shrink-0"
                                  style="background: rgba(249,115,22,0.1); color: #f97316; border: 1px solid rgba(249,115,22,0.2);">
                                draft
                            </span>
                            <div class="flex gap-2 flex-shrink-0">
                                <Link :href="`/admin/diary-entries/${entry.id}/edit`"
                                      class="px-3 py-1.5 rounded border text-xs tracking-wider transition-colors"
                                      style="border-color: rgba(139,92,246,0.3); color: #a78bfa;"
                                      onmouseenter="this.style.background='rgba(139,92,246,0.08)'"
                                      onmouseleave="this.style.background='transparent'"
                                >
                                    Edit
                                </Link>
                                <button @click="destroy(entry.id, entry.title)"
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
            </div>
        </div>
    </AdminLayout>
</template>
