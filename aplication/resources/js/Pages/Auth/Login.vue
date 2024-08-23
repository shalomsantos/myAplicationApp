<template>
    <v-row align="center" no gutters class="bg-grey-lighten-4 h-screen">
        <v-col cols="3" class="mx-auto">
            <form @submit.prevent="submit" class="d-flex flex-column">
                <h1 class="mb-3">Login</h1>
                <v-text-field
                    type="email"
                    label="E-mail"
                    v-model="form.email"
                    variant="outlined"
                    :error-messages="form.errors.email"
                    autofocus
                    required
                ></v-text-field>

                <v-text-field
                    type="password"
                    label="Senha"
                    v-model="form.password"
                    variant="outlined"
                    :error-messages="form.errors.password"
                    required
                    autocomplete="current-password"
                ></v-text-field>

                <v-btn variant="tonal" type="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" size="x-large" class="bg-blue-grey-darken-3 mb-3">
                    Log in
                </v-btn>
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    Esqueceu a sua senha?
                </Link>
            </form>
        </v-col>
    </v-row>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>


