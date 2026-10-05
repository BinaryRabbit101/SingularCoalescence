<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/layouts/AdminLayout.vue';

interface Product {
    id: number;
    name: string;
    tagline?: string;
    type: string;
    url?: string;
    published: boolean;
    images: string[];
}

defineProps<{ products: Product[] }>();

function destroy(id: number) {
    if (confirm('Delete this product?')) {
        router.delete(`/admin/products/${id}`);
    }
}

function typeColor(type: string) {
    return type === 'app' ? '#f97316' : '#8b5cf6';
}
</script>

<template>
    <AdminLayout>
        <Head title="Products" />

        <div class="mb-8 flex items-center justify-between">
            <h1 class="text-2xl font-bold" style="color: #e8e8f0">Products</h1>
            <Link
                href="/admin/products/create"
                class="rounded px-4 py-2 text-sm tracking-widest uppercase transition-opacity"
                style="
                    background: linear-gradient(135deg, #8b5cf6, #f97316);
                    color: white;
                "
            >
                + New
            </Link>
        </div>

        <div
            v-if="products.length === 0"
            class="py-20 text-center"
            style="color: #4b5563"
        >
            No products yet.
        </div>

        <div
            v-else
            class="overflow-hidden rounded-xl border"
            style="border-color: rgba(255, 255, 255, 0.06)"
        >
            <table class="w-full text-sm">
                <thead>
                    <tr
                        style="
                            background: rgba(255, 255, 255, 0.04);
                            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
                        "
                    >
                        <th
                            class="px-4 py-3 text-left font-medium"
                            style="color: #9ca3af"
                        >
                            Name
                        </th>
                        <th
                            class="px-4 py-3 text-left font-medium"
                            style="color: #9ca3af"
                        >
                            Type
                        </th>
                        <th
                            class="px-4 py-3 text-left font-medium"
                            style="color: #9ca3af"
                        >
                            Images
                        </th>
                        <th
                            class="px-4 py-3 text-left font-medium"
                            style="color: #9ca3af"
                        >
                            Status
                        </th>
                        <th
                            class="px-4 py-3 text-right font-medium"
                            style="color: #9ca3af"
                        >
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="product in products"
                        :key="product.id"
                        style="
                            border-bottom: 1px solid rgba(255, 255, 255, 0.04);
                        "
                        class="transition-colors"
                        onmouseenter="
                            this.style.background = 'rgba(255,255,255,0.02)'
                        "
                        onmouseleave="this.style.background = 'transparent'"
                    >
                        <td class="px-4 py-3">
                            <div class="font-medium" style="color: #e8e8f0">
                                {{ product.name }}
                            </div>
                            <div
                                v-if="product.tagline"
                                class="mt-0.5 text-xs"
                                style="color: #6b7280"
                            >
                                {{ product.tagline }}
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <span
                                class="rounded px-2 py-0.5 text-xs font-medium tracking-wide uppercase"
                                :style="`color: ${typeColor(product.type)}; background: ${typeColor(product.type)}22;`"
                            >
                                {{ product.type }}
                            </span>
                        </td>
                        <td class="px-4 py-3" style="color: #6b7280">
                            {{ product.images?.length ?? 0 }}
                        </td>
                        <td class="px-4 py-3">
                            <span
                                v-if="product.published"
                                class="text-xs"
                                style="color: #22c55e"
                                >Published</span
                            >
                            <span v-else class="text-xs" style="color: #6b7280"
                                >Draft</span
                            >
                        </td>
                        <td class="space-x-3 px-4 py-3 text-right">
                            <Link
                                :href="`/admin/products/${product.id}/edit`"
                                class="text-xs transition-colors"
                                style="color: #8b5cf6"
                                onmouseenter="this.style.color = '#a78bfa'"
                                onmouseleave="this.style.color = '#8b5cf6'"
                            >
                                Edit
                            </Link>
                            <button
                                @click="destroy(product.id)"
                                class="text-xs transition-colors"
                                style="color: #6b7280"
                                onmouseenter="this.style.color = '#ef4444'"
                                onmouseleave="this.style.color = '#6b7280'"
                            >
                                Delete
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>
