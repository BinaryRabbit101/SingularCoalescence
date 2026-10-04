<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import MusicBar from '@/components/MusicBar.vue';
import { useAudioPlayer } from '@/composables/useAudioPlayer';

const page = usePage();
const user = page.props.auth?.user;
const audioStore = useAudioPlayer();
const menuOpen = ref(false);

const navLinks = [
    { label: 'Characters', href: '/characters' },
    { label: 'Novel', href: '/novel' },
    { label: 'Universe', href: '/universe' },
    { label: 'Products', href: '/products' },
];
</script>

<template>
    <div class="min-h-screen flex flex-col" style="background: #0a0a0f; color: #e8e8f0;">
        <!-- Navbar -->
        <nav class="fixed top-0 left-0 right-0 z-50 border-b border-white/10"
             style="background: linear-gradient(90deg, rgba(139,92,246,0.15) 0%, rgba(10,10,15,0.95) 50%, rgba(249,115,22,0.15) 100%); backdrop-filter: blur(12px);">
            <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
                <!-- Logo / Brand -->
                <Link href="/" class="flex items-center gap-3 group">
                    <div class="w-8 h-8 rounded-full flex-shrink-0 animate-glow-pulse"
                         style="background: linear-gradient(135deg, #8b5cf6 0%, #f97316 100%);"></div>
                    <span class="font-bold text-xs sm:text-sm tracking-widest uppercase" style="letter-spacing: 0.15em;">
                        <span style="background: linear-gradient(90deg, #8b5cf6, #f97316); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">Singular Coalescence</span>
                    </span>
                </Link>

                <!-- Nav links -->
                <div class="hidden md:flex items-center gap-8">
                    <Link
                        v-for="link in navLinks"
                        :key="link.href"
                        :href="link.href"
                        class="relative text-sm tracking-wider uppercase transition-colors duration-200 group py-1"
                        style="color: #9ca3af;"
                        onmouseenter="this.style.color='#e8e8f0'"
                        onmouseleave="this.style.color='#9ca3af'"
                    >
                        {{ link.label }}
                        <span class="absolute bottom-0 left-0 right-0 h-px origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-300"
                              style="background: linear-gradient(90deg, #8b5cf6, #f97316);"></span>
                    </Link>
                </div>

                <!-- Auth -->
                <div class="flex items-center gap-3">
                    <Link v-if="user" href="/admin"
                          class="hidden sm:inline-block text-xs tracking-widest uppercase px-4 py-2 rounded border transition-colors duration-200"
                          style="border-color: #8b5cf6; color: #8b5cf6;"
                          onmouseenter="this.style.background='rgba(139,92,246,0.1)'"
                          onmouseleave="this.style.background='transparent'"
                    >
                        Admin
                    </Link>

                    <button type="button"
                            class="md:hidden w-10 h-10 flex items-center justify-center rounded border"
                            style="border-color: rgba(255,255,255,0.2); color: #9ca3af;"
                            :aria-expanded="menuOpen"
                            aria-controls="mobile-menu"
                            aria-label="Toggle menu"
                            @click="menuOpen = !menuOpen"
                    >
                        <svg v-if="!menuOpen" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 7h16M4 12h16M4 17h16" /></svg>
                        <svg v-else width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M6 6l12 12M18 6L6 18" /></svg>
                    </button>
                </div>
            </div>

            <!-- Mobile menu -->
            <div v-if="menuOpen" id="mobile-menu" class="md:hidden border-t border-white/10 px-6 py-3 flex flex-col" style="background: rgba(10,10,15,0.97);">
                <Link
                    v-for="link in navLinks"
                    :key="link.href"
                    :href="link.href"
                    class="py-3 text-sm tracking-wider uppercase"
                    style="color: #9ca3af;"
                    @click="menuOpen = false"
                >
                    {{ link.label }}
                </Link>
                <Link v-if="user" href="/admin" class="py-3 text-sm tracking-wider uppercase" style="color: #8b5cf6;" @click="menuOpen = false">
                    Admin
                </Link>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="flex-1 pt-16" :class="audioStore.started ? 'pb-24' : ''">
            <slot />
        </main>

        <!-- Footer -->
        <footer class="border-t py-8" style="border-color: rgba(255,255,255,0.08);">
            <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-2">
                    <div class="w-5 h-5 rounded-full flex-shrink-0" style="background: linear-gradient(135deg, #8b5cf6 0%, #f97316 100%);"></div>
                    <span class="text-xs tracking-widest uppercase" style="color: #6b7280;">Singular Coalescence</span>
                </div>
                <div class="flex flex-wrap items-center justify-center gap-x-6 gap-y-2">
                    <Link v-for="link in navLinks" :key="link.href" :href="link.href"
                          class="text-xs tracking-wider uppercase transition-colors"
                          style="color: #4b5563;"
                          onmouseenter="this.style.color='#9ca3af'"
                          onmouseleave="this.style.color='#4b5563'"
                    >
                        {{ link.label }}
                    </Link>
                </div>
                <p class="text-xs" style="color: #4b5563;">One consciousness. An infinite universe.</p>
            </div>
        </footer>

        <!-- Persistent music bar — lives here so it survives Inertia navigations -->
        <MusicBar />
    </div>
</template>
