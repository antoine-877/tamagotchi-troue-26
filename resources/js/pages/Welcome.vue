<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { dashboard, login, register } from '@/routes';
</script>

<template>
    <Head title="Bienvenue" />

    <main
        class="flex min-h-screen flex-col items-center justify-center gap-6 p-6 text-center"
    >
        <p class="text-6xl" aria-hidden="true">🐉 🐱 🐙 🦊 🐰</p>
        <h1 class="text-3xl font-semibold">Tamagotchi</h1>
        <p class="max-w-md text-muted-foreground">
            Adoptez des créatures, nourrissez-les, faites-les jouer et visitez
            l'enclos des autres joueurs.
        </p>

        <div class="flex gap-3">
            <Button v-if="$page.props.auth.user" as-child>
                <Link :href="dashboard()">Mes créatures</Link>
            </Button>
            <template v-else>
                <Button as-child>
                    <Link :href="login()">Se connecter</Link>
                </Button>
                <Button as-child variant="outline">
                    <Link :href="register()">Créer un compte</Link>
                </Button>
            </template>
        </div>
    </main>
</template>
