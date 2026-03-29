<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';
import AdminLayout from '@/layouts/AdminLayout.vue';

interface Character {
    id: number;
    name: string;
}

interface DiaryEntry {
    id: number;
    character_id: number;
    entry_number: number;
    title: string;
    content: string;
    entry_date?: string;
    published: boolean;
}

const props = defineProps<{
    entry: DiaryEntry | null;
    characters: Character[];
}>();

const isEdit = computed(() => !!props.entry);

const form = useForm({
    character_id: props.entry?.character_id ?? '',
    entry_number: props.entry?.entry_number ?? 1,
    title: props.entry?.title ?? '',
    content: props.entry?.content ?? '',
    entry_date: props.entry?.entry_date ?? '',
    published: props.entry?.published ?? true,
});

function submit() {
    if (isEdit.value) {
        form.put(`/admin/diary-entries/${props.entry!.id}`);
    } else {
        form.post('/admin/diary-entries');
    }
}
</script>

<template>
    <AdminLayout>
        <Head :title="isEdit ? 'Edit Diary Entry' : 'New Diary Entry'" />

        <div class="max-w-3xl">
            <div class="flex items-center gap-4 mb-8">
                <Link href="/admin/diary-entries" class="text-sm transition-colors" style="color: #6b7280;"
                      onmouseenter="this.style.color='#9ca3af'" onmouseleave="this.style.color='#6b7280'">
                    ← Diary Entries
                </Link>
                <h1 class="text-2xl font-bold" style="color: #e8e8f0;">{{ isEdit ? 'Edit Entry' : 'New Diary Entry' }}</h1>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <div class="rounded-xl border p-6 space-y-4" style="border-color: rgba(255,255,255,0.08); background: rgba(255,255,255,0.02);">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs tracking-wide mb-1.5" style="color: #9ca3af;">Character *</label>
                            <select v-model="form.character_id" required
                                    class="w-full rounded border px-3 py-2 text-sm focus:outline-none"
                                    style="background: rgba(255,255,255,0.04); border-color: rgba(255,255,255,0.1); color: #e8e8f0;">
                                <option value="" disabled>Select character...</option>
                                <option v-for="char in characters" :key="char.id" :value="char.id">{{ char.name }}</option>
                            </select>
                            <p v-if="form.errors.character_id" class="text-xs mt-1" style="color: #ef4444;">{{ form.errors.character_id }}</p>
                        </div>
                        <div>
                            <label class="block text-xs tracking-wide mb-1.5" style="color: #9ca3af;">Entry Number *</label>
                            <input v-model="form.entry_number" type="number" min="1" required
                                   class="w-full rounded border px-3 py-2 text-sm focus:outline-none"
                                   style="background: rgba(255,255,255,0.04); border-color: rgba(255,255,255,0.1); color: #e8e8f0;" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs tracking-wide mb-1.5" style="color: #9ca3af;">Title *</label>
                        <input v-model="form.title" type="text" required
                               class="w-full rounded border px-3 py-2 text-sm focus:outline-none"
                               style="background: rgba(255,255,255,0.04); border-color: rgba(255,255,255,0.1); color: #e8e8f0;" />
                        <p v-if="form.errors.title" class="text-xs mt-1" style="color: #ef4444;">{{ form.errors.title }}</p>
                    </div>

                    <div>
                        <label class="block text-xs tracking-wide mb-1.5" style="color: #9ca3af;">Entry Date</label>
                        <input v-model="form.entry_date" type="date"
                               class="w-full rounded border px-3 py-2 text-sm focus:outline-none"
                               style="background: rgba(255,255,255,0.04); border-color: rgba(255,255,255,0.1); color: #e8e8f0;" />
                    </div>

                    <div>
                        <label class="block text-xs tracking-wide mb-1.5" style="color: #9ca3af;">Content *</label>
                        <textarea v-model="form.content" rows="16" required
                                  class="w-full rounded border px-3 py-2 text-sm focus:outline-none resize-none font-mono leading-relaxed"
                                  style="background: rgba(255,255,255,0.04); border-color: rgba(255,255,255,0.1); color: #e8e8f0;"></textarea>
                        <p v-if="form.errors.content" class="text-xs mt-1" style="color: #ef4444;">{{ form.errors.content }}</p>
                    </div>

                    <div class="flex items-center gap-3">
                        <input v-model="form.published" type="checkbox" id="published" class="rounded" />
                        <label for="published" class="text-sm" style="color: #9ca3af;">Published</label>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <button type="submit" :disabled="form.processing"
                            class="px-6 py-2.5 rounded text-sm tracking-widest uppercase font-medium transition-opacity"
                            style="background: linear-gradient(135deg, #8b5cf6, #f97316); color: white;"
                            :class="form.processing ? 'opacity-50' : ''">
                        {{ isEdit ? 'Update Entry' : 'Create Entry' }}
                    </button>
                    <Link href="/admin/diary-entries" class="text-sm transition-colors" style="color: #6b7280;"
                          onmouseenter="this.style.color='#9ca3af'" onmouseleave="this.style.color='#6b7280'">
                        Cancel
                    </Link>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
