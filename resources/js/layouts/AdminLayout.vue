<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import MusicBar from '@/components/MusicBar.vue';
import { useAudioPlayer } from '@/composables/useAudioPlayer';

const page = usePage();
const currentPath = computed(() => page.url);
const audioStore = useAudioPlayer();

const navLinks = [
    { label: 'Dashboard', href: '/admin', icon: '⬡' },
    { label: 'Characters', href: '/admin/characters', icon: '◈' },
    { label: 'Diary Entries', href: '/admin/diary-entries', icon: '◉' },
    { label: 'Music Tracks', href: '/admin/music-tracks', icon: '◎' },
    { label: 'Novel', href: '/admin/novel', icon: '◆' },
    { label: 'Universe', href: '/admin/universe', icon: '◇' },
    { label: 'Products', href: '/admin/products', icon: '◈' },
];

function isActive(href: string): boolean {
    if (href === '/admin') {
        return currentPath.value === '/admin';
    }

    return currentPath.value.startsWith(href);
}
</script>

<template>
    <div class="flex min-h-screen" style="background: #0a0a0f; color: #e8e8f0">
        <!-- Sidebar -->
        <aside
            class="flex w-56 flex-shrink-0 flex-col border-r"
            style="
                border-color: rgba(255, 255, 255, 0.08);
                background: rgba(255, 255, 255, 0.02);
            "
        >
            <!-- Logo -->
            <div
                class="flex h-16 items-center border-b px-5"
                style="border-color: rgba(255, 255, 255, 0.08)"
            >
                <Link href="/" class="flex items-center gap-2">
                    <div
                        class="h-7 w-7 flex-shrink-0 rounded-full"
                        style="
                            background: linear-gradient(
                                135deg,
                                #8b5cf6 0%,
                                #f97316 100%
                            );
                        "
                    ></div>
                    <span
                        class="text-sm font-bold tracking-widest uppercase"
                        style="letter-spacing: 0.1em; color: #e8e8f0"
                        >S. Coalescence</span
                    >
                </Link>
            </div>

            <!-- Admin Label -->
            <div class="px-5 pt-5 pb-2">
                <p
                    class="text-xs tracking-widest uppercase"
                    style="color: #4b5563"
                >
                    Admin Panel
                </p>
            </div>

            <!-- Nav -->
            <nav class="flex flex-1 flex-col gap-0.5 px-3 pb-4">
                <Link
                    v-for="link in navLinks"
                    :key="link.href"
                    :href="link.href"
                    class="flex items-center gap-3 rounded px-3 py-2 text-sm tracking-wide transition-all duration-150"
                    :style="
                        isActive(link.href)
                            ? 'background: rgba(139,92,246,0.15); color: #a78bfa; border-left: 2px solid #8b5cf6;'
                            : 'color: #6b7280; border-left: 2px solid transparent;'
                    "
                >
                    <span class="text-xs">{{ link.icon }}</span>
                    {{ link.label }}
                </Link>
            </nav>

            <!-- Footer -->
            <div
                class="border-t px-5 py-4"
                style="border-color: rgba(255, 255, 255, 0.08)"
            >
                <Link
                    href="/"
                    class="text-xs tracking-wider uppercase transition-colors"
                    style="color: #4b5563"
                    onmouseenter="this.style.color = '#9ca3af'"
                    onmouseleave="this.style.color = '#4b5563'"
                >
                    ← Public Site
                </Link>
            </div>
        </aside>

        <!-- Main -->
        <div class="flex min-w-0 flex-1 flex-col">
            <!-- Top bar -->
            <header
                class="flex h-16 flex-shrink-0 items-center justify-between border-b px-8"
                style="
                    border-color: rgba(255, 255, 255, 0.08);
                    background: rgba(255, 255, 255, 0.01);
                "
            >
                <h1
                    class="text-sm font-medium tracking-wide"
                    style="color: #9ca3af"
                >
                    Administration
                </h1>
                <div class="flex items-center gap-4">
                    <Link
                        href="/settings/profile"
                        class="text-xs tracking-wider uppercase transition-colors"
                        style="color: #6b7280"
                        onmouseenter="this.style.color = '#9ca3af'"
                        onmouseleave="this.style.color = '#6b7280'"
                    >
                        Settings
                    </Link>
                    <Link
                        href="/logout"
                        method="post"
                        as="button"
                        class="rounded border px-3 py-1.5 text-xs tracking-widest uppercase transition-colors"
                        style="
                            border-color: rgba(239, 68, 68, 0.3);
                            color: #ef4444;
                        "
                        onmouseenter="
                            this.style.background = 'rgba(239,68,68,0.08)'
                        "
                        onmouseleave="this.style.background = 'transparent'"
                    >
                        Logout
                    </Link>
                </div>
            </header>

            <!-- Flash messages -->
            <div
                v-if="$page.props.flash?.success"
                class="mx-8 mt-4 rounded border px-4 py-3 text-sm"
                style="
                    background: rgba(139, 92, 246, 0.1);
                    border-color: rgba(139, 92, 246, 0.3);
                    color: #a78bfa;
                "
            >
                {{ $page.props.flash.success }}
            </div>

            <main
                class="flex-1 overflow-auto p-8"
                :class="audioStore.started ? 'pb-28' : ''"
            >
                <slot />
            </main>
        </div>

        <MusicBar />
    </div>
</template>
