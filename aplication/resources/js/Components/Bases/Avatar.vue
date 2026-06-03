<template>
    <v-sheet
        v-if="!avatar?.updated_at"
        class="d-flex justify-space-between align-end bg-transparent"
    >
        <v-tooltip :text="avatar.created_by.name" location="top">
            <template v-slot:activator="{ props }">
                <v-avatar
                    v-bind="props"
                    color="green-darken-1"
                    density="compact"
                >
                    <span class="text-headline-small">{{ avatarNick(avatar.created_by.name) }}</span>
                </v-avatar>
            </template>
        </v-tooltip>
        <p class="text-body-2 text-disabled">
            {{ isDate(avatar.created_at) }}
        </p>
    </v-sheet>
    <v-sheet v-else class="d-flex justify-space-between align-end bg-transparent">
        <v-tooltip :text="avatar.updated_by?.name" location="top">
            <template v-slot:activator="{ props }">
                <v-avatar
                    v-bind="props"
                    color="green-darken-1"
                    density="compact"
                >
                    <span class="text-headline-small">{{ avatarNick(avatar.updated_by?.name) }}</span>
                </v-avatar>
            </template>
        </v-tooltip>
        <p class="text-body-2 text-disabled">
            <v-icon
                size="small"
                icon="mdi-history"
                color="info"
                class="mr-1"
            ></v-icon>
            {{ isDate(avatar.updated_at) }}
        </p>
    </v-sheet>
</template>

<script setup>
const props = defineProps({
    avatar: Object,
});
</script>

<style scoped></style>
