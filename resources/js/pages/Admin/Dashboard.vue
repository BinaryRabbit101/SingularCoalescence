<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AdminLayout from '@/layouts/AdminLayout.vue';

defineProps<{
    stats: {
        characters: number;
        diary_entries: number;
        music_tracks: number;
        universe_entries: number;
        products: number;
        novel: boolean;
    };
}>();

const items = [
    { label: 'Characters', key: 'characters', href: '/admin/characters', icon: '◈', color: '#8b5cf6' },
    { label: 'Diary Entries', key: 'diary_entries', href: '/admin/diary-entries', icon: '◉', color: '#f97316' },
    { label: 'Music Tracks', key: 'music_tracks', href: '/admin/music-tracks', icon: '◎', color: '#8b5cf6' },
    { label: 'Universe Entries', key: 'universe_entries', href: '/admin/universe', icon: '◇', color: '#f97316' },
    { label: 'Products', key: 'products', href: '/admin/products', icon: '◈', color: '#8b5cf6' },
];
</script>

<template>
    <AdminLayout>
        <Head title="Admin Dashboard" />

        <div class="max-w-4xl">
            <h1 class="text-2xl font-bold mb-2" style="color: #e8e8f0;">Dashboard</h1>
            <p class="text-sm mb-10" style="color: #6b7280;">Universe content management.</p>

            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-12">
                <Link
                    v-for="item in items"
                    :key="item.key"
                    :href="item.href"
                    class="rounded-xl p-5 border transition-all duration-200 cursor-pointer"
                    style="border-color: rgba(255,255,255,0.08); background: rgba(255,255,255,0.02);"
                    onmouseenter="this.style.borderColor='rgba(139,92,246,0.3)'"
                    onmouseleave="this.style.borderColor='rgba(255,255,255,0.08)'"
                >
                    <div class="text-2xl mb-3" :style="`color: ${item.color};`">{{ item.icon }}</div>
                    <div class="text-3xl font-bold mb-1" style="color: #e8e8f0;">
                        {{ item.key === 'novel' ? (stats.novel ? '1' : '0') : stats[item.key as keyof typeof stats] }}
                    </div>
                    <div class="text-xs tracking-wide uppercase" style="color: #6b7280;">{{ item.label }}</div>
                </Link>

                <!-- Novel card -->
                <Link href="/admin/novel"
                      class="rounded-xl p-5 border transition-all duration-200 cursor-pointer"
                      style="border-color: rgba(255,255,255,0.08); background: rgba(255,255,255,0.02);"
                      onmouseenter="this.style.borderColor='rgba(249,115,22,0.3)'"
                      onmouseleave="this.style.borderColor='rgba(255,255,255,0.08)'"
                >
                    <div class="text-2xl mb-3" style="color: #f97316;">◆</div>
                    <div class="text-3xl font-bold mb-1" style="color: #e8e8f0;">{{ stats.novel ? '1' : '0' }}</div>
                    <div class="text-xs tracking-wide uppercase" style="color: #6b7280;">Novel</div>
                </Link>
            </div>

            <!-- Quick links -->
            <div class="rounded-xl border p-6" style="border-color: rgba(255,255,255,0.08); background: rgba(255,255,255,0.02);">
                <h2 class="text-sm font-medium tracking-widest uppercase mb-4" style="color: #6b7280;">Quick Actions</h2>
                <div class="flex flex-wrap gap-3">
                    <Link href="/admin/characters/create"
                          class="px-4 py-2 rounded border text-xs tracking-widest uppercase transition-colors"
                          style="border-color: rgba(139,92,246,0.3); color: #a78bfa;"
                          onmouseenter="this.style.background='rgba(139,92,246,0.08)'"
                          onmouseleave="this.style.background='transparent'"
                    >
                        + New Character
                    </Link>
                    <Link href="/admin/diary-entries/create"
                          class="px-4 py-2 rounded border text-xs tracking-widest uppercase transition-colors"
                          style="border-color: rgba(255,255,255,0.1); color: #9ca3af;"
                          onmouseenter="this.style.background='rgba(255,255,255,0.04)'"
                          onmouseleave="this.style.background='transparent'"
                    >
                        + New Diary Entry
                    </Link>
                    <Link href="/admin/music-tracks/create"
                          class="px-4 py-2 rounded border text-xs tracking-widest uppercase transition-colors"
                          style="border-color: rgba(255,255,255,0.1); color: #9ca3af;"
                          onmouseenter="this.style.background='rgba(255,255,255,0.04)'"
                          onmouseleave="this.style.background='transparent'"
                    >
                        + Upload Track
                    </Link>
                    <Link href="/admin/universe/create"
                          class="px-4 py-2 rounded border text-xs tracking-widest uppercase transition-colors"
                          style="border-color: rgba(255,255,255,0.1); color: #9ca3af;"
                          onmouseenter="this.style.background='rgba(255,255,255,0.04)'"
                          onmouseleave="this.style.background='transparent'"
                    >
                        + Universe Entry
                    </Link>
                    <Link href="/" target="_blank"
                          class="px-4 py-2 rounded border text-xs tracking-widest uppercase transition-colors"
                          style="border-color: rgba(255,255,255,0.1); color: #9ca3af;"
                          onmouseenter="this.style.background='rgba(255,255,255,0.04)'"
                          onmouseleave="this.style.background='transparent'"
                    >
                        ↗ View Site
                    </Link>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
