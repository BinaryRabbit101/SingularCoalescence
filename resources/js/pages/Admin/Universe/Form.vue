<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import AdminLayout from '@/layouts/AdminLayout.vue';

interface UniverseEntry {
    id: number;
    category: string;
    name: string;
    description?: string;
    image?: string;
    sort_order: number;
    published: boolean;
}

const props = defineProps<{
    entry: UniverseEntry | null;
}>();

const isEdit = computed(() => !!props.entry);

const form = useForm({
    category: props.entry?.category ?? 'faction',
    name: props.entry?.name ?? '',
    description: props.entry?.description ?? '',
    sort_order: props.entry?.sort_order ?? 0,
    published: props.entry?.published ?? true,
    image: null as File | null,
});

const imagePreview = ref<string | undefined>(
    props.entry?.image ? `/storage/${props.entry.image}` : undefined
);

function onImage(e: Event) {
    const file = (e.target as HTMLInputElement).files?.[0];
    if (file) {
        form.image = file;
        imagePreview.value = URL.createObjectURL(file);
    }
}

function submit() {
    if (isEdit.value) {
        form.transform((data) => ({ ...data, _method: 'PUT' }))
            .post(`/admin/universe/${props.entry!.id}`, { forceFormData: true });
    } else {
        form.post('/admin/universe', { forceFormData: true });
    }
}
</script>

<template>
    <AdminLayout>
        <Head :title="isEdit ? 'Edit Universe Entry' : 'New Universe Entry'" />

        <div class="max-w-2xl">
            <div class="flex items-center gap-4 mb-8">
                <Link href="/admin/universe" class="text-sm transition-colors" style="color: #6b7280;"
                      onmouseenter="this.style.color='#9ca3af'" onmouseleave="this.style.color='#6b7280'">
                    ← Universe
                </Link>
                <h1 class="text-2xl font-bold" style="color: #e8e8f0;">{{ isEdit ? 'Edit Entry' : 'New Entry' }}</h1>
            </div>

            <form @submit.prevent="submit" class="space-y-6" enctype="multipart/form-data">
                <div class="rounded-xl border p-6 space-y-4" style="border-color: rgba(255,255,255,0.08); background: rgba(255,255,255,0.02);">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs tracking-wide mb-1.5" style="color: #9ca3af;">Category *</label>
                            <select v-model="form.category" required
                                    class="w-full rounded border px-3 py-2 text-sm focus:outline-none"
                                    style="background: rgba(255,255,255,0.04); border-color: rgba(255,255,255,0.1); color: #e8e8f0;">
                                <option value="faction">Faction</option>
                                <option value="location">Location</option>
                                <option value="tech">Technology</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs tracking-wide mb-1.5" style="color: #9ca3af;">Sort Order</label>
                            <input v-model="form.sort_order" type="number" min="0"
                                   class="w-full rounded border px-3 py-2 text-sm focus:outline-none"
                                   style="background: rgba(255,255,255,0.04); border-color: rgba(255,255,255,0.1); color: #e8e8f0;" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs tracking-wide mb-1.5" style="color: #9ca3af;">Name *</label>
                        <input v-model="form.name" type="text" required
                               class="w-full rounded border px-3 py-2 text-sm focus:outline-none"
                               style="background: rgba(255,255,255,0.04); border-color: rgba(255,255,255,0.1); color: #e8e8f0;" />
                        <p v-if="form.errors.name" class="text-xs mt-1" style="color: #ef4444;">{{ form.errors.name }}</p>
                    </div>

                    <div>
                        <label class="block text-xs tracking-wide mb-1.5" style="color: #9ca3af;">Description</label>
                        <textarea v-model="form.description" rows="6"
                                  class="w-full rounded border px-3 py-2 text-sm focus:outline-none resize-none"
                                  style="background: rgba(255,255,255,0.04); border-color: rgba(255,255,255,0.1); color: #e8e8f0;"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs tracking-wide mb-2" style="color: #9ca3af;">Image</label>
                        <div v-if="imagePreview" class="h-32 rounded-lg overflow-hidden mb-2 border"
                             style="border-color: rgba(255,255,255,0.06);">
                            <img :src="imagePreview" class="w-full h-full object-cover" />
                        </div>
                        <input type="file" accept="image/*" @change="onImage"
                               class="block w-full text-xs" style="color: #6b7280;" />
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
                    <Link href="/admin/universe" class="text-sm transition-colors" style="color: #6b7280;"
                          onmouseenter="this.style.color='#9ca3af'" onmouseleave="this.style.color='#6b7280'">
                        Cancel
                    </Link>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
