<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import AdminLayout from '@/layouts/AdminLayout.vue';

interface Product {
    id: number;
    name: string;
    tagline?: string;
    description?: string;
    type: string;
    url?: string;
    images: string[];
    sort_order: number;
    published: boolean;
}

const props = defineProps<{
    product: Product | null;
}>();

const isEdit = computed(() => !!props.product);

const form = useForm({
    name: props.product?.name ?? '',
    tagline: props.product?.tagline ?? '',
    description: props.product?.description ?? '',
    type: props.product?.type ?? 'product',
    url: props.product?.url ?? '',
    sort_order: props.product?.sort_order ?? 0,
    published: props.product?.published ?? true,
    images: [] as File[],
});

const existingImages = ref<string[]>(props.product?.images ?? []);
const newPreviews = ref<string[]>([]);

function onImages(e: Event) {
    const files = Array.from((e.target as HTMLInputElement).files ?? []);
    form.images = files;
    newPreviews.value = files.map((f) => URL.createObjectURL(f));
}

function submit() {
    const fd = new FormData();
    fd.append('name', form.name);
    fd.append('tagline', form.tagline);
    fd.append('description', form.description);
    fd.append('type', form.type);
    fd.append('url', form.url);
    fd.append('sort_order', String(form.sort_order));
    fd.append('published', form.published ? '1' : '0');
    form.images.forEach((img) => fd.append('images[]', img));

    if (isEdit.value) {
        fd.append('_method', 'PUT');
        form.transform(() => fd as any).post(
            `/admin/products/${props.product!.id}`,
            { forceFormData: true },
        );
    } else {
        form.transform(() => fd as any).post('/admin/products', {
            forceFormData: true,
        });
    }
}
</script>

<template>
    <AdminLayout>
        <Head :title="isEdit ? 'Edit Product' : 'New Product'" />

        <div class="max-w-2xl">
            <div class="mb-8 flex items-center gap-4">
                <Link
                    href="/admin/products"
                    class="text-sm transition-colors"
                    style="color: #6b7280"
                    onmouseenter="this.style.color = '#9ca3af'"
                    onmouseleave="this.style.color = '#6b7280'"
                >
                    ← Products
                </Link>
                <h1 class="text-2xl font-bold" style="color: #e8e8f0">
                    {{ isEdit ? 'Edit Product' : 'New Product' }}
                </h1>
            </div>

            <form
                @submit.prevent="submit"
                class="space-y-6"
                enctype="multipart/form-data"
            >
                <div
                    class="space-y-4 rounded-xl border p-6"
                    style="
                        border-color: rgba(255, 255, 255, 0.08);
                        background: rgba(255, 255, 255, 0.02);
                    "
                >
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label
                                class="mb-1.5 block text-xs tracking-wide"
                                style="color: #9ca3af"
                                >Type *</label
                            >
                            <select
                                v-model="form.type"
                                required
                                class="w-full rounded border px-3 py-2 text-sm focus:outline-none"
                                style="
                                    background: rgba(255, 255, 255, 0.04);
                                    border-color: rgba(255, 255, 255, 0.1);
                                    color: #e8e8f0;
                                "
                            >
                                <option value="product">Product</option>
                                <option value="app">App</option>
                            </select>
                        </div>
                        <div>
                            <label
                                class="mb-1.5 block text-xs tracking-wide"
                                style="color: #9ca3af"
                                >Sort Order</label
                            >
                            <input
                                v-model="form.sort_order"
                                type="number"
                                min="0"
                                class="w-full rounded border px-3 py-2 text-sm focus:outline-none"
                                style="
                                    background: rgba(255, 255, 255, 0.04);
                                    border-color: rgba(255, 255, 255, 0.1);
                                    color: #e8e8f0;
                                "
                            />
                        </div>
                    </div>

                    <div>
                        <label
                            class="mb-1.5 block text-xs tracking-wide"
                            style="color: #9ca3af"
                            >Name *</label
                        >
                        <input
                            v-model="form.name"
                            type="text"
                            required
                            class="w-full rounded border px-3 py-2 text-sm focus:outline-none"
                            style="
                                background: rgba(255, 255, 255, 0.04);
                                border-color: rgba(255, 255, 255, 0.1);
                                color: #e8e8f0;
                            "
                        />
                        <p
                            v-if="form.errors.name"
                            class="mt-1 text-xs"
                            style="color: #ef4444"
                        >
                            {{ form.errors.name }}
                        </p>
                    </div>

                    <div>
                        <label
                            class="mb-1.5 block text-xs tracking-wide"
                            style="color: #9ca3af"
                            >Tagline</label
                        >
                        <input
                            v-model="form.tagline"
                            type="text"
                            class="w-full rounded border px-3 py-2 text-sm focus:outline-none"
                            style="
                                background: rgba(255, 255, 255, 0.04);
                                border-color: rgba(255, 255, 255, 0.1);
                                color: #e8e8f0;
                            "
                        />
                    </div>

                    <div>
                        <label
                            class="mb-1.5 block text-xs tracking-wide"
                            style="color: #9ca3af"
                            >Description</label
                        >
                        <textarea
                            v-model="form.description"
                            rows="5"
                            class="w-full resize-none rounded border px-3 py-2 text-sm focus:outline-none"
                            style="
                                background: rgba(255, 255, 255, 0.04);
                                border-color: rgba(255, 255, 255, 0.1);
                                color: #e8e8f0;
                            "
                        ></textarea>
                    </div>

                    <div>
                        <label
                            class="mb-1.5 block text-xs tracking-wide"
                            style="color: #9ca3af"
                            >URL (optional)</label
                        >
                        <input
                            v-model="form.url"
                            type="url"
                            class="w-full rounded border px-3 py-2 text-sm focus:outline-none"
                            style="
                                background: rgba(255, 255, 255, 0.04);
                                border-color: rgba(255, 255, 255, 0.1);
                                color: #e8e8f0;
                            "
                            placeholder="https://"
                        />
                    </div>

                    <div>
                        <label
                            class="mb-2 block text-xs tracking-wide"
                            style="color: #9ca3af"
                            >Images</label
                        >
                        <div
                            v-if="
                                existingImages.length > 0 &&
                                newPreviews.length === 0
                            "
                            class="mb-2 flex flex-wrap gap-2"
                        >
                            <img
                                v-for="(img, i) in existingImages"
                                :key="i"
                                :src="`/storage/${img}`"
                                class="h-20 w-20 rounded border object-cover"
                                style="border-color: rgba(255, 255, 255, 0.06)"
                            />
                        </div>
                        <div
                            v-if="newPreviews.length > 0"
                            class="mb-2 flex flex-wrap gap-2"
                        >
                            <img
                                v-for="(src, i) in newPreviews"
                                :key="i"
                                :src="src"
                                class="h-20 w-20 rounded border object-cover"
                                style="border-color: rgba(255, 255, 255, 0.06)"
                            />
                        </div>
                        <input
                            type="file"
                            accept="image/*"
                            multiple
                            @change="onImages"
                            class="block w-full text-xs"
                            style="color: #6b7280"
                        />
                        <p class="mt-1 text-xs" style="color: #4b5563">
                            {{
                                isEdit
                                    ? 'Selecting new images will replace all existing images.'
                                    : 'Select one or more images.'
                            }}
                        </p>
                    </div>

                    <div class="flex items-center gap-3">
                        <input
                            v-model="form.published"
                            type="checkbox"
                            id="published"
                            class="rounded"
                        />
                        <label
                            for="published"
                            class="text-sm"
                            style="color: #9ca3af"
                            >Published</label
                        >
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="rounded px-6 py-2.5 text-sm font-medium tracking-widest uppercase transition-opacity"
                        style="
                            background: linear-gradient(
                                135deg,
                                #8b5cf6,
                                #f97316
                            );
                            color: white;
                        "
                        :class="form.processing ? 'opacity-50' : ''"
                    >
                        {{ isEdit ? 'Update Product' : 'Create Product' }}
                    </button>
                    <Link
                        href="/admin/products"
                        class="text-sm transition-colors"
                        style="color: #6b7280"
                        onmouseenter="this.style.color = '#9ca3af'"
                        onmouseleave="this.style.color = '#6b7280'"
                    >
                        Cancel
                    </Link>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
